<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use App\Models\Alerting\AlertServiceRecipients;

class AlertsServiceRecipientSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //seeders for local development only
        if (App::Environment() === 'local' && !AlertServiceRecipients::count() ) {
            $faker = Faker\Factory::create();
            $serviceId1 = $faker->numberBetween(2000,2100);
            $serviceId2 = $faker->numberBetween(2100,2200);
            $serviceId3 = $faker->numberBetween(2200,2300);
            $serviceId4 = $faker->numberBetween(2300,2400);
            DB::table('alert_service_recipients')->insert(array (
                0 =>
                    array (
                        'service_id' => $serviceId4,
                        'recipient_id' => 1,
                        'group_id' => 1,
                    ),
                1 =>
                    array (
                        'service_id' => $serviceId1,
                        'recipient_id' => 2,
                        'group_id' => 6,
                    ),
                2 =>
                    array (
                        'service_id' => $serviceId1,
                        'recipient_id' => 3,
                        'group_id' => 2,
                    ),
                3 =>
                    array (
                        'service_id' => $serviceId2,
                        'recipient_id' => 4,
                        'group_id' => 3,
                    ),
                4 =>
                    array (
                        'service_id' => $serviceId3,
                        'recipient_id' => 5,
                        'group_id' => 4,
                    ),
            ));
        }
    }
}
