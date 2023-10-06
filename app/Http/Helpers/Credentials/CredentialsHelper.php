<?php

namespace App\Http\Helpers\Credentials;

use App\Models\Fabric\Integration;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class CredentialsHelper
{
    /**
     * Fetch any existing credentials and use those to populate missing data as front-end is obfuscated
     *
     * @param array $credentials
     * @param int $credentialId
     * @param int $integrationId
     *
     * @return array
     */
    public function fetchExistingCredentials(array $credentials, int $credentialId, int $integrationId): array
    {
        $tapestryConnection = $this->setUpTapestryConnection($integrationId);
        if (is_null($tapestryConnection)) {
            return $credentials;
        }

        $matchingCredentialsRecord = $tapestryConnection->where('id', $credentialId)->first();
        $existingCredentials = json_decode($matchingCredentialsRecord->extra, true);
        foreach ($credentials as $key => $value) {
            // If key doesn't exist then add but if it does, overwrite
            // This is then validated later
            $existingCredentials[$key] = $value;
        }

        return $existingCredentials;
    }

    /**
     * Set up the tapestry connection
     *
     * @param int $integrationId
     *
     * @return Builder|null
     */
    private function setUpTapestryConnection(int $integrationId): ?Builder
    {
        $integration = Integration::firstWhere('id', $integrationId);

        return DB::connection('mysql_tapestry')->table(sprintf('idx_%s', $integration->username));
    }
}
