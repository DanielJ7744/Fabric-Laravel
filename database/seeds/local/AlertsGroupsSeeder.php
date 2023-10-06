<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use App\Models\Alerting\AlertGroups;

class AlertsGroupsSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //seeders for local development only
        if (App::Environment() === 'local' && !AlertGroups::count()) {
            $now = \Carbon\Carbon::now()->format('Y-m-d H:i:s');
            $faker = Faker\Factory::create();
            DB::table('alert_groups')->insert(array (
                0 =>
                    array (
                        'id' => 1,
                        'company_id' => 1,
                        'name' => 'dev-system',
                        'created_at' => $now,
                        'updated_at' => $now,
                    ),
                1 =>
                    array (
                        'id' => 2,
                        'company_id' => 1,
                        'name' => 'devops',
                        'created_at' => $now,
                        'updated_at' => $now,
                    ),
                2 =>
                    array (
                        'id' => 3,
                        'company_id' => 2,
                        'name' => 'managements',
                        'created_at' => $now,
                        'updated_at' => $now,
                    ),
                3 =>
                    array (
                        'id' => 4,
                        'company_id' => 2,
                        'name' => 'team',
                        'created_at' => $now,
                        'updated_at' => $now,
                    ),
                4 =>
                    array (
                        'id' => 5,
                        'company_id' => 1,
                        'name' => 'team',
                        'created_at' => $now,
                        'updated_at' => $now,
                    ),
            ));
        }
    }
}
