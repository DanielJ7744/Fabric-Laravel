<?php

use App\Models\Fabric\FilterType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateTimeFilterTypeKeys extends Migration
{
    private const TAPESTRY_TIME_TYPES = [
        'time',
        'timestamp',
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach (self::TAPESTRY_TIME_TYPES as $key) {
            DB::table('filter_types')->where(['key' => $key])->update(['key' =>  mb_strtoupper($key)]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach (self::TAPESTRY_TIME_TYPES as $key) {
            DB::table('filter_types')->where(['key' =>  mb_strtoupper($key)])->update(['key' =>  mb_strtolower($key)]);
        }
    }
}
