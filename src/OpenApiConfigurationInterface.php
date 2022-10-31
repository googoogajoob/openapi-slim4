<?php
declare(strict_types=1);

namespace OpenApiSlim4;

/**
 * This interface is intended for classes which can use an openapi definition to configure a Slim Application.
 * The purpose of such classes is to create an instance of a Slim Application which accepts Rest Requests
 * in accordance with the RestApi definition from an openapi.
 *
 * How the necessary information and variables are instantiated is up to the developer
 */
interface OpenApiConfigurationInterface
{
    /**
     * Use information provided by an OpenApi Object and a Slim Application Object to
     * configure an instance of the Slim Application, which is in
     * accordance with the OpenApi definition
     *
     * @return bool true if the configuration is successful, false otherwise
     */
    public function configureSlimFramework(): bool;
}