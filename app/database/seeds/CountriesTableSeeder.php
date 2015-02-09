<?php

class CountriesTableSeeder extends Seeder {

	public function run()
	{
            DB::table('countries')->delete();
            for($i = 1; $i <= 248; $i++) {
                Country::create(['code' => $i]);
            }
	}

}
