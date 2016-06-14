<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\Jobs;

class SendMassEmail extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'email:approvedlockups';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send mass emails to all lockups creators who have approved lockups';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $job = new Jobs\EmailJob();

        $job->run();

    }

}
