<!DOCTYPE html>
<html lang = "ru">
    <head>
        <title>Добавление урока</title>
        <link rel="icon"href="/storage/img/icon_1.ico" type="image/x-icon">
        <meta charset="utf8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link href="{{asset('/css/bootstrap.css')}}" rel="stylesheet" type="text/css">
        <link href="{{asset('/css/main.css')}}" rel="stylesheet" type="text/css">
        <script src="/storage/ckeditor/ckeditor.js"></script>
    </head>
    <body>
        @include('layouts.messages.message')
        <div class="container">
            <br>
            <h1>Добавление урока</h1>
        </div>

        <!--Содержимое страницы по ДОБАВЛЕНИЮ СТАТЬИ!-->
        <div class="form-row">
            <div class="col-8">
                <form name="test" method="post" action="/{{$sectionURL}}/add_lesson.php">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="user" value="{{Auth::user()->name}}">
                    <div class="form-group col-6">
                        <b>Название урока:</b>
                        <input  name="lesson_name" id="lesson_name" class="form-control" type="text" size="40">
                    </div>
                    <div class="form-group col-6">
                        <b>URL статьи:</b> <a href="#" onclick="translit('lesson_name', 'url')">транслитерировать</a>
                        <input name="url" id="url" class="form-control" type="text">
                    </div>
                    <div class="form-group col-6">
                        <b>Дата добавления:</b>
                        <input name="date" id="date" class="form-control" type="date" value="{{$date}}">
                    </div>
                    <div class="form-group col-6">
                        <b>В какой раздел будет добавлена статья:</b>
                        <br>
                        @foreach($sections as $s)
                            <input type="radio" name="section" value="{{$s->id}}" @if($sectionURL==$s->url) checked @endif>{{$s->name}}
                            <br>
                        @endforeach
                    </div>
                    <div class="form-group col-6">
                        <b>Текст превью:</b>
                        <textarea name="preview_text" class="form-control" cols="40" rows="3"></textarea>
                    </div>
                    <div class="form-group col-12">
                        <b>HTML-содержимое самой статьи:<br></b>
                        <a href="/add_img" target="_blank">Добавить картинку</a>
                        <textarea name="html_content" id="html_content" class="form-control"></textarea>
                        <script>
                            CKEDITOR.replace( 'html_content', {height: 600});
                        </script>
                    </div>
                    <div class="form-group col-6">
                        <input type="submit" value="Отправить">
                    </div>
                </form>
            </div>
        </div>
        <!-- КОНЕЦ Содержимого страницы!-->

        <!-- СКРИПТ ПО ТРАНСЛИТУ!-->
        <script src='/js/transliterURL.js'></script>;
        <!-- КОНЕЦ СКРИПТ ПО ТРАНСЛИТУ!-->

    </body>
</html>
