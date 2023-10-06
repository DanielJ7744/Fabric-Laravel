<?php

namespace App\Adapters;

use App\Models\Fabric\Company;
use App\Models\Fabric\InboundEndpoint;
use Exception;
use GuzzleHttp\Client as Http;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use stdClass;

class HasuraAdapter
{
    /**
     * The id of Fabric within Hasura.
     *
     * @var integer
     */
    const FABRIC_ID = 1002;

    /**
     * The endpoint type used for Fabric within Hasura.
     *
     * @var integer
     */
    const ENDPOINT_TYPE = 2;

    /**
     * Instantiate a new Hasura Adapter instance.
     *
     * @param string $endpoint
     * @param string $username
     * @param string $password
     */
    public function __construct(string $endpoint, string $username, string $password)
    {
        $this->endpoint = $endpoint;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Retrieve transactions for the given usernames.
     *
     * @param iterable  $usernames
     * @param Carbon  $start
     * @param Carbon  $end
     * @param string  $format
     * @return Collection
     * @throws Exception
     */
    public function transactions(iterable $usernames, Carbon $start = null, Carbon $end = null, string $format = 'daily'): Collection
    {
        return $format === 'hourly'
            ? $this->transactionsByHour($usernames, $start, $end)
            : $this->transactionsByDay($usernames, $start, $end);
    }

    /**
     * Retrieve transactions by day.
     *
     * @param iterable  $usernames
     * @param Carbon  $start
     * @param Carbon  $end
     * @return Collection
     * @throws Exception
     */
    protected function transactionsByDay(iterable $usernames, Carbon $start, Carbon $end): Collection
    {
        $query = <<<'GRAPHQL'
            query transactionsByDay($usernames: [String!] = "", $start: date = "", $end: date = "") {
                pwservice_transaction_summary_daily(where: {_and: {username: {_in: $usernames}, _and: {date: {_gte: $start}, _and: {date: {_lte: $end}}}}}) {
                    service_id
                    pwservice_id {
                        description
                        billable
                        from_factory
                        to_factory
                    }
                    date
                    total_pull_data_size
                    total_transactions
                    username
                }
            }
        GRAPHQL;

        $response = $this->request($query,  [
            'usernames' => $usernames,
            'start' => $start->startOfDay(),
            'end' => $end->endOfDay()
        ]);

        return $this
            ->filterInvalidTransactions($response->data->pwservice_transaction_summary_daily, $usernames)
            ->map(fn (object $item) => (object) [
                'date'                 => $item->date,
                'username'             => $item->username,
                'service_id'           => $item->service_id,
                'billable'             => $item->pwservice_id->billable,
                'description'          => $item->pwservice_id->description,
                'from_factory'         => $item->pwservice_id->from_factory,
                'to_factory'           => $item->pwservice_id->to_factory,
                'total_pull_data_size' => $item->total_pull_data_size,
                'total_transactions'   => $item->total_transactions,
            ]);
    }

    /**
     * Retrieve transactions by hour.
     *
     * @param iterable  $usernames
     * @param Carbon  $start
     * @param Carbon  $end
     * @return Collection
     * @throws Exception
     */
    protected function transactionsByHour(iterable $usernames, Carbon $start, Carbon $end): Collection
    {
        $query = <<<'GRAPHQL'
           query transactionsByHour($usernames: [String!]!, $start: timestamp!, $end: timestamp!) {
                pwservice_transaction_summary_hourly(where: {_and: {_and: {_and: {username: {_in: $usernames}}, date: {_gte: $start}}, date: {_lte: $end}}}) {
                    pwservice_id {
                        description
                        billable
                    }
                    username
                    service_id
                    total_transactions
                    status
                    date
                }
            }
        GRAPHQL;

        $response = $this->request($query, [
            'usernames' => $usernames,
            'start' => $start->startOfDay(),
            'end' => $end->endOfDay()
        ]);

        return $this
            ->filterInvalidTransactions($response->data->pwservice_transaction_summary_hourly, $usernames)
            ->map(fn (object $item) => (object) [
                'date'               => $item->date,
                'status'             => $item->status,
                'username'           => $item->username,
                'service_id'         => $item->service_id,
                'billable'           => $item->pwservice_id->billable,
                'description'        => $item->pwservice_id->description,
                'total_transactions' => $item->total_transactions,
            ]);
    }

    /**
     * Filter out invalid transactions from Hasura.
     *
     * @param iterable $records
     * @param iterable $usernames
     * @return Collection
     */
    protected function filterInvalidTransactions(iterable $records, iterable $usernames): Collection
    {
        [$valid, $invalid] = collect($records)->partition(fn ($item) => !is_null($item->pwservice_id));

        if ($invalid->count()) {
            Log::error("Hasura returned invalid records", [
                'valid'     => $valid->count(),
                'invalid'   => $invalid->count(),
                'usernames' => $usernames,
            ]);
        }

        return $valid->values();
    }

    /**
     * Create an endpoint within Hasura for the provided company.
     *
     * @param Company  $company
     * @param string  $name
     * @param string  $description
     *
     * @return stdClass
     *
     * @throws Exception
     */
    public function createEndpoint(Company $company, string $name, string $description): stdClass
    {
        $query = <<<'GRAPHQL'
            mutation putAPIOrganizationEndpoint($organization_id: Int!, $name: citext!, $description: citext!, $endpoint_type_id: Int, $created_by: Int) {
                insert_pwendpoint_one(
                    object: {name: $name, description: $description, pwendpoint_type_id: $endpoint_type_id, created_by: $created_by, updated_by: $created_by, pworganization_id: $organization_id}
                    on_conflict: {constraint: pwendpoint_pworganization_id_name_key, update_columns: updated_by}
                ) {
                    id
                    name
                    description
                    pwendpoint_type {
                        id
                        name
                        description
                    }
                }
            }
        GRAPHQL;

        $response = $this->request($query, [
            'organization_id'  => $company->getKey(),
            'name'             => $name,
            'description'      => $description,
            'created_by'       => self::FABRIC_ID,
            'endpoint_type_id' => self::ENDPOINT_TYPE,
        ]);

        return (object) [
            'id'          => $response->data->insert_pwendpoint_one->id,
            'name'        => $response->data->insert_pwendpoint_one->name,
            'description' => $response->data->insert_pwendpoint_one->description,
        ];
    }

    /**
     * Create an endpoint within Hasura for the provided company.
     *
     * @param InboundEndpoint  $inboundEndpoint
     * @param array  $payload
     *
     * @return stdClass
     *
     * @throws Exception
     */
    public function storePayload(InboundEndpoint $inboundEndpoint, array $payload): stdClass
    {
        $query = <<<'GRAPHQL'
            mutation PostNewPayload($json: jsonb!, $service_id: Int!, $reference_id: String!, $organization_id: Int!, $endpoint_id: Int!, $callback_url: String, $created_by: Int! = 1000001, $updated_by: Int! = 1000001) {
                insert_pwpayload(
                objects: {json: $json, pwservice_id: $service_id, reference_id: $reference_id, pworganization_id: $organization_id, pwendpoint_id: $endpoint_id, callback_url: $callback_url, created_by: $created_by, updated_by: $updated_by}
            ) {
                returning {
                    id
                    pwendpoint_id
                    pworganization_id
                    json
                    pwservice_id
                    reference_id
                    pwendpoint {
                        id
                        name
                    }
                    callback_url
                    }
                }
            }
        GRAPHQL;

        $response = $this->request($query, [
            'json'             => $payload,
            'reference_id'     => Str::uuid(),
            'service_id'       => $inboundEndpoint->service_id,
            'endpoint_id'      => $inboundEndpoint->external_endpoint_id,
            'organization_id'  => $inboundEndpoint->integration->company_id,
        ]);

        return (object) [
            'id' => $response->data->insert_pwpayload->returning[0]->id,
        ];
    }

    /**
     * Make a request to Hasura.
     *
     * @param string $query
     * @param array $variables
     * @return stdClass
     * @throws Exception
     */
    public function request(string $query, array $variables = []): stdClass
    {
        try {
            $response = (new Http)->post($this->endpoint, [
                'headers' => [
                    "$this->username" => $this->password,
                    'Content-Type'    => 'application/json',
                ],
                'body' => json_encode([
                    'query'     => $query,
                    'variables' => $variables
                ]),
            ]);

            $response = json_decode($response->getBody());

            if (isset($response->errors)) {
                throw new Exception($response->errors[0]->message);
            }

            return $response;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
