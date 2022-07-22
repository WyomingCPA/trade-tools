<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use TelegramBot\Api\BotApi;

use Carbon\Carbon;

class StatusScript extends Model
{
    protected $table = 'status_script';
    
    protected $fillable = ['name', 'is_run'];

    public static function boot() {

        parent::boot();

        static::updating(function($item) {
            $oldValue = $item->getOriginal();
            if ($oldValue['is_run'] != $item->is_run)
            {
                $is_run_text = '';
                if ($item->is_run === 1)
                {
                    $is_run_text = 'Запущен';
                }
                else {
                    $is_run_text = 'Остановлен';
                }

                $messageText = "Скрипт $item->name $is_run_text \n";
                $messageText .= "Время $item->updated_at \n";  
        
                $chatId = '-414528593';
        
                $bot = new BotApi(env('TELEGRAM_TOKEN'));
                $bot->sendMessage($chatId, $messageText, 'HTML');
            }



        });
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->addHour(3)->format('H:i:s j F Y');
    }
}
