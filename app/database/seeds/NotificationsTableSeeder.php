<?php

class NotificationsTableSeeder extends Seeder {

    public function run() {
        DB::table('notifications')->delete();
    }

}
