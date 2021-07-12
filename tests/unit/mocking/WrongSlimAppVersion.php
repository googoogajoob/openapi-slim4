<?php
/**
 * Project openapi-slim4
 * Created by PhpStorm.
 * User: and
 * Date: 11.07.21
 * Time: 17:07
 */

namespace OpenApiSlimTests\unit\mocking;

use Slim\App;

class WrongSlimAppVersion extends App
{
    public const VERSION = '3.8.1';
}