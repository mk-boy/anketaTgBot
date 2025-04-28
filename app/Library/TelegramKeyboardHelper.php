<?php

namespace App\Library;

use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\Models\Question;

class TelegramKeyboardHelper
{
    public static function getInlineKeyboard($buttons)
    {
        $keyboard = Keyboard::make()->inline()->setResizeKeyboard(true);

        foreach ($buttons as $button) {
            $keyboard->row([$button]);
        }

        return $keyboard;
    }

    public static function getFirstQuestionKeyboard()
    {
        $buttons = [
            ['text' => 'Яндекс Еда 🟠', 'callback_data' => 'questions;1;Яндекс Еда 🟠'], 
            ['text' => 'ВкусВилл 🟢', 'callback_data' => 'questions;1;ВкусВилл 🟢']
        ];

        $keyboard = self::getInlineKeyboard($buttons);

        return $keyboard;
    }

    public static function getSecondQuestionKeyboard()
    {
        $buttons = [
            ['text' => 'Велокурьер 🚴', 'callback_data' => 'questions;2;Велокурьер 🚴'],
            ['text' => 'Пеший курьер 🚶', 'callback_data' => 'questions;2;Пеший курьер 🚶'],
            ['text' => 'Курьер на авто 🚗', 'callback_data' => 'questions;2;Курьер на авто 🚗']
        ];

        $keyboard = self::getInlineKeyboard($buttons);

        return $keyboard;
    }

    public static function getSixthQuestionKeyboard()
    {
        $buttons = [
            ['text' => 'Да', 'callback_data' => 'questions;6;Да'],
            ['text' => 'Нет', 'callback_data' => 'questions;6;Нет']
        ];

        $keyboard = self::getInlineKeyboard($buttons);

        return $keyboard;
    }

    public static function getSevenQuestionKeyboard()
    {
        $buttons = [
            ['text' => 'Да', 'callback_data' => 'questions;7;Да'],
            ['text' => 'Нет', 'callback_data' => 'questions;7;Нет']
        ];

        $keyboard = self::getInlineKeyboard($buttons);

        return $keyboard;
    }

    public static function getQuestionCallbackKeyboard($question_id)
    {
        switch ($question_id) {
            case Question::COMPANY_QUESTION_ID:
                return self::getFirstQuestionKeyboard();
                break;
            case Question::VACANCY_QUESTION_ID:
                return self::getSecondQuestionKeyboard();
                break;
            case Question::ALFA_QUESTION_ID:
                return self::getSixthQuestionKeyboard();
                break;
            case Question::EXPERIENCE_QUESTION_ID:
                return self::getSevenQuestionKeyboard();
                break;
        }
    }
}
