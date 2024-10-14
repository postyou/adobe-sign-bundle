<?php

declare(strict_types=1);

/*
 * @author  POSTYOU Digital- & Filmagentur
 * @license MIT
 */

namespace Postyou\AdobeSignBundle\Client;

class AdobeErrorResponseException extends \Exception
{
    public function __construct(string $message = '', private readonly array $data = [], int $code = 0, ?\Throwable $previous = null)
    {
        foreach ($data as $k => $v) {
            $message .= " {$k}: {$v};";
        }

        parent::__construct($message, $code, $previous);
    }

    public function getData(): array
    {
        return $this->data;
    }
}
