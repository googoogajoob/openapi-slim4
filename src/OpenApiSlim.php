<?php
declare(strict_types=1);

namespace OpenApiSlim;

use cebe\openapi\SpecObjectInterface;
use Slim\App;
use Psr\Log\LoggerInterface;

class OpenApiSlim implements OpenApiSlimInterface
{
    const PERMITTED_HTTP_METHODS = ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD'];
    protected $openApiDefinition;
    protected App $slimApp;
    protected LoggerInterface $logger;
    protected bool $isValidated = false;
    protected array $pathConfigurationData = [];

    /**
     * OpenApiSlim constructor.
     *
     * @param $openApiDefinition
     *        must be of type cebe\openapi\SpecObjectInterface
     * @param App $slimApp
     *        must be version 4
     * @param LoggerInterface $logger
     */
    public function __construct($openApiDefinition, App $slimApp, LoggerInterface $logger)
    {
        $this->openApiDefinition = $openApiDefinition;
        $this->slimApp = $slimApp;
        $this->logger = $logger;
    }

    /**
     * @return bool
     */
    public function configureSlim(): bool
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
            $openApiPaths = $this->openApiDefinition->paths->getPaths();
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
    public function validate(): bool
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
        if (!$this->openApiDefinition instanceof SpecObjectInterface) {
            $this->logger->error('OpenApiDefinition must be of type: cebe\openapi\SpecObjectInterface');

            return false;
        }

        return false;
    }
}