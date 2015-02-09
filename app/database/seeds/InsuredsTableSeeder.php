<?php

class InsuredsTableSeeder extends Seeder {

    public function run() {
        DB::table('insured_types')->delete();
        InsuredType::create(['id' => 1, 'code' => 'insureds.insured']);
        InsuredType::create(['id' => 2, 'code' => 'insureds.taker']);

        DB::table('insureds')->delete();
    }

}
