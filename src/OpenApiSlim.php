<?php
declare(strict_types=1);

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
    protected ?LoggerInterface $logger = null;
    protected ?OpenApi $openApi = null;
    protected array $pathConfigurationData = [];
    protected ?App $slimApp = null;
    protected bool $throwValidationException = false;
    protected array $validationMessages = [];

    /**
     * All Class variables can be set via the constructor or through setters
     *
     * @param LoggerInterface|null $logger
     * @param App|null $slimApp
     * @param string|OpenApi|null $openApi
     * @param bool|null $throwValidationException
     * @throws IOException
     * @throws TypeErrorException
     * @throws UnresolvableReferenceException
     */
    public function __construct(?LoggerInterface $logger = null, ?App $slimApp = null, string|OpenApi|null $openApi = null, bool $throwValidationException = null)
    {
        if (!is_null($logger)) {
            $this->setLogger($logger);
        }
        if (!is_null($slimApp)) {
            $this->setSlimApp($slimApp);
        }
        if (!is_null($openApi)) {
            $this->setOpenApi($openApi);
        }
        if (!is_null($throwValidationException)) {
            $this->setThrowValidationException($throwValidationException);
        }
    }

    /**
     * @return bool
     * @throws OpenApiSlim4Exception
     */
    public function configureFramework(): bool
    {
        $isValid = $this->validateClassParameters();
        $isValid = $isValid && $this->validateOpeanApiDefinition();
        $isValid = $isValid && $this->getPathConfigurationData();
        if ($isValid) {
            return $this->configureSlimRoutes() && $this->configureSlimGlobalMiddleware();
        } elseif ($this->throwValidationException) {
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
                $this->slimApp->add($globalMiddleware);
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
                $route = $this->slimApp->map([strtoupper($httpMethod)], $path, $configuration['operationId']);
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
     * @return void
     */
    protected function getPathConfigurationData(): void
    {
        if (count($this->pathConfigurationData)) {
            if ($this->logger) {
                $this->logger->debug('pathConfigurationData already defined');
            }
        } else {
            $openApiPaths = $this->openApi->paths->getPaths();
        }
        foreach ($openApiPaths as $path => $pathConfiguration) {
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

    /**
     * @param App $slimApp
     * @return OpenApiSlim4ConfigurationInterface
     */
    public function setSlimApp(App $slimApp): OpenApiSlim4ConfigurationInterface
    {
        $this->slimApp = $slimApp;

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
     * Validate all required components
     *
     * @return bool
     */
    protected function validate(): bool
    {
        #$this->logger->info('Performing validation');

        #$this->logger->debug('Validate Slim Version');
        if (substr($this->slimApp::VERSION, 0, 1) != '4') {
            #$this->logger->error('Slim Version 4.*.* is required. Given Version is: ' . $this->slimApp::VERSION);

            return false;
        }

        #$this->logger->debug('Validate Paths (Routes)');
        #$this->getPathConfigurationData();
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

        #$this->logger->info('Validation successful');

        return true;
    }
#    protected function validateOpenApiDefinition(): bool
#    {
#        #$this->logger->debug('Validate OpenApiDefinition');
#        if (!$this->OpenApi instanceof Reader) {
#            #$this->logger->error('OpenApiDefinition must be of type: cebe\openapi\Reader');
#
#            return false;
#        }
#
#        return false;
#    }
}
