<?php

namespace App\Services\Callbacks;

use Carbon\Carbon;
use App\Library\TelegramKeyboardHelper;
use App\Models\Executor;
use App\Models\ExecutorQuestion;
use App\Models\Question;
use App\Models\ExecutorPendingAction;
use Illuminate\Support\Facades\DB;
use Telegram\Bot\Exceptions\TelegramResponseException;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\FileUpload\InputFile;

class Questions
{
    public function process(Executor $executor, $update, array $data)
    {
        $answer = $data[2];
        $question_id = (int) $data[1];

        try {
            ExecutorQuestion::create([
                'executor_id' => $executor->id,
                'question_id' => $question_id,
                'value' => $answer
            ]);
        } catch (Exception $ex) {
            return;
        }

        if (($question_id + 1) > count(Question::QUESTIONS)) {
            $message_delete = Telegram::sendMessage([
                'chat_id' => $executor->telegram_id,
                'text' => trans('admin.request_waiting')
            ]);

            sleep(15);

            Telegram::deleteMessage([
                'chat_id' => $executor->telegram_id,
                'message_id' => $message_delete->getMessageId()
            ]);

            $executor_service = ExecutorQuestion::where('executor_id', $executor->id)
            ->where('question_id', Question::COMPANY_QUESTION_ID)->first();

            if ($executor_service->value == 'Яндекс Еда 🟠') {
                Telegram::sendPhoto([
                    'chat_id' => $executor->telegram_id,
                    'photo' => new InputFile('https://i.ibb.co/Z6g7Ghjx/YANDEX-15-SEP-2023-1373.jpgs'),
                    'caption' => trans('admin.final_message') . trans('admin.yandex_href'),
                    'parse_mode' => 'HTML'
                ]);
            } else {
                Telegram::sendPhoto([
                    'chat_id' => $executor->telegram_id,
                    'photo' => new InputFile('https://i.ibb.co/Z6g7Ghjx/YANDEX-15-SEP-2023-1373.jpgs'),
                    'caption' => trans('admin.final_message') . trans('admin.vkusvill_href'),
                    'parse_mode' => 'HTML'
                ]);
            }

            $executor->active = false;
            $executor->save();

            return;
        }

        if (in_array($question_id + 1, [3, 4, 5])) {
            ExecutorPendingAction::create([
                'executor_id' => $executor->id,
                'pending_action_id' => $question_id + 1
            ]);

            Telegram::sendMessage([
                'chat_id' => $executor->telegram_id,
                'text' => trans('admin.' . Question::QUESTIONS[$question_id + 1]),
                'parse_mode' => 'HTML'
            ]);

            return;
        }

        Telegram::sendMessage([
            'chat_id' => $executor->telegram_id,
            'text' => trans('admin.' . Question::QUESTIONS[$question_id + 1]),
            'reply_markup' => TelegramKeyboardHelper::getQuestionCallbackKeyboard($question_id + 1),
            'parse_mode' => 'HTML'
        ]);
    }
}