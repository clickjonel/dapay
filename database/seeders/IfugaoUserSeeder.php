<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IfugaoUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = DB::connection('dohis')
        ->table('dohis_hrh_user')
        ->leftJoin('dohis_hrh_user_assignment', 'dohis_hrh_user.hrh_user_id', '=', 'dohis_hrh_user_assignment.hrh_user_id')
        ->select('dohis_hrh_user.*', 'dohis_hrh_user_assignment.section_id')
        ->where('dohis_hrh_user_assignment.section_id', 7)
        ->get();

        $users->each(function($user){
            // this has multiple records
            if($user->username !== 'amyrosepulpog@gmail.com'){
                User::create([
                    'username' => $user->username,
                    'password' => '12345',
                    'first_name' => $user->first_name,
                    'middle_name' => $user->middle_name,
                    'last_name' => $user->last_name,
                    'suffix' => null,
                    'prefix' => null,
                    'nickname' => $user->first_name,
                    'account_status' => 'Active',
                    'user_level' => 5,
                    'pdoho_province_id' => 3
                ]);
            }

        });
    }
}
