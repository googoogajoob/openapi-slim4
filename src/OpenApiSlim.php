<?php
declare(strict_types = 1);

namespace OpenApiSlim4;

use cebe\openapi\spec\OpenApi;
use cebe\openapi\Reader;
use cebe\openapi\exceptions\IOException;
use cebe\openapi\exceptions\TypeErrorException;
use cebe\openapi\exceptions\UnresolvableReferenceException;
use Slim\App;
use Psr\Log\LoggerInterface;

class OpenApiSlim4 implements OpenApiSlim4ConfigurationInterface
{
    const PERMITTED_HTTP_METHODS = ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS', 'HEAD'];
    protected ?App $SlimApplication = null;
    protected ?LoggerInterface $logger = null;
    protected string|OpenApi|null $openApi = null;
    protected array $pathConfigurationData = [];
    protected bool $throwValidationException = false;
    protected array $validationMessages = [];

    /**
     * Class properties can be set via the constructor or through setters
     *
     * @param LoggerInterface|null $logger
     * @param App|null $slimApplication
     * @param string|OpenApi|null $openApi
     * @param bool|null $throwValidationException
     * @throws IOException
     * @throws TypeErrorException
     * @throws UnresolvableReferenceException
     */
    public function __construct(?LoggerInterface $logger = null,
                                ?App $slimApplication = null,
                                string|OpenApi|null $openApi = null,
                                ?bool $throwValidationException = null)
    {
        if (!is_null($logger)) {
            $this->setLogger($logger);
        }
        if (!is_null($slimApplication)) {
            $this->setSlimApplication($slimApplication);
        }
        if (!is_null($openApi)) {
            $this->setOpenApi($openApi);
        }
        if (!is_null($throwValidationException)) {
            $this->setThrowValidationException($throwValidationException);
        }
    }

    /**
     * Validate input values and attempt the slim configuration, if the configuration fails validity is false
     *
     * @return bool
     * @throws OpenApiSlim4Exception
     */
    public function configureFramework(): bool
    {
        $isValid = $this->validate();
        $isValid = $isValid && $this->configureSlimRoutes();
        $isValid = $isValid && $this->configureSlimGlobalMiddleware();
        if (!$isValid && $this->throwValidationException) {
            throw new OpenApiSlim4Exception(implode(PHP_EOL, $this->validationMessages));
        }

        return $isValid;
    }

    /**
     * @return bool
     */
    protected function configureSlimGlobalMiddleware(): bool
    {
        if (isset($this->openApi->components->{'x-middleware'})) {
            foreach ($this->openApi->components->{'x-middleware'} as $globalMiddleware) {
                $this->SlimApplication->add($globalMiddleware);
            }
        }

        return true;
    }

    /**
     * @return bool
     */
    protected function configureSlimRoutes(): bool
    {
        foreach ($this->pathConfigurationData as $path => $OpenApiPathData) {
            foreach ($OpenApiPathData as $httpMethod => $configuration) {
                $route = $this->SlimApplication->map([strtoupper($httpMethod)], $path, $configuration['operationId']);
                if (isset($configuration['x-middleware'])) {
                    foreach ($configuration['x-middleware'] as $middleware) {
                        $route->add($middleware);
                    }
                }
            }
        }

        return true;
    }

    /**
     * @return bool
     */
    protected function getPathConfigurationData(): bool
    {
        foreach ($this->openApi->paths->getPaths() as $path => $pathConfiguration) {
            $httpMethods = $pathConfiguration->getOperations();
            foreach ($httpMethods as $httpMethod => $pathMethodConfiguration) {
                $this->pathConfigurationData[$path][$httpMethod]['operationId'] = $pathMethodConfiguration->operationId;
                if (isset($pathMethodConfiguration->{'x-middleware'})) {
                    foreach ($pathMethodConfiguration->{'x-middleware'} as $middleware) {
                        $this->pathConfigurationData[$path][$httpMethod]['x-middleware'][] = $middleware;
                    }
                }
            }
        }

        return false;
    }

    /**
     * @param string $httpMethod
     * @return bool
     */
    protected function isHttpMethodPermitted(string $httpMethod): bool
    {
        return in_array(strtoupper($httpMethod), self::PERMITTED_HTTP_METHODS);
    }

    /**
     * @param LoggerInterface $logger
     * @return OpenApiSlim4ConfigurationInterface
     */
    public function setLogger(LoggerInterface $logger): OpenApiSlim4ConfigurationInterface
    {
        $this->logger = $logger;

        return $this;
    }

    /**
     * Set the source of the OpenApi Configuration Definition
     *
     * @param String|OpenApi $openApi
     * @return OpenApiSlim4ConfigurationInterface
     * @throws IOException
     * @throws TypeErrorException
     * @throws UnresolvableReferenceException
     */
    public function setOpenApi(string|OpenApi $openApi): OpenApiSlim4ConfigurationInterface
    {
        if (is_string($openApi)) {
            $this->openApi = Reader::readFromYamlFile($openApi);
        } else {
            $this->openApi = $openApi;
        }

        return $this;
    }

    protected function resolveOpenApiObject(): OpenApiSlim4ConfigurationInterface
    {

    }

    /**
     * @param App $SlimApplication
     * @return OpenApiSlim4ConfigurationInterface
     */
    public function setSlimApplication(App $SlimApplication): OpenApiSlim4ConfigurationInterface
    {
        $this->SlimApplication = $SlimApplication;

        return $this;
    }

    /**
     * A switch which determines if an exception should be thrown when the validation is unsuccessful
     *
     * @param bool $throwValidationException
     * @return OpenApiSlim4ConfigurationInterface
     */
    public function setThrowValidationException(bool $throwValidationException): OpenApiSlim4ConfigurationInterface
    {
        $this->throwValidationException = $throwValidationException;

        return $this;
    }

    /**
     * @return bool
     */
    protected function validate(): bool
    {
        $isValid = $this->validateClassProperties();
        $isValid = $isValid && $this->validateOpenApiDefinition();
        $isValid = $isValid && $this->validateSlimApplication();
        $isValid = $isValid && $this->getPathConfigurationData();

        return $isValid;
    }

    /*
    protected function validate(): bool
    {
        if (!count($this->pathConfigurationData)) {
            $this->logger->error('No paths(routes) defined');

            return false;
        }

        foreach ($this->pathConfigurationData as $path => $pathConfigurationData) {
            foreach ($pathConfigurationData as $httpMethod => $handler) {
                if (!$this->isHttpMethodPermitted($httpMethod)) {
                    #$this->logger->error('Http Method is not allowed: ' . $httpMethod);

                    return false;
                }
                $handlerParts = explode(':', $handler);
                $class = $handlerParts[0];
                if (!class_exists($class)) {
                    #$this->logger->error('Handler Class does not exist: ' . $class);

                    return false;
                }
                $classMethod = ($handlerParts[1] ? $handlerParts[1] : '__invoke');
                if (!method_exists($class, $classMethod)) {
                    #$this->logger->error('Handler Class Method does not exist: ' . $class . '->' . $classMethod);

                    return false;
                }
            }
        }

        return true;
    }
    */

    /**
     * Validate that the necessary class properties have been set
     *
     * @return bool
     */
    protected function validateClassProperties(): bool
    {
        $returnValue = true;
        if (!$this->SlimApplication) {
            $this->validationMessages[] ='Slim Application is not defined';
            $returnValue = false;
        }
        if (!$this->openApi) {
            $returnValue = false;
            $this->validationMessages[] ='Openapi object is not defined';
        }

        return $returnValue;
    }

    /**
     * @return bool
     */
    protected function validateOpenApiDefinition(): bool
    {
        $returnValue = true;
        if (!$this->openApi instanceof Reader) {
            $returnValue = false;
            $this->validationMessages[] = 'OpenApiDefinition must be of type: ' . Reader::class;
        }

        return $returnValue;
    }

    /**
     * Validate that the required version of Slim is being used
     *
     * @return bool
     */
    protected function validateSlimApplication(): bool
    {
        $returnValue = true;
        if (!str_starts_with($this->SlimApplication::VERSION, '4')) {
            $this->validationMessages[] ='Slim Version 4 is required';
            $returnValue = false;
        }

        return $returnValue;
    }
}

/**
 * ToDo:
 * Catch exceptions from CeBe and transfer them to my Exceptions
 * Catch exceptions from Slim and transfer them to my Exceptions
 */


