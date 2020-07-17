<?php

declare(strict_types=1);

use Hyperf\Database\Seeders\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        \App\Model\User::create([
            'username' => 'admin',
            'password' => '123456',
            'nick_name' => '超级管理员',
            'real_name' => '超级管理员'
        ]);
    }
}
