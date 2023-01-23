<?php

namespace App\Models;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoardHistory extends Model
{
    protected $fillable = [
      'time',
      'board_id',
      'status'
    ];

    const STATUS_ENABLED = 'enable';
    const STATUS_DISABLED = 'disable';

    static public function getTimeByBoard(Board $board, $start, $end) {
        $history = BoardHistory::select('time', 'status')
            ->whereBoardId($board->id)
            ->whereBetween('created_at', [
                $start,
                $end
            ])
            ->orderBy('id')
            ->get();

        $enabled = 0;
        $disabled = 0;

        $lastTime = $start;
        $lastState = self::STATUS_DISABLED;
        foreach ($history as $item) {
            $time = Carbon::parse($item->time);
            if ($item->status === self::STATUS_ENABLED) {
                $disabled += $time->diffInSeconds($lastTime);
            }
            if ($item->status === self::STATUS_DISABLED) {
                $enabled += $time->diffInSeconds($lastTime);
            }
            $lastState = $item->status;
            $lastTime = $time;
        }

        if ($lastState === self::STATUS_ENABLED) {
            $enabled += $end->diffInSeconds($lastTime);
        } else {
            $disabled += $end->diffInSeconds($lastTime);
        }

        return [
            'enabled' => $enabled,
            'disabled' => $disabled
        ];
    }
}
