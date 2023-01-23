<?php

namespace App\Console\Commands;

use App\Models\Board;
use Illuminate\Console\Command;

class DailyNotificator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notificator:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return int
     */
    public function handle()
    {
        $boards = Board::all();
        $boards->map(fn ($board) => $board->sendStatus());
        return 0;
    }
}
