<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\User::create([
            'name' => 'Admin',
            'surname' => 'AdminSurname',
            'patronymic' => 'AdminPatronymic',
            'department_id' => 0,
            'description' => 'Admin',
            'admin' => true,
            'email' => 'admin@admin.com',
            'password' => bcrypt('admin')
        ]);
    }
}
