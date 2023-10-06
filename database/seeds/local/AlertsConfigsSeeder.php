<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class AlertsConfigsSeeder extends Seeder
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
            $now = \Carbon\Carbon::now()->format('Y-m-d H:i:s');
            $company1 = 1;
            $company2 = 2;
            $company3 = 3;
            $faker = Faker\Factory::create();
            DB::table('alert_configs')->insert(array (
                0 =>
                    array (
                        'company_id' => $company1,
                        'service_id' => $faker->numberBetween(1000,5000),
                        'error_alert_threshold' => $faker->numberBetween(0, 999),
                        'warning_alert_threshold' => $faker->numberBetween(0, 999),
                        'frequency_alert_threshold' => $faker->numberBetween(0, 999999),
                        'alert_frequency' => $faker->randomElement(['off', '0 * * * *', '0 0 * * *']),
                        'throttle_value' => $faker->numberBetween(1,3),
                        'alert_status' => $faker->numberBetween(0,1)
                    ),
                1 =>
                    array (
                        'company_id' => $company1,
                        'service_id' => $faker->numberBetween(1000,5000),
                        'error_alert_threshold' => $faker->numberBetween(0, 999),
                        'warning_alert_threshold' => $faker->numberBetween(0, 999),
                        'frequency_alert_threshold' => $faker->numberBetween(0, 999999),
                        'alert_frequency' => $faker->randomElement(['off', '0 * * * *', '0 0 * * *']),
                        'throttle_value' => $faker->numberBetween(1,3),
                        'alert_status' => $faker->numberBetween(0,1)
                    ),
                2 =>
                    array (
                        'company_id' => $company2,
                        'service_id' => $faker->numberBetween(1000,5000),
                        'error_alert_threshold' => $faker->numberBetween(0, 999),
                        'warning_alert_threshold' => $faker->numberBetween(0, 999),
                        'frequency_alert_threshold' => $faker->numberBetween(0, 999999),
                        'alert_frequency' => $faker->randomElement(['off', '0 * * * *', '0 0 * * *']),
                        'throttle_value' => $faker->numberBetween(1,3),
                        'alert_status' => $faker->numberBetween(0,1)
                    ),
                3 =>
                    array (
                        'company_id' => $company2,
                        'service_id' => $faker->numberBetween(1000,5000),
                        'error_alert_threshold' => $faker->numberBetween(0, 999),
                        'warning_alert_threshold' => $faker->numberBetween(0, 999),
                        'frequency_alert_threshold' => $faker->numberBetween(0, 999999),
                        'alert_frequency' => $faker->randomElement(['off', '0 * * * *', '0 0 * * *']),
                        'throttle_value' => $faker->numberBetween(1,3),
                        'alert_status' => $faker->numberBetween(0,1)
                    ),
                4 =>
                    array (
                        'company_id' => $company3,
                        'service_id' => $faker->numberBetween(1000,5000),
                        'error_alert_threshold' => $faker->numberBetween(0, 999),
                        'warning_alert_threshold' => $faker->numberBetween(0, 999),
                        'frequency_alert_threshold' => $faker->numberBetween(0, 999999),
                        'alert_frequency' => $faker->randomElement(['off', '0 * * * *', '0 0 * * *']),
                        'throttle_value' => $faker->numberBetween(1,3),
                        'alert_status' => $faker->numberBetween(0,1)
                    ),
            ));
        }
    }
}
