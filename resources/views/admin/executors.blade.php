<a class="btn btn-success" href="/export-anketa">Экспорт</a>

<table class="table table-bordered mt-2">
  <thead>
    <tr>
      <th scope="col">Пользователь</th>
      <th scope="col">{{ trans('admin.company_question') }}</th>
      <th scope="col">{{ trans('admin.vacancy_question') }}</th>
      <th scope="col">{{ trans('admin.fio_question') }}</th>
      <th scope="col">{{ trans('admin.age_question') }}</th>
      <th scope="col">{{ trans('admin.number_question') }}</th>
      <th scope="col">{{ trans('admin.alfa_question') }}</th>
      <th scope="col">{{ trans('admin.exp_question') }}</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($executors as $executor)
      <tr>
        <th scope="col">
            Username: <b>{{ $executor->username }}</b> <br>
            Telegram ID: <b>{{ $executor->telegram_id }}</b>
        </th>
        <th>{{ $executor->q1 }}</th>
        <th>{{ $executor->q2 }}</th>
        <th>{{ $executor->q3 }}</th>
        <th>{{ $executor->q4 }}</th>
        <th>{{ $executor->q5 }}</th>
        <th>{{ $executor->q6 }}</th>
        <th>{{ $executor->q7 }}</th>
      </tr>
    @endforeach
  </tbody>
</table>