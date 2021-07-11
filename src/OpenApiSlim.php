<?php
declare(strict_types=1);

namespace OpenApiSlim;

use cebe\openapi\SpecObjectInterface;
use Slim\App;
use Psr\Log\LoggerInterface;

class OpenApiSlim implements OpenApiSlimInterface
{
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
        return false;
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
            $methods = $pathConfiguration->getOperations();
            foreach ($methods as $method => $pathMethodConfiguration) {
                $this->pathConfigurationData[$path][$method] = $pathMethodConfiguration->operationId;
            }
        }
    }

    /**
     * @return bool
     */
    public function validate(): bool
    {
        $this->logger->info('Performing validation');
        if (!$this->openApiDefinition instanceof SpecObjectInterface) {
            $this->logger->error('OpenApiDefinition must be of type: cebe\openapi\SpecObjectInterface');

            return false;
        }
        if (substr($this->slimApp::VERSION, 0, 1) != '4') {
            $this->logger->error('Slim Version 4.*.* is required. Given Version is: ' . $this->slimApp::VERSION);

            return false;
        }

        $this->getPathConfigurationData();
        if (!count($this->pathConfigurationData)) {
            $this->logger->error('No path Definitions defined');

            return false;
        }
        foreach ($this->pathConfigurationData as $path => $pathConfigurationData) {
            foreach ($pathConfigurationData as $method => $handler) {
                $handlerParts = explode(':', $handler);
                $class = $handlerParts[0];
                $classMethod = $handlerParts[1];
                if (!class_exists($class)) {
                    $this->logger->error('Handler Class does not exist: ' . $class);

                    return false;
                } elseif ($classMethod && !method_exists($class, $classMethod)) {
                    $this->logger->error('Handler Class Method does not exist: ' . $class . '->' . $classMethod);

                    return false;
                }
            }
        }

        $this->isValidated = true;
        $this->logger->info('Validation successful');

        return true;
    }
}