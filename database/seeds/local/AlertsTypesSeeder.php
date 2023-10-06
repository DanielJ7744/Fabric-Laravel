<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use App\Models\Alerting\AlertTypes;

class AlertsTypesSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //seeders for local development only
        if (App::Environment() === 'local' && !AlertTypes::count()) {
            $now = \Carbon\Carbon::now()->format('Y-m-d H:i:s');

            DB::table('alert_types')->insert(array (
                0 =>
                    array (
                        'id' => 1,
                        'name' => 'error',
                        'template' => 'This is a error message',
                        'created_at' => $now,
                        'updated_at' => $now,
                    ),
                1 =>
                    array (
                        'id' => 2,
                        'name' => 'warning',
                        'template' => 'This is a warning message',
                        'created_at' => $now,
                        'updated_at' => $now,
                    ),
                2 =>
                    array (
                        'id' => 3,
                        'name' => 'info',
                        'template' => 'This is a info message',
                        'created_at' => $now,
                        'updated_at' => $now,
                    ),
            ));
        }
    }
}
