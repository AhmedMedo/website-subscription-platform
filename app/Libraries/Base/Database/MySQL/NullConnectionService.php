<?php

declare(strict_types=1);

namespace App\Libraries\Base\Database\MySQL;

use Closure;
use App\Libraries\Base\Database\ConnectionService;

class NullConnectionService implements ConnectionService
{
    public function disableLog(): void
    {
    }

    public function beginTransaction(): void
    {
    }

    public function commit(): void
    {
    }

    public function rollBack(): void
    {
    }

    public function transaction(Closure $callback, int $attempts = 1): void
    {
        $callback();
    }
}
