<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ProcessesCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'processes:clean';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Cleans all the processes and their files.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
            if ($this->confirm('Are you sure you want to delete all processes? [yes|no]')) {
                if ($this->confirm('All data will be erased, you sure? [yes|no]')) {
                    DB::table('processes')->delete();
                    DB::statement('ALTER TABLE processes AUTO_INCREMENT=1');
                    File::cleanDirectory(Config::get('settings.process_folder'));
                    $this->info('All processes were deleted.');
                }
            }
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array();
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array();
	}

}
