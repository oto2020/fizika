<!DOCTYPE html>
<html lang="ru">
<head>
    <title>Урок - {{$lesson->name}}</title>
    <link rel="icon" href="/storage/img/icon_1.ico" type="image/x-icon">
    <meta charset="utf8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="{{asset('/css/bootstrap.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('/css/main.css')}}" rel="stylesheet" type="text/css">
</head>
<body>
<div class="wrapper">
    <div class="container content">
        @include('layouts.messages.message')
        <br>
        <!--ВЕРХНЕЕ МЕНЮ!-->
    @include('layouts.top.menu')
    <!--ВСЁ, ЧТО ПОСЛЕ ВЕРХНЕГО МЕНЮ!-->
        <div class="row">
            <!-- ЛЕВОЕ МЕНЮ !-->
            <div class="col-2">
                @include('layouts.left.auth')
                @include('layouts.left.menu')
            </div>
            <!-- КОНЕЦ ЛЕВОГО МЕНЮ!-->

            <!--Содержимое страницы!-->
            <div class="col-10">
                <p>
                <h1>{{$lesson->name}}</h1>
                @if ($role!==null && $role->name == 'Администратор')
                    <a href="/{{$section->url}}/{{$lesson->url}}/edit_lesson">
                        [редактировать]
                    </a>
                    <a href="/{{$section->url}}/{{$lesson->url}}/mark_as_deleted"
                       onclick="return confirm ('Внимание: перед удалением урока необходимо удалить все его тесты! Отправляем урок в удалённые?')"
                    >
                        [не отображать на сайте]
                    </a>
                    <br>
                @endif
            <!-- КОНТЕНТ СТРАНИЦЫ ИБ БД!-->
                {!!$lesson->content!!}
                <br>
                <h6>Дата добавления урока: {{$lesson->date}} | Автор урока: {{$lesson->user}}</h6>
                </p>

                <!-- Тесты !-->
                @if (($role!==null && ($role->name == 'Администратор' || $role->name == 'Ученик')))
                    <p>
                    <hr/>
                    <h1>Тесты по уроку:</h1>
                    <ul class="list-group">
                        @foreach($tests as $test)
                            <li class="list-group-item">
                                <a href="/{{$section->url}}/{{$lesson->url}}/{{$test->url}}">{{$test->name}}</a>
                            </li>
                        @endforeach
                    </ul>
                    </p>
                @endif
                @if ($role!==null && $role->name == 'Администратор')
                    <ul class="list-group">
                        <li class="list-group-item">
                            <a href="{{$lesson->full_url}}/add_test/">[добавить тест]</a>
                        </li>
                    </ul>
            @endif
            <!-- Тесты !-->
                <hr>
                <br>
                <h3>
                    Комментарии к уроку:
                </h3>
            </div>
            <!-- КОНЕЦ Содержимого страницы!-->

        </div>

        <footer class="footer container">КФУ им. В.И. Вернадского, Симферополь, 2020</footer>
    </div>
</div>
</body>
</html>
