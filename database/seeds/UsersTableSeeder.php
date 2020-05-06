<?php

use Illuminate\Database\Seeder;
Use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //1
        User::create([
            'name' => 'Manuel Mendoza',
            'email' => 'manuel.mdz.rom@gmail.com',
            'password' => bcrypt('Manuelnene2796'), // secret
            'remember_token' => str_random(10),
            'dni' =>  '27962020',
            'address' => '',
            'phone' => '',
            'role' => 'admin'
        ]);
        //2
        User::create([
            'name' => 'Doctor Manuel',
            'email' => 'doctor@gmail.com',
            'password' => bcrypt('123456'), // secret
            'remember_token' => str_random(10),
            'dni' =>  '27962020',
            'address' => '',
            'phone' => '',
            'role' => 'doctor'
        ]);
        //3
        User::create([
            'name' => 'Paciente Manuel',
            'email' => 'paciente@gmail.com',
            'password' => bcrypt('123456'), // secret
            'remember_token' => str_random(10),
            'dni' =>  '27962020',
            'address' => '',
            'phone' => '',
            'role' => 'patient'
        ]);

        factory(User::class, 50)->states('patient')->create();
    }
}
