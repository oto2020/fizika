<!DOCTYPE html>
<html lang="ru">
<head>
    <title>{{$role->name}} [Личный кабинет]</title>
    <link rel="icon" href="/storage/img/icon_1.ico" type="image/x-icon">
    <meta charset="utf8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="{{asset('/css/bootstrap.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('/css/main.css')}}" rel="stylesheet" type="text/css">
</head>
<body>
<div class="wrapper">
    <div class="container content">
        <br>

        <!--ВЕРХНЕЕ МЕНЮ!-->
    @include('layouts.top.menu')
    <!--ВСЁ, ЧТО ПОСЛЕ ВЕРХНЕГО МЕНЮ!-->
        <div class="row row-full-width">
            <!-- ЛЕВОЕ МЕНЮ !-->
            <div class="col-xs-12 col-sm-2">
                @include('layouts.left.auth')
            </div>
            <!-- КОНЕЦ ЛЕВОГО МЕНЮ!-->

            <!--Содержимое страницы!-->
            <div class="col-xs-12 col-sm-10" style="padding-left: 25px;">
                <p>
                <h1>Личный кабинет</h1>
                </p>
            <!--ВКЛАДКА РЕЗУЛЬТАТЫ ТЕСТОВ!-->
                @include('layouts.messages.message')
                <?php// dump($testResults);?>
                <h2>Редактирование информации для входа:</h2>
                <div class="form" style="width:30%">
                    <form action="/change_user_info.php" method="post">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="form-group">
                            <b>E-mail</b> <br>
                            <input class="form-control" type ="email" name="email" value="{{Session::get('login_email')}}">
                        </div>
                        <div class="form-group">
                            <b>Пароль</b> <br>
                            <input class="form-control" type="password" name="password">
                        </div>
                        <div class="form-group">
                            <button class="form-control btn-success" type="submit">Применить изменения</button>
                        </div>
                    </form>
                </div>
                <hr>
                <h2>Результаты ваших тестов:</h2>
                <br>
                <table class="table table-striped table-inverse big-table">
                    <thead>
{{--                    <th>#</th>--}}
                    <th style="width:400px">Название теста</th>
                    <th>Результат</th>
                    <th>Дата прохождения</th>
                    <th style="width:500px">Подробнее</th>
                    </thead>
                    <tbody>
                        @foreach ($testResults as $result)
                            <tr style="background-color: rgba(0, 0, 0, 0.05);">
{{--                                <td>--}}
{{--                                    {{$result->user_id}}--}}
{{--                                </td>--}}
                                <td>
                                    {{$result->test_name}}
                                </td>
                                <td>
                                    {{$result->point}} %
                                </td>
                                <td>
                                    {{$result->datetime}}
                                </td>
                                <td colspan="4">
                                    <button onclick="showHideResultDetails({{$result->result_id}})" class="btn btn-secondary btn-sm">Скрыть/показать результаты</button>
                                    <div id="details_{{$result->result_id}}" style="display:none;">
                                        <br>
                                        <?php $details = json_decode($result->details); ?>
                                        <ul>
                                            @foreach ($details as $questionName => $fields)
                                                <li>
                                                    <?php $counter = 0; ?>
                                                    <b>{{$questionName}}</b><br>
                                                    @foreach ($fields as $field)
                                                        @if ($counter === 0) - Результат:           <b>{{$field}}</b> <br>
                                                        @elseif ($counter === 1) - ответ участника: <i>{{$field}}</i> <br>
                                                        @elseif ($counter === 2) @continue
                                                        @endif
                                                        <?php $counter++; ?>
                                                    @endforeach
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <script>
            // по нажатию на кнопку скрывает/показывает контент
            function showHideResultDetails(id) {
                let resultDetails = document.getElementById('details_'+id);
                if (resultDetails.style.display === 'none') {
                    resultDetails.style.display = 'inline';
                    console.log('покажем', resultDetails);
                }
                else {
                    resultDetails.style.display = 'none';
                    console.log('скроем', resultDetails);
                }
            }
        </script>
        <!-- КОНЕЦ Содержимого страницы!-->
        <footer class="footer container">КФУ им. В.И. Вернадского, Симферополь, 2020</footer>
    </div>

</div>

</div>
</body>
</html>
