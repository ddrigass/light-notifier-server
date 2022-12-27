<?php

namespace App\Models;

use App\Console\Commands\CheckBoards;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Board extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'external_id', 'chat_id', 'last_activity', 'active', 'enable_time', 'timeout'
    ];

    public static $enabled_text = "💡 Включили свет 💡";
    public static $disabled_text = "❌ Выключили свет ❌";
    public static function getText($enabled, $lastEnable, $last_activity)
    {
        $text = '';
        $last_activity = Carbon::parse($last_activity);
        $lastEnable = Carbon::parse($lastEnable);
        // text about enabling
        if ($enabled) {
            $text = self::$enabled_text . "\nСвета небыло: " . gmdate('H:i:s', Carbon::now()->diffInSeconds($last_activity));
        }
        if (!$enabled) {
            $text = self::$disabled_text . "\nСвет был в течении: " . gmdate('H:i:s', Carbon::now()->diffInSeconds($lastEnable));
        }
        return urlencode($text);
    }

    protected static function boot()
    {
        static::updating(function ($model) {
            $new = $model->active;
            $old = $model->getOriginal()['active'] ?? $new;
            if ($old !== $new) {
                CheckBoards::sendMessage($model->chat_id, self::getText($new, $model->enable_time, $model->getOriginal()['last_activity']));
            }
            if ($new && !$old) {
                $model->enable_time = Carbon::now();
            }
        });

        parent::boot();
    }
}
