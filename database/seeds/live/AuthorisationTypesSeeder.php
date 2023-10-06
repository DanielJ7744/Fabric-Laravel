<?php

use App\Models\Fabric\AuthorisationType;
use Illuminate\Database\Seeder;

class AuthorisationTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $insertData = [
            [
                'name' => 'oauth1'
            ],
            [
                'name' => 'oauth2'
            ],
            [
                'name' => 'ntlm'
            ],
            [
                'name' => 'basic_auth'
            ],
            [
                'name' => 'none'
            ],
            [
                'name' => 'ftp'
            ],
            [
                'name' => 'ssh'
            ]
        ];

        foreach ($insertData as $insert) {
            $result = AuthorisationType::firstOrCreate($insert);
            $result->save();
        }
    }
}
