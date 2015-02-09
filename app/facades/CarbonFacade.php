<?php namespace Support\Facades;

use Illuminate\Support\Facades\Facade;

class Carbon extends Facade {
    protected static function getFacadeAccessor() { 
		return 'carbon';
	}
}