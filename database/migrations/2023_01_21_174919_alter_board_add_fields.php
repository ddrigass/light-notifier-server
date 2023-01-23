<?php

use App\Models\Board;
use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterBoardAddFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('boards', function (Blueprint $table) {
            $table->boolean('disabled_notifications')->default(false);
            $table->string('enabled_text')->default('💡 Включили свет 💡
Света не было: $disabled_time');
            $table->string('disabled_text')->default('❌ Выключили свет ❌
Свет был в течении: $enabled_time');
        });

        Schema::create('board_histories', function (Blueprint $table) {
            $table->id();
            $table->string('time')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->foreignIdFor(Board::class)->index();
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('boards', function (Blueprint $table) {
            $table->dropColumn('disabled_notifications');
            $table->dropColumn('enabled_text');
            $table->dropColumn('disabled_text');
        });
        Schema::dropIfExists('board_histories');
    }
}
