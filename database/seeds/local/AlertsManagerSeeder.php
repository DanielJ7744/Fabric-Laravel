<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class AlertsManagerSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //seeders for local development only
        if (App::Environment() === 'local') {
            $now = \Carbon\Carbon::now();
            $company1 = 1;
            $company2 = 2;
            $configId1 = 2;
            $configId2 = 4;
            $faker = Faker\Factory::create();
            $firstRun = $now->addHours(1)->format('Y-m-d H:i:s');
            $secondRun = $now->addHours(2)->format('Y-m-d H:i:s');

            DB::table('alert_manager')->insert(array (
                0 =>
                    array (
                        'company_id' => $company1,
                        'service_id' => $faker->numberBetween(1000,5000),
                        'recipient_id' => $faker->numberBetween(1, 10),
                        'config_id' => $configId1,
                        'alert_type' => $faker->randomElement(['info', 'warning', 'error']),
                        'send_from' => $firstRun,
                        'dispatched_at' => NULL
                    ),
                1 =>
                    array (
                        'company_id' => $company1,
                        'service_id' => $faker->numberBetween(1000,5000),
                        'recipient_id' => $faker->numberBetween(1, 10),
                        'config_id' => $configId1,
                        'alert_type' => $faker->randomElement(['info', 'warning', 'error']),
                        'send_from' => $secondRun,
                        'dispatched_at' => NULL
                    ),
                2 =>
                    array (
                        'company_id' => $company2,
                        'service_id' => $faker->numberBetween(1000,5000),
                        'recipient_id' => $faker->numberBetween(1, 10),
                        'config_id' => $configId2,
                        'alert_type' => $faker->randomElement(['info', 'warning', 'error']),
                        'send_from' => $firstRun,
                        'dispatched_at' => $now->subHour()
                    )
            ));
        }
    }
}
