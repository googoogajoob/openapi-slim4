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
     * @param string|OpenApi|null $openApi
     * @param App|null $slimApplication
     * @param LoggerInterface|null $logger
     * @param bool|null $throwValidationException
     */
    public function __construct(string|OpenApi|null $openApi = null,
                                ?App $slimApplication = null,
                                ?LoggerInterface $logger = null,
                                ?bool $throwValidationException = null)
    {
        $this->setOpenApi($openApi);
        $this->setSlimApplication($slimApplication);
        $this->setLogger($logger);
        $this->setThrowValidationException($throwValidationException);
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
            throw new OpenApiSlim4Exception($this->getValidationMessagesString());
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
            $this->validationMessages[] = 'Exception configuring Global Middleware: ' . $exception->getMessage();
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
            $this->validationMessages[] = 'Exception configuring Slim Routes: ' . $exception->getMessage();
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
            $this->validationMessages[] = 'Exception retrieving Path configuration data: ' . $exception->getMessage();
        }

        return $returnValue;
    }

    /**
     * @return array
     */
    public function getValidationMessagesArray(): array
    {
        return $this->validationMessages;
    }

    /**
     * @param string $separator
     * @return string
     */
    public function getValidationMessagesString(string $separator = PHP_EOL): string
    {
        return implode($separator, $this->validationMessages);
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
     * @throws InvalidJsonPointerSyntaxException
     * @throws TypeErrorException
     * @throws UnresolvableReferenceException
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
     * @param LoggerInterface|null $logger
     * @return OpenApiSlim4ConfigurationInterface
     */
    public function setLogger(?LoggerInterface $logger): OpenApiSlim4ConfigurationInterface
    {
        $this->logger = $logger ?? null;

        return $this;
    }

    /**
     * Set the source of the OpenApi Configuration Definition
     *
     * @param string|OpenApi|null $openApi
     * @return OpenApiSlim4ConfigurationInterface
     */
    public function setOpenApi(string|OpenApi|null $openApi): OpenApiSlim4ConfigurationInterface
    {
        $this->openApi = $openApi ?? null;

        return $this;
    }

    /**
     * @param App|null $SlimApplication
     * @return OpenApiSlim4ConfigurationInterface
     */
    public function setSlimApplication(?App $SlimApplication): OpenApiSlim4ConfigurationInterface
    {
        $this->SlimApplication = $SlimApplication ?? null;

        return $this;
    }

    /**
     * Set a switch which determines if an exception should be thrown when the validation is unsuccessful
     *
     * @param bool|null $throwValidationException
     * @return OpenApiSlim4ConfigurationInterface
     */
    public function setThrowValidationException(?bool $throwValidationException): OpenApiSlim4ConfigurationInterface
    {
        $this->throwValidationException = $throwValidationException ?? false;

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
        if (is_null($this->SlimApplication)) {
            $this->validationMessages[] = 'Slim Application is not defined';
            $returnValue = false;
        }
        if ($returnValue && is_null($this->openApi)) {
            $returnValue = false;
            $this->validationMessages[] = 'Openapi object is not defined';
        }

        return $returnValue;
    }

    /**
     * Validate the following aspects of the openapi definition:
     *   1) Is the input file (json|yaml) readable
     *   2) Can an Object of type Reader be created
     *   3) Is the Openapi definition valid
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
            $this->validationMessages[] = 'Exception resolving OpenApi Definition: ' . $exception->getMessage();
        }
        if ($returnValue && !$this->openApi instanceof OpenApi) {
            $returnValue = false;
            $this->validationMessages[] = 'OpenApiDefinition must be of type: ' . Reader::class;
        }
        if ($returnValue && !$this->openApi->validate()) {
            $returnValue = false;
            $this->validationMessages[] = 'Validation Error in the Openapi Definition: ' . implode(PHP_EOL, $this->openApi->getErrors());
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
            $this->validationMessages[] = 'Slim Version 4 is required';
            $returnValue = false;
        }

        return $returnValue;
    }
}