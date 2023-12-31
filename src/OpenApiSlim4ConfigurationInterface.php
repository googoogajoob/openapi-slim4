<?php
declare(strict_types=1);

namespace OpenApiSlim4;

/**
 * This interface is intended for classes which can use an openapi definition to configure a Slim4 Application.
 * The purpose of such classes is to create an instance of a Slim4 Application which accepts Rest Requests which are,
 * in accordance with the RestApi definition from an openapi definition.
 */
interface OpenApiSlim4ConfigurationInterface
{
    /**
     * Use the provided information from an openapi definition and a Slim4 Application Object to
     * configure an instance of the Slim4 Application, which is in accordance with the openapi definition
     *
     * @return bool true if the configuration is successful, false otherwise
     */
    public function configureFramework(): bool;
}