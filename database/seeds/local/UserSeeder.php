<?php

use App\Models\Fabric\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        collect([
            [
                'name' => 'Patchworks Admin',
                'email' => 'fabric_pwadmin@pwks.co',
                'company_id' => 1,
            ],
            [
                'name' => 'Patchworks User',
                'email' => 'fabric_pwuser@pwks.co',
                'company_id' => 1,
            ],
            [
                'name' => 'Client Admin',
                'email' => 'fabric_clientadmin@pwks.co',
                'company_id' => 2,
            ],
            [
                'name' => 'Client User',
                'email' => 'fabric_clientuser@pwks.co',
                'company_id' => 2,
            ]
        ])->each(function ($attributes) {
            factory(User::class)->create($attributes + ['password' => 'local']);
        });

        // attach roles to the users
        User::where('name', 'Patchworks Admin')
            ->first()
            ->assignRole('patchworks admin');

        User::where('name', 'Patchworks User')
            ->first()
            ->assignRole('patchworks user');

        User::where('name', 'Client Admin')
            ->first()
            ->assignRole('client admin');

        User::where('name', 'Client User')
            ->first()
            ->assignRole('client user');
    }
}
