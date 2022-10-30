<?php
declare(strict_types=1);

namespace OpenapiSlim4;

use Slim\App;
use TestServer\TestService;

class MockConfig
{
    public function configureSlimFramework(App $slimApp): bool
    {
        $slimApp->get('/foo', TestService::class);

        return true;
    }
}
