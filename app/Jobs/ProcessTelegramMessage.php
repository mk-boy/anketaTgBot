<?php

namespace App\Jobs;

use App;
use App\Services\CallbackService;
use App\Services\TextMessageService;
use App\Models\Executor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Telegram\Bot\Laravel\Facades\Telegram;
use Illuminate\Support\Facades\Redis as RedisFacade;
use Telegram\Bot\Objects\Update as UpdateObject;
use Telegram\Bot\Traits\CommandsHandler;
use Throwable;
use App\Jobs\Middleware\TelegramMessageRateLimited;
use Config;
use DateTime;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Redis;

class ProcessTelegramMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $updateJson;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($updateJson)
    {
        $this->updateJson = $updateJson;
    }

    /**
     * Get the middleware the job should pass through.
     *
     * @return array
     */
    public function middleware()
    {
        return [new TelegramMessageRateLimited];
    }

    public function handle(CallbackService $callbackService, TextMessageService $textMessageService)
    {
        $update = new UpdateObject($this->updateJson);

        try {
            if ( $update->getMessage()->getChat()->getType() != 'private' ) {
                return ;
            }
        }
        catch ( Exception $ex ) {

        }

        $executor_telegram_id = $update->getMessage()->getChat()->getId();

        $executor = Executor::where('telegram_id', $executor_telegram_id)->first();

        $message = $update->getMessage();

        if ($executor) {
            if (!$executor->active) return;
        }

        if ( !$message->isEmpty() ) {
            if ( $this->messageHasCommand($message) ) {
                Telegram::processCommand($update);
                return;
            }
        }

        if ($callbackQuery = $update->getCallbackQuery()) {
            $callbackService->process($update, $executor);
            return;
        }
        
        if ($textMessageService->process($update, $executor)) {
            return;
        }
    }

    /**
     * Проверка на содержание в сообщении команд
     *
     * @param $message сообщение от телеграма
     *
     * @return bool признак содержания хотя бы одной команды
     */
    private function messageHasCommand($message) {
        if ( $message->has('entities')) {

            $entities = collect($message->get('entities'))
                        ->filter(function ($entity) {
                            return $entity['type'] === 'bot_command';
                        });

            return count($entities) > 0;
        }

        return false;
    }

    /**
     * Handle a job failure.
     *
     * @param \Throwable $exception
     * @return void
     */
    public function failed(Throwable $exception)
    {
        // Send user notification of failure, etc...
        return;
    }
}
