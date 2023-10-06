# Patchworks Fabric

## Quick Start

Assuming you have already set up and run the project previously with the instructions below

- start the containers `docker-compose up -d`
- access the app / API at `http://localhost:6121`
- start a terminal within the container `fabricPHP` (or `docker exec -it fabric-app bash`)
- Update API documentation via the [Optic Diff UI](http://localhost:34444/apis/1/diffs).
- View current API docs locally with the [Optic Docs UI](http://localhost:34444/apis/1/documentation)
- Make API requests via Postman [Postman Workspace for Fabric](https://app.getpostman.com/join-team?invite_code=f0444361f4166d01f438c4e9207c1420&ws=20480f85-ad50-4f7d-88cc-38dbee5cf510)

## About Fabric

Fabric is the system responsible for Patchworks' central store of data, and will act as a gateway to various other Patchworks APIs.

Some of its roles include:

- keeps a record of Companies
- keeps a record of Integrations belonging to those companies
- stores Credentials used for those Integrations
- keeps a record of Users belonging to those companies. Users are able to log in to the Patchworks Dashboard to manage their Integrations
- controls which actions a user is allowed to do, via Roles and Permissions
- (in progress) keeps an Audit Log of actions taken by a User
- (not yet built) controls whether to pass an API request to a different Patchworks system, depending on whether the user is authenticated and has the relevant permissions
- provides various data to the Patchworks Dashboard
- contains endpoints used to automatically onboard new Customers and set up their Integrations, for certain supported systems
- stores OAuth access tokens retrieved from 3rd party providers (eg Clover, Lightspeed, Xero), and supports refreshing these tokens when they expire

Further information on Fabric is available on Confluence at https://patchworks.atlassian.net/l/c/AB6SLKeb

## Kubernetes Links

In K8s, this service base url is `svc-fabric-<env>.pwks.co`. This convention is ommitted for Production.

This means that by environment they are as follows:
- svc-fabric-dev.pwks.co
- svc-fabric-stage.pwks.co
- svc-fabric.pwks.co

## Running the project locally

Fabric can be run locally using Docker - ensure Docker is installed on your machine before doing the below steps

When running, Fabric can be accessed at `http://localhost:6121`
You should see a page which says 'fabric' if you visit this address in the browser. You will also be able to hit the API at this address, eg via Postman

### Starting Docker

#### Running for the first time

Run `docker-compose up -d --build`
This will build all the docker containers needed for the project to run. This includes

- Fabric app container
- Database (mysql)
- Redis
- 2x nginx containers (see API monitoring info)
- Optic (API monitoring) container

#### Subsequent runs

Run `docker-compose up -d` to start all of the containers as above

### Running PHP commands in the container

When running any command you would usually run in the terminal, such as `composer install` or `php artisan ...`, you will need to run these _inside_ the Docker container

The name of the PHP container is `fabric-app`
(you can see a list of containers and names by running `docker ps` in the terminal)

Run `docker exec -it fabric-app bash` to start a terminal within the container

You should see the text before your cursor change, to start with something like `fabric@abcxyz`. This shows that you are now inside the docker container.

If you run a command such as `php -v`, the results will reflect the fabric-app docker container.

You can now run any terminal commands as usual. Packages will be installed using the correct PHP version which is used in the container

As good practice, when you pull in code, you should do a `composer update` to ensure that your composer packages are up-to-date.

#### Creating an alias

To make it easier to run commands in the container in the future, you can create an alias - run the below in the terminal

`alias fabricPHP='docker exec -it fabric-app bash`

This will create an alias called `fabricPHP` which will launch a bash terminal inside the `fabric-app` PHP container.
Now follow the steps in 'subsequent runs' below

Note that this alias will only last for the current terminal session - add it to your `.bashrc` or `.zshrc` file to make it permanent

Enter `fabricPHP` in the terminal to use the alias you created earlier.

#### Running as Root

If you need to run commands inside the container as root, start the container terminal with `docker exec -it -u 0 fabric-app bash`

### Installing the project

If this is the first time you have run the project, follow the steps below. Make sure that you are running all commands in the Docker container, as per the instructions for 'Running PHP commands in the container'

- Install composer dependencies `composer install`
- Copy the `env.example` file and name it `env`
- Run `php artisan key:generate`
- Run `composer install`
- Run `php artisan migrate`
- Run `php artisan db:seed` - note that if the app environment is `local`, this will also create some Companies and Users, and assign them roles, for use in development
- Follow the steps below in 'Monitoring & Documenting the API'

### Monitoring & Documenting the API

A tool called Optic is used to watch the API during local development. It watches development traffic and automatically captures requests, which you can save to auto-generate the API specification document.

#### Documenting Requests

Continue development as normal (running with docker-compose), making requests to the API at the usual localhost address.

Any undocumented requests will appear in the UI, as will any responses not seen previously, or which are different from those already saved in the API spec.

View the documentation UI at [http://localhost:34444/apis/1/diffs](http://localhost:34444/apis/1/diffs).

** New endpoints **
Any requests which have not yet been added to the spec will appear as 'undocumented'.  
You can click any section of the URL to set it as a parameter - eg the request `api/v1/users/1` - you would select the last section and name it `userId`
You can then name this endpoint, and save it. Optic will automatically collect all response types (eg 200, 401, etc) - you can choose which you would like to add to the spec

** Existing endpoints **
If your call to an existing endpoint returns data in a different format to that seen previously, Optic will flag this as a diff. You can choose to update the endpoint docs with this new data, or ignore it (for example, a field that is sometimes null, and sometimes a string)

This tool will also flag changes to the API on every pull request.

More in-depth info can be found in the [Optic docs](https://useoptic.com/document/baseline)

#### Making Requests

You will need to trigger requests yourself, eg from the Dashboard UI, an API call from another app, or via Postman. All requests that hit Fabric's localhost address will be seen by Optic (unless specifically ignored)

There is a [Postman Workspace for Fabric](https://app.getpostman.com/join-team?invite_code=f0444361f4166d01f438c4e9207c1420&ws=20480f85-ad50-4f7d-88cc-38dbee5cf510) (you will need to join the Patchworks team)

This workspace contains a number of requests to get started with. Feel free to add more as needed.

#### Exporting an OpenAPI3 specification

Run `api generate:oas` in the terminal (outside of Docker)
Optionally, add flags to specify format (one or both) `--json` `--yaml`
The location of your generated file will be shown in the terminal. This file can be uploaded anywhere an OpenAPI specification is used

#### How it works

Optic runs as an additional container within Docker.

Requests are received by the nginx-optic container, which forwards them onto the Optic container. Optic displays the received requests in its UI so they can be documented.

Optic then forwards the request on to the nginx-app container, which handles them as usual. Forwarding is controlled by the optic.yml file

Optic is started automatically when `docker-compose up` is run

All Optic files, including the generated API Specification, are stored in the `/Optic` folder

# Multi-Tenant / V2 Routes

Any new models/routes added should use the V2 route examples as we're aiming to remove the deprecated JSON API package and migrate to using the Laravel framework going forward.

The v2 routes use [Spatie Multitenancy](https://spatie.be/docs/laravel-multitenancy/v2/introduction) to ensure that all models and database results are scoped by default, and that new resources are automatically applied to the correct company. This also helps with security, as it ensures that only the correct company can access the data.

To allow including relationships and filtering results for INDEX/SHOW methods, we are using [Spatie Query Builder](https://spatie.be/docs/laravel-query-builder/v5/introduction)

PUT/POST requests should have a corresponding Request class (App/Http/Requests/Api) which contains the validation rules for that request.

Authorization should be using a [Policy](https://laravel.com/docs/8.x/authorization#policies), which avoids needing to have a huge list of gates in the AuthServiceProvider.

Please use the Integration Model, Controller, Policy, Requests etc as an example of how to structure your code.
