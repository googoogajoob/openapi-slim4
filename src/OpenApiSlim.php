<?php
declare(strict_types=1);

namespace OpenApiSlim;

use Slim\App;

class OpenApiSlim implements OpenApiSlimInterface
{
    protected $openApiDefinition;

    public function __construct($openApiDefinition, App $slimApp)
    {
        $this->openApiPaths = $openApiDefinition;
    }

    public function configureSlim(): bool
    {
        return false;
    }

    public function validate(): bool
    {
        $returnValue = true;

        foreach ($this->openApiDefinition as $path => $pathConfiguration) {
            if (!$pathConfiguration->validate()) {
                $returnValue = false;
                break;
            }
        }

        return $returnValue;
    }
}