<?php

namespace App\Console\Commands\App;

use Illuminate\Console\Command;

class CleanupSoftDeletes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cleanup:soft-deletes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete finally soft deletes Model instances. Config: `config/site.php`';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $items = config('site.models.cleanup.soft-deletes');

        foreach ($items as $model => $days) {
            /** @noinspection PhpUndefinedMethodInspection */
            $model::onlyTrashed()->where('deleted_at', '<=' ,now()->subDays($days))->forceDelete();
        }

        return 0;
    }
}
