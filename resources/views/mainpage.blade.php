<!DOCTYPE html>
<html lang="ru">
<head>
    <title>{{$section->name}} [Физика]</title>
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
                <?php //dump($lessons)?>
                <!--ВЕРХНЕЕ МЕНЮ!-->
                @include('layouts.top.menu')
                <!--ВСЁ, ЧТО ПОСЛЕ ВЕРХНЕГО МЕНЮ!-->
                <div class="row">
                    <!-- ЛЕВОЕ МЕНЮ !-->
                    <div class="col-2">
                        @include('layouts.left.auth')
                    </div>
                    <!-- КОНЕЦ ЛЕВОГО МЕНЮ!-->

                    <!--Содержимое страницы!-->
                    <div class="col-10">
                        <p>
                        <h1>{{$lesson->name}}</h1>
                        @if ($role!==null && $role->name == 'Администратор')
                            <a href="/{{$section->url}}/{{$lesson->url}}/edit_lesson">[редактировать]</a>
                        @endif
                    <!-- КОНТЕНТ СТРАНИЦЫ ИБ БД!-->
                        {!!$lesson->content!!}
                        <br>
                        </p>
                    </div>
                    <!-- КОНЕЦ Содержимого страницы!-->
                </div>
                <footer class="footer container">КФУ им. В.И. Вернадского, Симферополь, 2020</footer>
            </div>

        </div>
    </body>
</html>
