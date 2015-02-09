<?php

class UsersTableSeeder extends Seeder {

    public function run() {
        DB::table('users')->delete();
        DB::table('clients')->delete();
        DB::table('admins')->delete();
        DB::table('experts')->delete();

        Eloquent::reguard();
        Admin::create(['username' => 'admin', 'password' => 'zhonyas2015', 'name' => 'Administrador', 'email' => 'pedromdspereira.93@gmail.pt']);
        Admin::create(['username' => 'botelho', 'password' => 'botelhovaz2015', 'name' => 'Botelho Vaz', 'email' => 'botelho.vaz@perigest.pt']);
        Admin::create(['username' => 'daniel', 'password' => 'danielmoreira2015', 'name' => 'Daniel Moreira', 'email' => 'daniel.moreira@perigest.pt']);
        Admin::create(['username' => 'cristiana', 'password' => 'cristiananunes2015', 'name' => 'Cristiana Nunes', 'email' => 'cristiana.nunes@perigest.pt']);
        Eloquent::unguard();
    }

}
