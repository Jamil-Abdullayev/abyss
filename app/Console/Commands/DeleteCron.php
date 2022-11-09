<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Post;

class DeleteCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This is a cron job for delete 30 days old records each hour';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Post::whereDate( 'created_at', '<=', now()->subDays(30))->delete();
        $this->info('Success.');
    }
}
