<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDigitalOceanDropletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('digitalocean_droplets', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->string('name', 128);
            $table->unsignedInteger('memory');
            $table->unsignedInteger('vcpus');
            $table->unsignedInteger('disk');
            $table->string('status', 64);
            $table->string('region', 64);
            $table->string('image', 128);
            $table->string('size', 128);
            $table->string('tags');
            $table->string('public_ip')->nullable();
            $table->string('private_ip')->nullable();
            $table->timestamps();

            $table->primary('id');
            $table->unique('name');
            $table->index(['size', 'image']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('digitalocean_droplets');
    }
}
