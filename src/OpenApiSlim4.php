<?php
declare(strict_types = 1);

namespace OpenApiSlim4;

use cebe\openapi\exceptions\IOException;
use cebe\openapi\exceptions\TypeErrorException;
use cebe\openapi\exceptions\UnresolvableReferenceException;
use cebe\openapi\json\InvalidJsonPointerSyntaxException;
use cebe\openapi\Reader;
use cebe\openapi\spec\OpenApi;
use Exception;
use Psr\Log\LoggerInterface;
use Slim\App;

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
     *   Required: $openApi and $slimApplication
     *   Optional: $logger and $throwValidationException
     *
     * @param string|OpenApi|null $openApi //Openapi File (json|yaml) | cebe\openapi\Reader
     * @param App|null $slimApplication
     * @param LoggerInterface|null $logger // no logging output  when null
     * @param bool|null $throwValidationException // throw an OpenApiSlim4Exception if validation errors have occurred
     */
    public function __construct(string|OpenApi|null $openApi = null,
                                ?App $slimApplication = null,
                                ?LoggerInterface $logger = null,
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
     * Configure slim global middleware based on the openapi definition
     *
     * @return bool
     */
    protected function configureSlimGlobalMiddleware(): bool
    {
        $returnValue = true;
        try {
            if (isset($this->openApi->components->{'x-middleware'})) {
                foreach ($this->openApi->components->{'x-middleware'} as $globalMiddleware) {
                    $this->SlimApplication->add($globalMiddleware);
                }
            }
        } catch (Exception $exception) {
            $returnValue = false;
            $this->validationMessages[] = $exception->getMessage();
        }

        return $returnValue;
    }

    /**
     * Configure slim routes/paths based on the openapi definition
     *
     * @return bool
     */
    protected function configureSlimRoutes(): bool
    {
        $returnValue = true;
        try {
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
        } catch (Exception $exception) {
            $returnValue = false;
            $this->validationMessages[] = $exception->getMessage();
        }

        return $returnValue;
    }

    /**
     * Obtain relevant configuration data for Slim from the openapi definition
     *
     * @return bool
     */
    protected function getPathConfigurationData(): bool
    {
        $returnValue = true;
        try {
            foreach ($this->openApi->paths->getPaths() as $path => $pathConfiguration) {
                $httpMethods = $pathConfiguration->getOperations();
                foreach ($httpMethods as $httpMethod => $pathMethodConfiguration) {
                    if (!$this->isHttpMethodPermitted($httpMethod)) {
                        throw new OpenApiSlim4Exception('Method not permitted: ' . $httpMethod);
                    }
                    $this->pathConfigurationData[$path][$httpMethod]['operationId'] = $pathMethodConfiguration->operationId;
                    if (isset($pathMethodConfiguration->{'x-middleware'})) {
                        foreach ($pathMethodConfiguration->{'x-middleware'} as $middleware) {
                            $this->pathConfigurationData[$path][$httpMethod]['x-middleware'][] = $middleware;
                        }
                    }
                }
            }
        } catch (Exception $exception) {
            $returnValue = false;
            $this->validationMessages[] = $exception->getMessage();
        }

        return $returnValue;
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
     * Read Openapi definition from a Json or Yaml file.
     *
     * @return OpenApiSlim4ConfigurationInterface
     * @throws IOException
     * @throws TypeErrorException
     * @throws UnresolvableReferenceException
     * @throws InvalidJsonPointerSyntaxException
     */
    protected function resolveOpenApiObject(): OpenApiSlim4ConfigurationInterface
    {
        if (is_string($this->openApi)) {
            $extension = strtoupper(substr(strrchr($this->openApi, '.'), 1));
            if ($extension === 'JSON') {
                $this->openApi = Reader::readFromJsonFile($this->openApi);
            } else {
                $this->openApi = Reader::readFromYamlFile($this->openApi);
            }
        }

        return $this;
    }

    /**
     * Set Logger
     *
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
     * @param string|OpenApi $openApi
     * @return OpenApiSlim4ConfigurationInterface
     */
    public function setOpenApi(string|OpenApi $openApi): OpenApiSlim4ConfigurationInterface
    {
        $this->openApi = $openApi;

        return $this;
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
     * Set a switch which determines if an exception should be thrown when the validation is unsuccessful
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
        $isValid = $this->validateClassProperties();
        $isValid = $isValid && $this->validateOpenApiDefinition();
        $isValid = $isValid && $this->validateSlimApplication();
        $isValid = $isValid && $this->getPathConfigurationData();

        return $isValid;
    }

    /**
     * Validate that the necessary class properties have been set
     *
     * @return bool
     */
    protected function validateClassProperties(): bool
    {
        $returnValue = true;
        if (!is_null($this->SlimApplication)) {
            $this->validationMessages[] ='Slim Application is not defined';
            $returnValue = false;
        }
        if (!is_null($this->openApi)) {
            $returnValue = false;
            $this->validationMessages[] ='Openapi object is not defined';
        }

        return $returnValue;
    }

    /**
     * Validate the following aspects of the openapi definition:
     *   1) Is the input file (json|yaml) readable
     *   2) Can an Object of type Reader be created
     *   1) Is the Openapi definition valid
     *
     * @return bool
     */
    protected function validateOpenApiDefinition(): bool
    {
        $returnValue = true;
        try {
            $this->resolveOpenApiObject();
        } catch (TypeErrorException | UnresolvableReferenceException | IOException | InvalidJsonPointerSyntaxException $exception) {
            $returnValue = false;
            $this->validationMessages[] = $exception->getMessage();
        }
        if ($returnValue && !$this->openApi instanceof Reader) {
            $returnValue = false;
            $this->validationMessages[] = 'OpenApiDefinition must be of type: ' . Reader::class;
        }
        if ($returnValue && !$this->openApi->validate()) {
            $returnValue = false;
            $this->validationMessages[] = implode(PHP_EOL, $this->openApi->getErrors());
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