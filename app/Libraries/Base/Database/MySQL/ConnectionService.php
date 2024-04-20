<?php

namespace App\Libraries\Base\Database\MySQL;

use App\Libraries\Base\Database;
use Closure;
use Exception;
use Illuminate\Support\Facades\DB;

class ConnectionService implements Database\ConnectionService
{
    /**
     * @inheritDoc
     */
    public function transaction(Closure $callback, int $attempts = 1): void
    {
        for ($i = 0; $i < $attempts; $i++) {
            try {
                $this->beginTransaction();
                $callback();
                $this->commit();

                return;
            } catch (Exception $exception) {
                $this->rollBack();

                continue;
            }
        }

        if (true === isset($exception)) {
            throw $exception;
        }
    }

    public function disableLog(): void
    {
        DB::disableQueryLog();
        DB::unsetEventDispatcher();
        DB::connection('mysql')->disableQueryLog();
        DB::connection('mysql')->unsetEventDispatcher();
    }

    public function beginTransaction(): void
    {
        DB::connection('mysql')->beginTransaction();
    }

    public function commit(): void
    {
        DB::connection('mysql')->commit();
    }

    public function rollBack(): void
    {
        DB::connection('mysql')->rollBack();
    }
}
