<?php

namespace App\Http\Helpers;

use Carbon\Carbon;

class ApiHelper
{
    public static function buildTapestryApiPath(
        string $serverName,
        string $endpoint,
        string $tapestryRoute = 'tapestry.routes.minidash'
    ): string {
        $overrideTapestryUrls = config('tapestry.override_server_urls');
        $localTapestryUrl = config('tapestry.local_url');

        return sprintf(
            '%s://%s/%s/%s',
            config('tapestry.protocol'),
            $overrideTapestryUrls ? $localTapestryUrl : $serverName,
            config($tapestryRoute),
            $endpoint
        );
    }

    /**
     * Builds the query to be sent to Tapestry
     *
     * @param array $filters
     *
     * @return string
     */
    public static function buildTapestryQuery(array $filters): string
    {
        $query = [];
        foreach ($filters as $filter => $value) {
            if (is_null($value)) {
                continue;
            }

            if ($filter === 'created_at' || $filter === 'updated_at' || $filter === 'finished_at') {
                $query[$filter] = sprintf('>|%s', Carbon::now()->subDays($value));

                continue;
            }

            if ($filter === 'updated_at_start') {
                $query[$filter] = sprintf('>|%s', $value);

                continue;
            }

            if ($filter === 'updated_at_end') {
                $query[$filter] = sprintf('<|%s', $value);

                continue;
            }

            $query[$filter] = $value;
        }

        return http_build_query($query);
    }
}
