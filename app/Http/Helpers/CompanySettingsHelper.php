<?php

namespace App\Http\Helpers;

use App\Models\Tapestry\User as TapestryUser;
use Exception;
use Illuminate\Database\Connection;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class CompanySettingsHelper
{
    /**
     * The database connection instance for Tapestry.
     *
     * @var Connection
     */
    public Connection $tapestryConnection;

    /**
     * Attempt to create the IDX Table inside the Tapestry Database
     *
     * @param string $username
     * @param string $server
     *
     * @return bool
     * @throws Throwable
     */
    public function createTapestryIdxTable(string $username, string $server): bool
    {
        if (app()->runningUnitTests()) {
            return true;
        }

        $this->setTapestryConnection();

        $idxName = $this->generateIdxName($username);
        $tapestryTables = $this->getTapestryTables();

        throw_if($tapestryTables->contains($idxName), Exception::class, 'IDX Table already exists');

        $existingIdxTable = $tapestryTables->first(fn ($table) => Str::startsWith($table, 'idx_'));

        if (is_null($existingIdxTable)) {
            return false;
        }

        $statement = sprintf('CREATE TABLE %s LIKE %s', $idxName, $existingIdxTable);

        return $this->createIdxTable($statement) && $this->createTapestryUserRecord($idxName, $server);
    }

    /**
     * Set the Tapestry database connection
     *
     * @return void
     */
    public function setTapestryConnection(): void
    {
        $this->tapestryConnection = (new TapestryUser)->getConnection();
    }

    /**
     * Get all tapestry tables from our connection
     *
     * @param string $insertStatement
     *
     * @return bool
     */
    public function createIdxTable(string $insertStatement): bool
    {
        if (app()->runningUnitTests()) {
            return true;
        }

        return $this->tapestryConnection->statement($insertStatement);
    }

    /**
     * Get all tapestry tables from our connection
     *
     * @param ConnectionInterface $tapestryConnection
     *
     * @return Collection
     */
    private function getTapestryTables(): Collection
    {
        return collect($this->tapestryConnection->getDoctrineSchemaManager()->listTableNames());
    }

    /**
     * Create the user record within Tapestry
     *
     * @param string $idxName
     * @param string $server
     *
     * @return bool
     */
    protected function createTapestryUserRecord(string $idxName, string $server): bool
    {
        $username = explode('idx_', $idxName)[1];

        if (TapestryUser::where('username', $username)->where('server', $server)->exists()) {
            return true;
        }

        try {
            $user = TapestryUser::create([
                'username' => $username,
                'server' => $server,
                'email' => sprintf('%s@patchworks.com', $username),
                'password' => bin2hex(random_bytes(50)),
            ]);

            return $user->exists ?? false;
        } catch (Exception $exception) {
            Log::error(sprintf('Failed creating user: %s', $exception->getMessage()));

            return false;
        }
    }

    /**
     * Generate the IDX table name
     *
     * @param string $username
     *
     * @return string
     */
    protected function generateIdxName(string $username): string
    {
        return sprintf('idx_%s', $username);
    }
}
