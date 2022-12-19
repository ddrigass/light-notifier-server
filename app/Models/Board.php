<?php

namespace App\Models;

use App\Console\Commands\CheckBoards;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'external_id', 'chat_id', 'last_activity', 'active'
    ];

    protected static function boot()
    {
        static::updating(function ($model) {
            $new = $model->active;
            $old = $model->getOriginal()['active'] ?? $new;
            if ($old !== $new) {
                CheckBoards::sendMessage($model->chat_id, $new ? 'Включили свет' : 'Выключили свет');
            }
        });

        parent::boot();
    }
}
