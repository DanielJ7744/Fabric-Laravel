<?php

namespace App\Http\Api\v1\Companies;

use CloudCreativity\LaravelJsonApi\Auth\AbstractAuthorizer;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

class Authorizer extends AbstractAuthorizer
{
    protected $guards = ['api'];

    /**
     * Authorize a resource index request.
     *
     * @param string $type the domain record type.
     * @param Request $request the inbound request.
     * @return void
     * @throws AuthenticationException|AuthorizationException if the request is not authorized.
     */
    public function index($type, $request)
    {
        $this->can('search companies', $type);
    }

    /**
     * Authorize a resource create request.
     *
     * @param string $type the domain record type.
     * @param Request $request the inbound request.
     * @return void
     * @throws AuthenticationException|AuthorizationException if the request is not authorized.
     */
    public function create($type, $request)
    {
    }

    /**
     * Authorize a resource read request.
     *
     * @param object $record the domain record.
     * @param Request $request the inbound request.
     * @return void
     * @throws AuthenticationException|AuthorizationException if the request is not authorized.
     */
    public function read($record, $request)
    {
        $this->can('read companies', $record);
    }

    /**
     * Authorize a resource update request.
     *
     * @param object $record the domain record.
     * @param Request $request the inbound request.
     * @return void
     * @throws AuthenticationException|AuthorizationException if the request is not authorized.
     */
    public function update($record, $request)
    {
        $this->can('update companies', $record);
    }

    /**
     * Authorize a resource read request.
     *
     * @param object $record the domain record.
     * @param Request $request the inbound request.
     * @return void
     * @throws AuthenticationException|AuthorizationException if the request is not authorized.
     */
    public function delete($record, $request)
    {
        $this->can('delete companies', $record);
    }
}
