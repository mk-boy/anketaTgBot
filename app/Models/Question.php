<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    public const COMPANY_QUESTION_ID = 1;
    public const VACANCY_QUESTION_ID = 2;
    public const FIO_QUESTION_ID = 3;
    public const AGE_QUESTION_ID = 4;
    public const NUMBER_QUESTION_ID = 5;
    public const ALFA_QUESTION_ID = 6;
    public const EXPERIENCE_QUESTION_ID = 7;

    public const COMPANY_QUESTION_CODE = 'company_question';
    public const VACANCY_QUESTION_CODE = 'vacancy_question';
    public const FIO_QUESTION_CODE = 'fio_question';
    public const AGE_QUESTION_CODE = 'age_question';
    public const NUMBER_QUESTION_CODE = 'number_question';
    public const ALFA_QUESTION_CODE = 'alfa_question';
    public const EXPERIENCE_QUESTION_CODE = 'exp_question';

    public const QUESTIONS = [
        self::COMPANY_QUESTION_ID => self::COMPANY_QUESTION_CODE,
        self::VACANCY_QUESTION_ID => self::VACANCY_QUESTION_CODE,
        self::FIO_QUESTION_ID => self::FIO_QUESTION_CODE,
        self::AGE_QUESTION_ID => self::AGE_QUESTION_CODE,
        self::NUMBER_QUESTION_ID => self::NUMBER_QUESTION_CODE,
        self::ALFA_QUESTION_ID => self::ALFA_QUESTION_CODE,
        self::EXPERIENCE_QUESTION_ID => self::EXPERIENCE_QUESTION_CODE
    ];
}
