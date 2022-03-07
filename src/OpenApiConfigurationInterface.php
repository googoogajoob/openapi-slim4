<?php
declare(strict_types=1);

namespace OpenApiSlim;

use Psr\Log\LoggerInterface;
use Slim\App;
use cebe\openapi\Reader;

/**
 * This interface is intended for classes which can use an openapi definition to configure a Slim Application.
 * The purpose of such classes is to configure a Slim Application to respond to Rest Requests in accordance
 * with the RestApi of an openapi definition.
 */
interface OpenApiConfigurationInterface
{
    /**
     * OpenApiSlimInterface constructor.
     *
     * @param Reader $openApiDefinition a php variable from which the implementing class
     * can determine the required path information for each RestApi request of an OpenApi definition.
     *
     * The minimum information requirement in the OpenApi definition is:
     * - The RestApi Path definition as defined in openapi
     * - The Http Method used for the RestApi request
     * - A class definition of the Handler for the RestApi request
     *
     * @param App $slimApp an instance of the Slim Application Class
     *
     * @param LoggerInterface $logger
     */
    public function __construct(Reader $openApiDefinition, App $slimApp, ?LoggerInterface $logger);

    /**
     * Using the information provided in the constructor parameter $openApiDefinition
     * to configure an instance of $slimApp (also provided in the constructor)
     *
     * @return bool true if the configuration is successful, false otherwise
     */
    public function configureFramework(): bool;
}