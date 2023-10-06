<?php

use App\Models\Fabric\System;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class UpdateStatusColumnInSystems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $allSystems = System::all();
        Schema::table('systems', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        Schema::table('systems', function (Blueprint $table) {
            $table->string('status', 30)->default('inactive')->after('description');
        });
        foreach ($allSystems as $system) {
            $systemModel = System::find($system->id);
            $systemModel->status = $system->status;
            $systemModel->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('systems', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        Schema::table('systems', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive', 'development'])->default('inactive')->after('description');
        });
    }
}
