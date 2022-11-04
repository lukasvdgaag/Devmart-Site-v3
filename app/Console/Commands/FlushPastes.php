<?php

namespace App\Console\Commands;

use App\Models\Paste;
use Illuminate\Console\Command;

class FlushPastes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pastes:flush';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete all expired pastes';

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
     */
    public function handle()
    {
        $models = Paste::query()
            ->whereNotNull('expire_at')
            ->whereRaw('now() >= expire_at')
            ->get()->getIterator();

        foreach ($models as $paste) {
            if ($paste instanceof Paste) $paste->delete();
        }
    }
}
