<?php

namespace App\Models;

use App\Console\Commands\CheckBoards;
use App\Domains\Announcement\Models\Announcement;
use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Board extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'external_id',
        'chat_id',
        'last_activity',
        'active',
        'enable_time',
        'timeout',
        'disabled_notifications',
        'enabled_text',
        'disabled_text'
    ];

    public function getText($enabled, $board): string
    {
        $last_activity = Carbon::parse($board->getOriginal()['last_activity']);
        $enable_time = Carbon::parse($board->enable_time);
        // text about enabling
        if ($enabled) {
            $disabled_time = self::getDisabledTime($last_activity);
            $text = str_replace(
                '$disabled_time',
                $disabled_time,
                $board->enabled_text
            );
        }
        if (!$enabled) {
            $enabled_time = self::getEnabledTime($enable_time);
            $text = str_replace(
                '$enabled_time',
                $enabled_time,
                $board->disabled_text
            );
        }
        return urlencode($text);
    }

    protected static function boot()
    {
        static::updating(function ($model) {
            $new = $model->active;
            $old = $model->getOriginal()['active'] ?? $new;
            if ($old !== $new) {
                BoardHistory::create([
                    'board_id' => $model->id,
                    'status' => $new ? 'enable' : 'disable',
                ]);
                if ($model->disabled_notifications) return;
                CheckBoards::sendMessage(
                    $model->chat_id,
                    $model->getText($new, $model)
                );
            }
            if ($new && !$old) {
                $model->enable_time = Carbon::now();
            }
        });

        parent::boot();
    }

    private static function getDisabledTime($last_activity)
    {
        return Carbon::now()->diffForHumans($last_activity, true);
    }

    private static function getEnabledTime($enable_time)
    {
        return Carbon::now()->diffForHumans($enable_time, true);
    }

    public function sendStatus()
    {
//        $last_activity = $this->last_activity;
//        $enable_time = $this->enable_time;

        $statusText = "";
//        if ($this->active) {
//            $enabled_time = self::getEnabledTime($enable_time);
//            $statusText = "💡 Свет есть уже: $enabled_time";
//        } else {
//            $disabled_time = self::getDisabledTime($last_activity);
//            $statusText = "❌ Света нет уже: $disabled_time";
//        }

        $startOfDay = Carbon::now()->startOfDay();
        $endOfDay = Carbon::now()->endOfDay();

        $time = BoardHistory::getTimeByBoard($this, $startOfDay, $endOfDay);

        $enabled_at_day_for_humans = CarbonInterval::seconds($time['enabled'])->cascade()->forHumans();
        $disabled_at_day_for_humans = CarbonInterval::seconds($time['disabled'])->cascade()->forHumans();

        $statusText .= '
😇 Свет был сегодня в течении: '.$enabled_at_day_for_humans;
        $statusText .= '
🥲 Света не было сегодня в течении: '.$disabled_at_day_for_humans;

        Announcement::create([
            'area' => null,
            'type' => 'info',
            'message' => $this->chat_id.$statusText,
            'enabled' => true,
        ]);
//        CheckBoards::sendMessage(
//            $this->chat_id,
//            urlencode($statusText),
//            true
//        );
    }
}
