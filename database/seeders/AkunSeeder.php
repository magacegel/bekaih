<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class AkunSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'id'=>'1',
                'cif'=>'99990',
                'name'=>'super admin',
                'username' => 'superadmin',
                'email'=>'bayumw@gmail.com',
                'phone'=>'081289888084',
                'password'=> bcrypt('bayumwicaksono'),
                'level'=>'superadmin',
            ],
            [
                'id'=>'2',
                'cif'=>'99991',
                'name'=>'administrator',
                'username' => 'administrator',
                'email'=>'bayu.mahardika@bki.co.id',
                'phone'=>'085648133669',
                'password'=> bcrypt('bayumwicaksono'),
                'level'=>'administrator',
            ],
            [
                'id'=>'3',
                'cif'=>'99992',
                'name'=>'inspektor',
                'username' => 'inspektor',
                'email'=>'bayu.mahardika@gmail.com',
                'phone'=>'100000000001',
                'password'=> bcrypt('bayumwicaksono'),
                'level'=>'inspektor',
            ],
            [
                'id'=>'4',
                'cif'=>'99993',
                'name'=>'user',
                'username' => 'User 1',
                'email'=>'a@gmail.com',
                'phone'=>'200000000001',
                'password'=> bcrypt('bki#123'),
                'level'=>'user',
            ],
        ];
        
        User::where('id','>',0)->delete();
        
        foreach ($users as $key => $value) {
            User::create($value);
        }
    }
}
