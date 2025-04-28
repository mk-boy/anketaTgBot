<?php

namespace App\Http\Controllers;

use AdminSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Executor;
use App\Models\ExecutorQuestion;
use App\Models\Question;

class AdminViewsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function executors()
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

        return AdminSection::view(view('admin.executors', [
            'executors' => $executors
        ]));
    }
}