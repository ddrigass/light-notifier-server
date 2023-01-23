<?php

namespace App\Console\Commands;

use App\Models\Board;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;

class CheckBoards extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'boards:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check offline boards.';

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
        $now = Carbon::now();
        foreach ($boards as $board) {
            if ($board->active) {
                if (Carbon::parse($board->last_activity)->addSeconds($board->timeout) < $now) {
                    $board->active = false;
                    $board->save();
                }
            }
        }
        return 0;
    }

    static function sendMessage($chatId, $text, $silent = false) {
        $botToken = env("TELEGRAM_BOT_TOKEN");
        try {
            $url = "https://api.telegram.org/$botToken/sendMessage?chat_id=$chatId&text=$text";
            if ($silent)
                $url.= "&disable_notification=true";
            file_get_contents($url);
        }
        catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}
