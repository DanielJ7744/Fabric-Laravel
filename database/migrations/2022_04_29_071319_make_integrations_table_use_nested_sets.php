<?php

use App\Models\Fabric\Integration;
use Kalnoy\Nestedset\NestedSet;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeIntegrationsTableUseNestedSets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('integrations', function (Blueprint $table) {
            $table->unsignedInteger('_lft')->nullable()->after('active');
            $table->unsignedInteger('_rgt')->nullable()->after('_lft');
            $table->unsignedInteger('parent_id')->nullable()->after('_rgt');
        });
        Integration::fixTree();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('integrations', function (Blueprint $table) {
            NestedSet::dropColumns($table);
        });
    }
}
