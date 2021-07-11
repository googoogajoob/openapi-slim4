<?php
declare(strict_types=1);

namespace OpenApiSlim;

use Slim\App;
use Psr\Log\LoggerInterface;

/**
 * This interface is intended for classes which can use an openapi definition to configure a Slim Application.
 * The purpose of such classes is to configure a Slim Application to respond to Rest Requests in accordance
 * with the RestApi in an openapi definition.
 *
 * To accomplish this, three interface methods have been defined.
 * See the method comments below to understand what each interface method should perform.
 */
interface OpenApiSlimInterface
{
    /**
     * OpenApiSlimInterface constructor.
     *
     * @param mixed $openApiDefinition a php variable from which the implementing class
     * can determine the required path information for each RestApi request of an openapi definition.
     *
     * The minimum information requirement is:
     * - The RestApi Path definition as defined in openapi
     * - The Http Method used for the RestApi request
     * - A class definition of the Handler for the RestApi request
     *
     * Additional Information could also be provided or made accessible, for example:
     * - Class definitions for a Path Middleware Stack
     * - Class definitions for a Global Middleware Stack
     * - ...
     *
     * @param App $slimApp an instance of a Slim Application Class
     *
     * @param LoggerInterface $logger
     */
    public function __construct($openApiDefinition, App $slimApp, LoggerInterface $logger);

    /**
     * Using the information provided in the constructor parameter $openApiDefinition
     * to configure an instance of $slimApp (also provided in the constructor)
     *
     * @return bool true if the configuration is successful, false otherwise
     */
    public function configureSlim(): bool;

    /**
     * Validate that the information provided in the constructor is sufficient to achieve the task of configuring a slim application
     *
     * @return bool true if the information is adequate, false otherwise
     */
    public function validate(): bool;
}