<!DOCTYPE html>
<html lang="ru">
<head>
    <title>Урок - {{$lesson->name}}</title>
    <link rel="icon" href="/storage/img/icon_1.ico" type="image/x-icon">
    <meta charset="utf8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="{{asset('/css/bootstrap.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('/css/main.css')}}" rel="stylesheet" type="text/css">
    <link href="/katex/katex.css" rel="stylesheet" type="text/css"/>
    <script src="/katex/katex.js" type="text/javascript"></script>
    <script>
        // при загрузке изображения со стороннего ресурса -- включается в работу katex. Нужно лишь навесить событие и в качестве обработчика указать этот метод
        function onLoadLatexImg(img) {
            // формируем динамически новый div
            var newDiv = document.createElement('div');
            // katex берет latex-код из img.alt и формирует наш новый div
            katex.render(img.alt, newDiv);
            // заменим img на div
            img.parentElement.replaceChild(newDiv, img);
        }
    </script>

</head>
<body>
<div class="wrapper">
    <div class="container content">
        @include('layouts.messages.message')
        <br>
        <!--ВЕРХНЕЕ МЕНЮ!-->
    @include('layouts.top.menu')
    <!--ВСЁ, ЧТО ПОСЛЕ ВЕРХНЕГО МЕНЮ!-->
        <div class="row row-full-width">
            <!-- ЛЕВОЕ МЕНЮ !-->
            <div class="col-xs-12 col-sm-2">
                @include('layouts.left.auth')
                @include('layouts.left.menu')
            </div>
            <!-- КОНЕЦ ЛЕВОГО МЕНЮ!-->

            <!--Содержимое страницы!-->
            <div class="col-xs-12 col-sm-10" style="padding-left: 25px;">
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
                <div id="app">
                    <example-component></example-component>
                </div>
                <div id="onlyLessonPageContentCSS">
                    {!!$lesson->content!!}
                </div>
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
                <br>
                @foreach ($comments as $comment)
                    <div class="media-block">
                        <a class="media-left" href="#">
                            <img class="img-circle img-sm" name="avatar_image[]" src="{{$comment->avatar_src}}"
                                 onerror="this.src = '/storage/img/AVATAR_ZAYAC.png'">
                        </a>
                        <div class="media-body">

                            <div class="mar-btm">
                                <a href="#"
                                   class="btn-link text-semibold media-heading box-inline">{{$comment->user_name}}</a>
                                <p class="text-muted text-sm"> {{$comment->datetime}}</p>
                            </div>
                            <p>{{$comment->content}}</p>
                            <hr>
                        </div>
                    </div>
                @endforeach
                @if ($role!==null && $role->level > 10)
                    <div class="col-md-12">
                        <div class="panel">
                            <div style="width:100%">
                                <form method="post" action="/add_comment">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="user_id" value="{{$user->id}}">
                                    <input type="hidden" name="lesson_id" value="{{$lesson->id}}">
                                    <input name="content" type="text" class="form-control"
                                           placeholder="Добавьте Ваш комментарий">

                                    <div class="mar-top clearfix">
                                        <button class="btn btn-sm btn-outline-dark" type="submit"
                                                style="width:160px; float:right; margin-top:10px"> Добавить
                                        </button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                @else
                    <div class="alert alert-warning" role="alert">
                        Только зарегистрированные и подтверждённые ученики могут писать комментарии.
                    </div>
            @endif


            <!--===================================================-->


                <br>
                <br>


            </div>
            <!-- КОНЕЦ Содержимого страницы!-->

        </div>

        <footer class="footer container">КФУ им. В.И. Вернадского, Симферополь, 2020</footer>
    </div>
</div>
<script src="/js/app.js"></script>
</body>
</html>
