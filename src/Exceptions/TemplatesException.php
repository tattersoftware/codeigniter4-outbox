<?php

namespace Tatter\Outbox\Exceptions;

use RuntimeException;

class TemplatesException extends RuntimeException
{
    public static function forMissingTemplate(string $name)
    {
        return new static(lang('Templates.missingTemplate', [$name]));
    }
}
