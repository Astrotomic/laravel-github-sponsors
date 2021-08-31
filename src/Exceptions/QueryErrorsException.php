<?php

namespace Astrotomic\GithubSponsors\Exceptions;

use Throwable;

class QueryErrorsException extends RuntimeException
{
    protected array $errors;

    public function __construct(array $errors, string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->errors = $errors;
    }

    public static function fromErrors(array $errors): self
    {
        return new static(
            $errors,
            $errors[0]['message'] ?? ''
        );
    }
}