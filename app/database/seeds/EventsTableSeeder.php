<?php

class EventsTableSeeder extends Seeder {

	public function run() {
                EventType::create(['label' => 'label-default','code' => 'events.info']);
                EventType::create(['label' => 'label-blue','code' => 'events.warning']);
                EventType::create(['label' => 'label-red','code' => 'events.danger']);
	}

}
