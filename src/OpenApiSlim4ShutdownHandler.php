<?php

declare(strict_types=1);

namespace OpenApiSlim4;

use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\ServerRequestCreatorFactory;
use Slim\ResponseEmitter;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Handlers\ErrorHandler;

class OpenApiSlim4ShutdownHandler
{
    protected Request $request;

    protected ErrorHandler $errorHandler;

    protected bool $displayErrorDetails;

    public function __construct(
        ErrorHandler $errorHandler,
        bool $displayErrorDetails
    ) {
        $serverRequestCreator = ServerRequestCreatorFactory::create();
        $this->request = $serverRequestCreator->createServerRequestFromGlobals();
        $this->errorHandler = $errorHandler;
        $this->displayErrorDetails = $displayErrorDetails;
    }

    public function __invoke()
    {
        $error = error_get_last();
        if ($error) {
            $errorFile = $error['file'];
            $errorLine = $error['line'];
            $errorMessage = $error['message'];
            $errorType = $error['type'];
            $message = 'An error while processing your request. Please try again later.';

            if ($this->displayErrorDetails) {
                switch ($errorType) {
                    case E_USER_ERROR:
                        $message = "FATAL ERROR: {$errorMessage}. ";
                        $message .= " on line {$errorLine} in file {$errorFile}.";
                        break;

                    case E_USER_WARNING:
                        $message = "WARNING: {$errorMessage}";
                        break;

                    case E_USER_NOTICE:
                        $message = "NOTICE: {$errorMessage}";
                        break;

                    default:
                        $message = "ERROR: {$errorMessage}";
                        $message .= " on line {$errorLine} in file {$errorFile}.";
                        break;
                }
            }

            $exception = new HttpInternalServerErrorException($this->request, $message);
            $response = $this->errorHandler->__invoke(
                $this->request,
                $exception,
                $this->displayErrorDetails,
                false,
                false,
            );

            $responseEmitter = new ResponseEmitter();
            $responseEmitter->emit($response);
        }
    }
}