<?php

namespace App\Commands\MainBot;

use App;
use App\Facades\Settings;
use Telegram\Bot\Actions;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Commands\Command;
use Illuminate\Support\Facades\DB;
use Log;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\Library\TelegramKeyboardHelper;
use App\Models\Executor;
use Telegram\Bot\FileUpload\InputFile;

class StartCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected string $name = "start";

    /**
     * @var string Command Description
     */
    protected string $description = "Start Command to get you started";

    /**
     * @inheritdoc
     */
    public function handle()
    {   
        $username = $this->getUpdate()->getMessage()->getChat()->getUsername();
        $telegram_id = $this->getUpdate()->getMessage()->getChat()->getId();

        $exist_executor = Executor::where('telegram_id', $telegram_id)->first();

        if ($exist_executor) {
            return ;
        } else {
            Executor::create([
                'username' => $username,
                'telegram_id' => $telegram_id
            ]);

            Telegram::sendPhoto([
                'chat_id' => $telegram_id,
                'photo' => new InputFile('https://i.ibb.co/Vc3wTjzW/Logo-1.png'),
                'caption' => '👋 Добрый день, мы команда “МигРабота”, поможем вам найти работу' . PHP_EOL . '-Без опыта работы' . PHP_EOL . '-Стабильная поддержка 24/7' . PHP_EOL . '-Свободный график' . PHP_EOL . '-Высокий доход' . PHP_EOL . 'Первым делом хотели бы уточнить в какой компании хотите работать?',
                'reply_markup' => TelegramKeyboardHelper::getFirstQuestionKeyboard()
            ]);
        }
    }
}