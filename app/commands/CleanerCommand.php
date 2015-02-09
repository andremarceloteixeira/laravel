<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CleanerCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'perigest:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleans useless information from database, including old notifications and events';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire() {
        /* Deleting Old Notifications */
        $numRows1 = Notification::where('created_at', '<', Carbon::now()->subDays(1))->delete();
        $this->info($numRows1 . ' Old Notifications deleted.');
        
        /* Deleting TMP folder */
        if(File::cleanDirectory(storage_path() . '/tmp')) {
             $this->info('Temporary Files deleted.');
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments() {
        return array();
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions() {
        return array();
    }

}
