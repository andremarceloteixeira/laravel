<?php

class DatabaseSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        Eloquent::unguard();
        $this->call('CountriesTableSeeder');
        $this->call('RolesTableSeeder');
        $this->call('UsersTableSeeder');
        $this->call('StatusTableSeeder');
        $this->call('InsuredsTableSeeder');
        $this->call('TypesTableSeeder');
        $this->call('ProcessesTableSeeder');
        $this->call('EventsTableSeeder');
        $this->call('NotificationsTableSeeder');
    }

}
