<?php

namespace App\Http\Api\v1\Users;

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
     * @param string $type
     *      the domain record type.
     * @param Request $request
     *      the inbound request.
     * @return void
     * @throws AuthenticationException|AuthorizationException
     *      if the request is not authorized.
     */
    public function index($type, $request)
    {
        $this->can('search users', $type);
    }

    /**
     * Authorize a resource create request.
     *
     * @param string $type
     *      the domain record type.
     * @param Request $request
     *      the inbound request.
     * @return void
     * @throws AuthenticationException|AuthorizationException
     *      if the request is not authorized.
     */
    public function create($type, $request)
    {
    }

    /**
     * Authorize a resource read request.
     *
     * @param object $record
     *      the domain record.
     * @param Request $request
     *      the inbound request.
     * @return void
     * @throws AuthenticationException|AuthorizationException
     *      if the request is not authorized.
     */
    public function read($record, $request)
    {
        $relation = last($request->segments());

        switch ($relation) {
            case 'roles':
                $this->can('search roles', $record);
                break;
            default:
                $this->can('read users', $record);
        }
    }

    /**
     * Authorize a resource update request.
     *
     * @param object $record
     *      the domain record.
     * @param Request $request
     *      the inbound request.
     * @return void
     * @throws AuthenticationException|AuthorizationException
     *      if the request is not authorized.
     */
    public function update($record, $request)
    {
        $relation = last($request->segments());

        switch ($relation) {
            case 'roles':
                if ($request->method() === 'POST') {
                    $this->can('create roles', $record);
                } elseif ($request->method() === 'DELETE') {
                    $this->can('delete roles', $record);
                }

                break;
            case 'company':
                if ($request->method() === 'PATCH') {
                    $this->can('assign companies', $request);
                }

                break;
            default:
                $this->can('update users', $record);
        }
    }

    /**
     * Authorize a resource read request.
     *
     * @param object $record
     *      the domain record.
     * @param Request $request
     *      the inbound request.
     * @return void
     * @throws AuthenticationException|AuthorizationException
     *      if the request is not authorized.
     */
    public function delete($record, $request)
    {
        $this->can('delete users', $record);
    }
}
