<?php

namespace Tests;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Laravel\Passport\Passport;
use PermissionSeeder;

abstract class LaravelTestCase extends BaseTestCase
{
    use CreatesApplication, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->migrateTapestryTables();

        $this->seed(PermissionSeeder::class);

        $this->artisan('passport:install');
    }

    /**
     * Set the currently logged in user with Passport.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  string|null  $abilities
     * @return $this
     */
    public function passportAs(Authenticatable $user, $abilities = ['access-api'])
    {
        Passport::actingAs($user, $abilities);

        return $this;
    }

    public function migrateTapestryTables()
    {
        Schema::connection('sqlite')->create('tapestry_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('server', 60);
            $table->string('username', 255);
            $table->string('email');
            $table->string('password', 100);
            $table->timestamps();
            $table->unique('username');
        });

        Schema::create('idx_table', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type', 40);
            $table->string('system_chain', 80);
            $table->string('common_ref', 200);
            $table->string('source_id', 150)->nullable();
            $table->string('source_parent_id', 150)->nullable();
            $table->string('endpoint_id', 150)->nullable();
            $table->string('endpoint_parent_id', 150)->nullable();
            $table->string('status', 50)->nullable();
            $table->integer('attempt_count', false, true)->default(0);
            $table->text('message')->nullable();
            $table->string('report_id', 255)->nullable();
            $table->text('report_data')->nullable();
            $table->text('extra')->nullable();
            $table->text('options')->nullable();
            $table->text('mapping')->nullable();
            $table->string('environment', 60)->nullable();
            $table->timestamps();
            $table->dateTime('reported_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });

        Schema::connection('sqlite')->create('service', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('status')->default(1);
            $table->string('description', 255)->nullable();
            $table->string('from_factory', 80);
            $table->string('from_environment', 25);
            $table->string('to_factory', 80);
            $table->string('to_environment', 25);
            $table->string('username', 255);
            $table->string('schedule', 50)->default('off');
            $table->string('timeout', 30)->nullable();
            $table->text('from_options')->nullable();
            $table->text('from_mapping')->nullable();
            $table->text('to_options')->nullable();
            $table->text('to_mapping')->nullable();
            $table->string('idle_timeout', 30)->nullable();
            $table->integer('run_count')->default(0);
            $table->integer('run_id')->nullable();
            $table->integer('dashboard_visibility')->default(0);
            $table->boolean('filterable')->default(0);
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
            $table->index('from_factory');
            $table->index('to_factory');
            $table->index('username');
            $table->boolean('billable');
        });

        Schema::connection('sqlite')->create('servicelog', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('service_id');
            $table->string('from_factory', 80);
            $table->string('from_environment', 25);
            $table->string('to_factory', 80);
            $table->string('to_environment', 25);
            $table->string('username', 255);
            $table->string('requested_by', 80);
            $table->string('status', 50);
            $table->text('notes');
            $table->integer('runtime');
            $table->integer('current_page');
            $table->integer('total_pages');
            $table->integer('total_count');
            $table->integer('page_size');
            $table->integer('last_page_time');
            $table->integer('error');
            $table->integer('warning');
            $table->integer('other');
            $table->text('filters');
            $table->dateTime('due_at');
            $table->dateTime('queued_at');
            $table->dateTime('started_at');
            $table->dateTime('finished_at');
            $table->dateTime('reported_at');
            $table->integer('process_id');
            $table->unsignedBigInteger('total_pull_data_size');
        });
    }
}
