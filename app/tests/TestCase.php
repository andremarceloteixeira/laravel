<?php

class TestCase extends Illuminate\Foundation\Testing\TestCase {
	/**
	 * Overwrites the _call so we can use $this->post('users'), instead of $this->call('POST', 'users')
	 *
	 * @return action
	 */
        public function __call($method, $args)
        {
          if (in_array($method, ['get', 'post', 'put', 'patch', 'delete']))
          {
              return $this->call($method, $args[0]);
          }

          throw new BadMethodCallException;
        }
    
	/**
	 * Creates the application.
	 *
	 * @return \Symfony\Component\HttpKernel\HttpKernelInterface
	 */
	public function createApplication()
	{
		$unitTesting = true;

		$testEnvironment = 'testing';

		return require __DIR__.'/../../bootstrap/start.php';
	}
        
        /**
         * Default preparation for each test
        */
        public function setUp()
        {
            parent::setUp();
            $this->prepareForTests();
        }
        
        /**
         * Migrates the database and set the mailer to 'pretend'.
         * This will cause the tests to run quickly.
         *
        */
         private function prepareForTests()
        {
            Artisan::call('migrate');
            Artisan::call('db:seed');
            Mail::pretend(true);
        }
        


}
