<?php

namespace Qu1eeeOJ\Deploy\Repositories;

use Illuminate\Support\Facades\DB;
use Qu1eeeOJ\Deploy\Contracts\DeployerConfigContract;
use Qu1eeeOJ\Deploy\Contracts\DeployerInstructionsRepositoryContract;

readonly class DeployerInstructionsRepository implements DeployerInstructionsRepositoryContract
{
    /**
     * @var string
     */
    const INSTRUCTION_FIELD = 'instruction';

    /**
     * DeployerInstructionsRepository constructor.
     */
    public function __construct(protected DeployerConfigContract $config) {}

    /**
     * @return array
     */
    public function getRan(): array
    {
        return DB::table($this->config->getDatabaseTableName())
            ->select(static::INSTRUCTION_FIELD)
            ->get()
            ->pluck(static::INSTRUCTION_FIELD)
            ->toArray();
    }

    /**
     * @param string $file
     * @return void
     */
    public function setRan(string $file): void
    {
        DB::table($this->config->getDatabaseTableName())->insert([
            static::INSTRUCTION_FIELD => $file
        ]);
    }
}
