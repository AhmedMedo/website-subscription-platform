<?php

namespace App\Libraries\Base\Database;

use Closure;

interface ConnectionService
{
    public function disableLog(): void;

    /**
     * @deprecated use transaction()
     */
    public function beginTransaction(): void;

    /**
     * @deprecated use transaction()
     */
    public function commit(): void;

    /**
     * @deprecated use transaction()
     */
    public function rollBack(): void;

    /**
     * @param Closure $callback
     * @param int $attempts
     */
    public function transaction(Closure $callback, int $attempts = 1): void;
}
