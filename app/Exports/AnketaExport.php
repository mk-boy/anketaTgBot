<?php

namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use App\Models\Executor;
use App\Models\Question;

class AnketaExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
{
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $cellRange = 'A1:W1';
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);

                for ($i = 1; $i <= 1000; $i++) {
                    $event->sheet->getDelegate()->getRowDimension($i)->setRowHeight(40);
                }
            }
        ];
    }

    public function collection()
    {
        $executors = Executor::join('executor_questions as q1', function ($join) {
            $join->on('q1.executor_id', '=', 'executors.id')->where('q1.question_id', Question::COMPANY_QUESTION_ID);
        })
        ->join('executor_questions as q2', function ($join) {
            $join->on('q2.executor_id', '=', 'executors.id')->where('q2.question_id', Question::VACANCY_QUESTION_ID);
        })
        ->join('executor_questions as q3', function ($join) {
            $join->on('q3.executor_id', '=', 'executors.id')->where('q3.question_id', Question::FIO_QUESTION_ID);
        })
        ->join('executor_questions as q4', function ($join) {
            $join->on('q4.executor_id', '=', 'executors.id')->where('q4.question_id', Question::AGE_QUESTION_ID);
        })
        ->join('executor_questions as q5', function ($join) {
            $join->on('q5.executor_id', '=', 'executors.id')->where('q5.question_id', Question::NUMBER_QUESTION_ID);
        })
        ->join('executor_questions as q6', function ($join) {
            $join->on('q6.executor_id', '=', 'executors.id')->where('q6.question_id', Question::ALFA_QUESTION_ID);
        })
        ->join('executor_questions as q7', function ($join) {
            $join->on('q7.executor_id', '=', 'executors.id')->where('q7.question_id', Question::EXPERIENCE_QUESTION_ID);
        })
        ->select(
            'executors.username',
            'executors.telegram_id',
            'q1.value as q1',
            'q2.value as q2',
            'q3.value as q3',
            'q4.value as q4',
            'q5.value as q5',
            'q6.value as q6',
            'q7.value as q7',
        )
        ->get();

        return $executors;
    }

    public function headings(): array
    {
        return ["Юзернейм", "Telegram ID", "Компания", "Должность", "ФИО", "Возраст", "Номер телефона", "Карта Альфа-банк", "Опыт работы"];
    }
}