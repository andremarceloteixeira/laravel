<?php

class RolesTableSeeder extends Seeder {

    public function run() {
        DB::table('roles')->delete();
        Role::create(['id' => 1, 'code' => 'roles.client']);
        Role::create(['id' => 2, 'code' => 'roles.expert']);
        Role::create(['id' => 3, 'code' => 'roles.admin']);
        Role::create(['id' => 4, 'code' => 'roles.super']);
    }

}
