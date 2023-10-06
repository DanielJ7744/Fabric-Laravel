<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class AlertsGroupsRecipients extends Seeder
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
            $faker = Faker\Factory::create();
            DB::table('alert_recipients')->insert(array (
                0 =>
                    array (
                        'company_id' => $faker->numberBetween(1,10),
                        'group_id' => $faker->numberBetween(1,5),
                        'user_id' => null,
                        'email' => $faker->email,
                        'name' => $faker->name,
                        'disabled' => $faker->numberBetween(0,1),
                        'created_at' => $now,
                        'updated_at' => $now,
                    ),
                1 =>
                    array (
                        'company_id' => $faker->numberBetween(11,20),
                        'group_id' => NULL,
                        'user_id' => $faker->numberBetween(1,10),
                        'email' => null,
                        'name' => null,
                        'disabled' => $faker->numberBetween(0,1),
                        'created_at' => $now,
                        'updated_at' => $faker->dateTime,
                    ),
                2 =>
                    array (
                        'company_id' => $faker->numberBetween(21,30),
                        'group_id' => $faker->numberBetween(1,10),
                        'user_id' => null,
                        'email' => $faker->email,
                        'name' => $faker->name,
                        'disabled' => $faker->numberBetween(0,1),
                        'created_at' => $now,
                        'updated_at' => $faker->dateTime,
                    ),
                3 =>
                    array (
                        'company_id' => $faker->numberBetween(31,40),
                        'group_id' => $faker->numberBetween(11,20),
                        'user_id' => $faker->numberBetween(11,20),
                        'email' => null,
                        'name' => null,
                        'disabled' => $faker->numberBetween(0,1),
                        'created_at' => $now,
                        'updated_at' => $faker->dateTime,
                    ),
                4 =>
                    array (
                        'company_id' => $faker->numberBetween(41,50),
                        'group_id' => NULL,
                        'user_id' => null,
                        'email' => $faker->email,
                        'name' => $faker->name,
                        'disabled' => $faker->numberBetween(0,1),
                        'created_at' => $now,
                        'updated_at' => $faker->dateTime,
                    ),
            ));


        }
    }
}
