<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Telegram\Bot\BotsManager;
use App\Jobs\ProcessTelegramMessage;

class WebhookController extends Controller
{
    protected BotsManager $botsManager;

    public function __construct(BotsManager $botsManager)
    {
        $this->botsManager = $botsManager;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        ProcessTelegramMessage::dispatch($data);

        return response("ok", 200);
    }

    public function setWebhook()
    {
        $response = file_get_contents("https://api.telegram.org/bot" . config('telegram.bots.main_bot.token') . "/setWebhook?url=" . env('TELEGRAM_WEBHOOK_HANDLER_URL'));

        return $response;
    }
}
