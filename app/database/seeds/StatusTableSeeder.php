<?php

class StatusTableSeeder extends Seeder {

	public function run()
	{
            DB::table('status')->delete();
            Status::create(['id' => 1, 'code' => 'status.pending']);
            Status::create(['id' => 2, 'code' => 'status.processing']);
            Status::create(['id' => 3, 'code' => 'status.completed']);
            Status::create(['id' => 4, 'code' => 'status.cancelled']);

	}

}
