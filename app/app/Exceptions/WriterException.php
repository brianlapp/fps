<?php

namespace App\Exceptions;

use Throwable;

class WriterException extends \Exception
{
    private mixed $response;

    public function __construct(string $message = "", mixed $response = null, int $code = 500, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->response = $response;
    }

    public function getResponse(): mixed
    {
        return $this->response;
    }
}
