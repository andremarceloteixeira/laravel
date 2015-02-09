<?php

class ProcessesTableSeeder extends Seeder {

	public function run()
	{
            DB::table('processes')->delete();
            //DB::statement('ALTER TABLE processes AUTO_INCREMENT=1');
            //$experts = [2,3,4];
            //$clients = [5,6,7];
            /*for($i=0; $i<15; $i++) {
                Process::create([
                    'client_id' => $clients[mt_rand(0, count($clients)-1)], 
                    'apolice' => mt_rand(100000, 500000), 
                    'status_id' => 2,
                    'expert_id' => $experts[mt_rand(0,count($experts)-1)],
                    'type_id' => 1,
                ]);
            }
            
            for($i=0; $i<8; $i++) {
                Process::create([
                    'client_id' => $clients[mt_rand(0, count($clients)-1)], 
                    'apolice' => mt_rand(10000, 50000), 
                    'status_id' => 1,
                ]);
            }*/
            
	}

}
