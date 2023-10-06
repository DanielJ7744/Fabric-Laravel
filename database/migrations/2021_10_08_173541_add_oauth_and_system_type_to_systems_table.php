<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOauthAndSystemTypeToSystemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('systems', function (Blueprint $table) {
            $table->unsignedSmallInteger('system_type_id')->after('id')->nullable();
            $table->foreign('system_type_id')->references('id')->on('system_types');
            $table->string('slug')->after('name')->nullable();
            $table->boolean('oauth')->after('credentials_schema')->default(0);
            $table->boolean('popular')->after('oauth')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('systems', function (Blueprint $table) {
            $table->dropForeign('systems_system_type_id_foreign');
            $table->dropColumn('system_type_id');
            $table->dropColumn('slug');
            $table->dropColumn('oauth');
            $table->dropColumn('popular');
        });
    }
}
