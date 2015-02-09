<?php

return array(
	'driver' => 'smtp',
	'host' => '',
	'port' => 587,
	'from' => array('address' => null, 'name' => null),
	'encryption' => 'tls',
	'username' => '',
	'password' => '',
	'sendmail' => '/usr/sbin/sendmail -bs',
	'pretend' => true,

);
