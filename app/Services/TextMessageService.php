<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Telegram\Bot\Laravel\Facades\Telegram;
use DateTime;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Telegram\Bot\Exceptions\TelegramResponseException;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Objects\Update as UpdateObject;
use App\Models\ExecutorPendingAction;
use App\Models\Question;
use App\Models\ExecutorQuestion;
use App\Library\TelegramKeyboardHelper;

class TextMessageService
{
    private $update;

    /**
     * @param $update
     * @return bool
     */
    public function process($update, $executor): bool
    {        
        $this->update = $update;
        $message = $update->getMessage();
        $text = $message->getText();

        if ( Telegram::triggerCommand(mb_strtolower($text), $this->update) )
            return true;

        $executor_pending_action = ExecutorPendingAction::where('executor_id', $executor->id)->first();

        if ($executor_pending_action) {
            switch ($executor_pending_action->pending_action_id) {
                case ExecutorPendingAction::FIO_PENDING_ACTION_ID:
                    ExecutorQuestion::create([
                        'executor_id' => $executor->id,
                        'question_id' => Question::FIO_QUESTION_ID,
                        'value' => $text
                    ]);
                    break;
                case ExecutorPendingAction::AGE_PENDING_ACTION_ID:
                    ExecutorQuestion::create([
                        'executor_id' => $executor->id,
                        'question_id' => Question::AGE_QUESTION_ID,
                        'value' => $text
                    ]);
                    break;
                case ExecutorPendingAction::NUMBER_PENDING_ACTION_ID:
                    ExecutorQuestion::create([
                        'executor_id' => $executor->id,
                        'question_id' => Question::NUMBER_QUESTION_ID,
                        'value' => $text
                    ]);
                    break;
            }

            $executor_pending_action->delete();

            if (in_array($executor_pending_action->pending_action_id + 1, [3, 4, 5])) {
                ExecutorPendingAction::create([
                    'executor_id' => $executor->id,
                    'pending_action_id' => $executor_pending_action->pending_action_id + 1
                ]);
    
                Telegram::sendMessage([
                    'chat_id' => $executor->telegram_id,
                    'text' => trans('admin.' . Question::QUESTIONS[$executor_pending_action->pending_action_id + 1])
                ]);

                return true;
            }

            Telegram::sendMessage([
                'chat_id' => $executor->telegram_id,
                'text' => trans('admin.' . Question::QUESTIONS[$executor_pending_action->pending_action_id + 1]),
                'reply_markup' => TelegramKeyboardHelper::getQuestionCallbackKeyboard($executor_pending_action->pending_action_id + 1)
            ]);
        }


        // никакого ответа пользователю не было
        return false;
    }
}