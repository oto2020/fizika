<!DOCTYPE html>
<html lang = "ru">
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
                        @include('layouts.left.menu')
                    </div>
                    <!-- КОНЕЦ ЛЕВОГО МЕНЮ!-->

                    <!--Содержимое страницы!-->
                    <div class="col-10">
                        @if($section->url=='main')
                            На главной странице будет что-то другое, кроме списка уроков.
                        @elseif(count($lessons)==0)
                            Уроки пока не добавлены.
                        @else
                            <ul class="list-group list-group-flush">
                                @foreach($lessons as $l)
                                    <li class="list-group-item">
                                        <p>
                                        <h2>{{$l->name}}</h2>

                                        <h6>Дата добавления урока: {{$l->date}} | Автор урока: {{$l->user}}</h6>

                                        {{$l->preview_text}}
                                        <a class="nav-link" href="/{{$section->url}}/{{$l->url}}">Подробнее -></a>
                                        </p>
                                    </li>
                                @endforeach
                            </ul>

                        @endif
                    </div>
                    <!-- КОНЕЦ Содержимого страницы!-->
                </div>
                <footer class="footer container">КФУ им. В.И. Вернадского, Симферополь, 2020</footer>
            </div>
        </div>



    </body>
</html>
