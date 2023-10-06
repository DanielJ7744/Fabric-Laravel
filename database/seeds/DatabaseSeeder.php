<?php

use App\Models\Fabric\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            AuthorisationTypesSeeder::class,
            SystemTypeSeeder::class,
            SystemsSeeder::class,
            SystemAuthorisationTypesSeeder::class,
            EntitiesSeeder::class,
            FactoriesSeeder::class,
            FactoriesSystemsSeeder::class,
            FilterTypeSeeder::class,
            FilterOperatorSeeder::class,
            FilterTypeFilterOperatorSeeder::class,
            FilterFieldSeeder::class,
            FilterFieldFilterTypeSeeder::class,
            FilterTemplatesSeeder::class,
            ServiceTemplateSeeder::class,
            ServiceOptionsSeeder::class,
            ServiceTemplateOptionSeeder::class,
            SubscriptionSeeder::class,
            FactorySystemSchemaSeeder::class,
            DefaultPayloadSeeder::class,
            EventTypesSeeder::class,
        ]);

        //seeders for local development only
        if (App::Environment() === 'local') {

            Artisan::call('passport:install');

            if (!User::count()) {
                $this->call([
                    CompanySeeder::class,
                    PermissionSeeder::class,
                    UserSeeder::class,
                ]);
            }

            $this->call([
                IntegrationSeeder::class,
                AlertsTypesSeeder::class,
                AlertsGroupsSeeder::class,
                AlertsGroupsRecipients::class,
                AlertsConfigsSeeder::class,
                AlertsManagerSeeder::class,
                AlertsServiceRecipientSeeder::class,
            ]);
        }
    }
}
