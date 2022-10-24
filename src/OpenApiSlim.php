<?php
declare(strict_types=1);

namespace OpenApiSlim;

use cebe\openapi\Reader;
use Slim\App;
use Psr\Log\LoggerInterface;

class OpenApiSlim implements OpenApiConfigurationInterface
{
    const PERMITTED_HTTP_METHODS = ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'];
    protected Reader $openApiReader;
    protected App $slimApp;
    protected LoggerInterface $logger;
    protected bool $isValidated = false;
    protected array $pathConfigurationData = [];

    /**
     * @param Reader $openApiReader
     * @return OpenApiConfigurationInterface
     */
    public function setOpenApiReader(Reader $openApiReader): OpenApiConfigurationInterface
    {
        $this->openApiReader = $openApiReader;

        return $this;
    }

    /**
     * @param App $slimApp
     * @return OpenApiConfigurationInterface
     */
    public function setSlimApp(App $slimApp): OpenApiConfigurationInterface
    {
        $this->slimApp = $slimApp;

        return $this;
    }

    /**
     * @param LoggerInterface $logger
     * @return OpenApiConfigurationInterface
     */
    public function setLogger(LoggerInterface $logger): OpenApiConfigurationInterface
    {
        $this->logger = $logger;

        return $this;
    }

    /**
     * OpenApiSlim constructor.
     * @param Reader $openApiDefinition
     * @param App $slimApp
     * @param LoggerInterface $logger
     */
    public function __construct(Reader $openApiReader, App $slimApp, LoggerInterface $logger)
    {
        $this->openApiDefinition = $openApiReader;
        $this->slimApp = $slimApp;
        $this->logger = $logger;
    }

    /**
     * @return bool
     */
    public function configureSlimFramework(): bool
    {
        if (!$this->isValidated) {
            if (!$this->validate()) {
                return false;
            }
        }

        return $this->configureSlimRoutes() && $this->configureSlimGlobalMiddleware();
    }

    /**
     * @return bool
     */
    protected function configureSlimRoutes(): bool
    {
        foreach ($this->pathConfigurationData as $path => $pathConfigurationData) {
            foreach ($pathConfigurationData as $httpMethod => $handler) {
                $this->slimApp->map([$httpMethod], $path, $handler);

                return false;
            }
        }

        return true;
    }

    /**
     * @return bool
     */
    protected function configureSlimGlobalMiddleware(): bool
    {
        return false;
    }

    /**
     *
     */
    protected function getPathConfigurationData()
    {
        if (count($this->pathConfigurationData)) {
            $this->logger->debug('PathConfigurationData already defined');
        } else {
            $openApiPaths = $this->openApiReader->paths->getPaths();
        }
        foreach ($openApiPaths as $path => $pathConfiguration) {
            $httpMethods = $pathConfiguration->getOperations();
            foreach ($httpMethods as $httpMethod => $pathMethodConfiguration) {
                $this->pathConfigurationData[$path][$httpMethod] = $pathMethodConfiguration->operationId;
            }
        }
    }

    protected function isHttpMethodPermitted(string $httpMethod): bool
    {
        return in_array(strtoupper($httpMethod), self::PERMITTED_HTTP_METHODS);
    }

    /**
     * @return bool
     */
    protected function validate(): bool
    {
        $this->isValidated = false;
        $this->logger->info('Performing validation');

        $this->logger->debug('Validate Slim Version');
        if (substr($this->slimApp::VERSION, 0, 1) != '4') {
            $this->logger->error('Slim Version 4.*.* is required. Given Version is: ' . $this->slimApp::VERSION);

            return false;
        }

        $this->logger->debug('Validate Paths (Routes)');
        $this->getPathConfigurationData();
        if (!count($this->pathConfigurationData)) {
            $this->logger->error('No paths(routes) defined');

            return false;
        }

        foreach ($this->pathConfigurationData as $path => $pathConfigurationData) {
            foreach ($pathConfigurationData as $httpMethod => $handler) {
                if (!$this->isHttpMethodPermitted($httpMethod)) {
                    $this->logger->error('Http Method is not allowed: ' . $httpMethod);

                    return false;
                }
                $handlerParts = explode(':', $handler);
                $class = $handlerParts[0];
                if (!class_exists($class)) {
                    $this->logger->error('Handler Class does not exist: ' . $class);

                    return false;
                }
                $classMethod = ($handlerParts[1] ? $handlerParts[1] : '__invoke');
                if (!method_exists($class, $classMethod)) {
                    $this->logger->error('Handler Class Method does not exist: ' . $class . '->' . $classMethod);

                    return false;
                }
            }
        }

        $this->isValidated = true;
        $this->logger->info('Validation successful');

        return true;
    }

    protected function validateOpenApiDefinition(): bool
    {
        $this->logger->debug('Validate OpenApiDefinition');
        if (!$this->openApiReader instanceof Reader) {
            $this->logger->error('OpenApiDefinition must be of type: cebe\openapi\Reader');

            return false;
        }

        return false;
    }
}
