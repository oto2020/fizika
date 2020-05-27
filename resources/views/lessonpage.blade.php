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
                    Комментарии к уроку [в разработке]:
                </h3>
                <br>


                <style>
                    .img-sm {
                        width: 46px;
                        height: 46px;
                    }
                    .media-block .media-left {
                        display: block;
                        float: left
                    }
                    .media-block .media-right {
                        float: right
                    }
                    .media-block .media-body {
                        display: block;
                        overflow: hidden;
                        width: auto
                    }
                </style>

                <!--===================================================-->
                <div class="media-block">
                    <a class="media-left" href="#"><img class="img-circle img-sm" alt="Профиль пользователя" src="https://bootstraptema.ru/snippets/icons/2016/mia/1.png"></a>
                    <div class="media-body">

                        <div class="mar-btm">
                            <a href="#" class="btn-link text-semibold media-heading box-inline">Максим Смирнов</a>
                            <p class="text-muted text-sm"> 15:35 - 19-06-2016</p>
                        </div>
                        <p>Всем привет, это мой самый первый комментарий, пока что он статичен для всех страниц сайта.</p>
                        <hr>
                    </div>
                </div>

                <div class="media-block">
                    <a class="media-left" href="#"><img class="img-circle img-sm" alt="Профиль пользователя" src="https://bootstraptema.ru/snippets/icons/2016/mia/1.png"></a>
                    <div class="media-body">

                        <div class="mar-btm">
                            <a href="#" class="btn-link text-semibold media-heading box-inline">Николай Прусикин</a>
                            <p class="text-muted text-sm"> 18:39 - 19-06-2016</p>
                        </div>
                        <p>Секция с комментариями для сайта с подключенным Bootstrap!!!</p>
                        <hr>
                    </div>
                </div>
                <!--===================================================-->
                <div class="col-md-12">
                    <div class="panel">
                        <div style="width:100%">
                            <textarea class="form-control" rows="2" placeholder="Добавьте Ваш комментарий" ></textarea>
                            <div class="mar-top clearfix">
                                <button class="btn btn-sm btn-outline-dark" type="submit" style="width:160px; float:right; margin-top:10px"> Добавить </button>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <br>

























            </div>
            <!-- КОНЕЦ Содержимого страницы!-->

        </div>

        <footer class="footer container">КФУ им. В.И. Вернадского, Симферополь, 2020</footer>
    </div>
</div>
</body>
</html>
