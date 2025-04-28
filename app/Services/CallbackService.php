<?php

namespace App\Services;

use App\Models\Executor;
use DateTime;
use Telegram\Bot\Laravel\Facades\Telegram;

class CallbackService
{
    /**
     * @param $update
     * @param Executor $executor
     */
    public function process($update, Executor $executor): void
    {   
        // если это не нажатие на кнопку, выходим из метода
        $callbackQuery = $update->getCallbackQuery();

        $messageId = $callbackQuery->getMessage()->getMessageId();
        $data = explode(";", $callbackQuery->getData());

        if ( $data[0] == '/rate_links' ) {
            Telegram::triggerCommand('rate_links', $update);
            return;
        }

        $serviceName = ucfirst($data[0]);
        $servicePath = __NAMESPACE__ . '\Callbacks\\' . $serviceName;
        $serviceInstance = new $servicePath;

        //Example: ../Services/Callbacks/Information::process($executor, (int)$messageId, $data)
        call_user_func_array([$serviceInstance, 'process'], [$executor, $update, $data]);
    }
}
