<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // admin account
        User::create([
            'username' => 'admin',
            'password' => '12345',
            'first_name' => 'admin',
            'middle_name' => 'admin',
            'last_name' => 'admin',
            'suffix' => null,
            'prefix' => null,
            'nickname' => 'admin',
            'account_status' => 'Active',
            'user_level' => 1
        ]);

        // secretariat account
        User::create([
            'username' => 'secretariat',
            'password' => '12345',
            'first_name' => 'secretariat',
            'middle_name' => 'secretariat',
            'last_name' => 'secretariat',
            'suffix' => null,
            'prefix' => null,
            'nickname' => 'secretariat',
            'account_status' => 'Active',
            'user_level' => 2
        ]);

        // pdoho-abra account
        User::create([
            'username' => 'pdoho-abra',
            'password' => '12345',
            'first_name' => 'pdoho-abra',
            'middle_name' => 'pdoho-abra',
            'last_name' => 'pdoho-abra',
            'suffix' => null,
            'prefix' => null,
            'nickname' => 'pdoho-abra',
            'account_status' => 'Active',
            'user_level' => 3,
            'pdoho_province_id' => 1
        ]);

         // pdoho-benguet account
        User::create([
            'username' => 'pdoho-benguet',
            'password' => '12345',
            'first_name' => 'pdoho-benguet',
            'middle_name' => 'pdoho-benguet',
            'last_name' => 'pdoho-benguet',
            'suffix' => null,
            'prefix' => null,
            'nickname' => 'pdoho-benguet',
            'account_status' => 'Active',
            'user_level' => 3,
            'pdoho_province_id' => 2
        ]);

         // pdoho-ifugao account
        User::create([
            'username' => 'pdoho-ifugao',
            'password' => '12345',
            'first_name' => 'pdoho-ifugao',
            'middle_name' => 'pdoho-ifugao',
            'last_name' => 'pdoho-ifugao',
            'suffix' => null,
            'prefix' => null,
            'nickname' => 'pdoho-ifugao',
            'account_status' => 'Active',
            'user_level' => 3,
            'pdoho_province_id' => 3
        ]);

         // pdoho-kalinga account
        User::create([
            'username' => 'pdoho-kalinga',
            'password' => '12345',
            'first_name' => 'pdoho-kalinga',
            'middle_name' => 'pdoho-kalinga',
            'last_name' => 'pdoho-kalinga',
            'suffix' => null,
            'prefix' => null,
            'nickname' => 'pdoho-kalinga',
            'account_status' => 'Active',
            'user_level' => 3,
            'pdoho_province_id' => 4
        ]);

         // pdoho-mp account
        User::create([
            'username' => 'pdoho-mp',
            'password' => '12345',
            'first_name' => 'pdoho-mp',
            'middle_name' => 'pdoho-mp',
            'last_name' => 'pdoho-mp',
            'suffix' => null,
            'prefix' => null,
            'nickname' => 'pdoho-mp',
            'account_status' => 'Active',
            'user_level' => 3,
            'pdoho_province_id' => 5
        ]);

         // pdoho-apayao account
        User::create([
            'username' => 'pdoho-apayao',
            'password' => '12345',
            'first_name' => 'pdoho-apayao',
            'middle_name' => 'pdoho-apayao',
            'last_name' => 'pdoho-apayao',
            'suffix' => null,
            'prefix' => null,
            'nickname' => 'pdoho-apayao',
            'account_status' => 'Active',
            'user_level' => 3,
            'pdoho_province_id' => 6
        ]);

        

    }
}
