<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * This is a temporary table until we implement a full billing solution.
         *
         * Please do not modify or rely on this table existing or being in its current state.
         */
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->boolean('default')->default(false);
            $table->boolean('upgrade');
            $table->unsignedInteger('services')->default(0);
            $table->unsignedBigInteger('transactions')->nullable();
            $table->boolean('business_insights')->default(false);
            $table->boolean('api_key')->default(false);
            $table->boolean('sftp')->default(false);
            $table->unsignedInteger('users')->default(0);
            $table->unsignedInteger('price')->nullable();
            $table->string('sku');
            $table->timestamps();
            $table->softDeletes();

            $table->unique('sku');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscriptions');
    }
}
