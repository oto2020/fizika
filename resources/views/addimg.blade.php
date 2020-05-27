<!DOCTYPE html>
<html lang = "ru">
<head>
    <title>Загрузка картинки</title>
    <link rel="icon"href="/storage/img/icon_1.ico" type="image/x-icon">
    <meta charset="utf8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="{{asset('/css/bootstrap.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('/css/main.css')}}" rel="stylesheet" type="text/css">
</head>
<body>

@include('layouts.messages.message')
<div class="container">
    <h1>Загрузка картинки</h1>
</div>


<!--Содержимое страницы по ДОБАВЛЕНИЮ СТАТЬИ!-->
<div class="col-6" style="padding-left:3%">
    <form name="test" method="post" action="/add_img.php" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div id="fileInputContainer">
            <div class="form-group">
                <b>Название файлов (префикс):</b>
                <input name="img_future_name" class="form-control" type="text" value="{{$imgName}}">
                <input type="file"  name="images[]" style="margin-top:4px;" accept=".jpg, .jpeg, .png, .bmp .gif" multiple>
            </div>
        </div>

{{--        <button type="button" id="add_field_button" class="btn btn-success btn-sm btn-block">Добавить ещё один файл</button>--}}
{{--        <br>--}}
        <div class="row alert alert-secondary" role="alert">
            <div class="col-8">
                (Принимаются файлы с разрешением <i>.jpg, .jpeg, .png, .bmp .gif</i>)
            </div>
            <div class="col-4">
                <input  class="btn btn-outline-success btn-lg btn-block" style="float:right;" type="submit" value="Загрузить">
            </div>
        </div>
        <br>
    </form>
</div>

<!-- КОНЕЦ Содержимого страницы!-->


<!-- Скрипт по добавлению полей для загрузки картинки!-->
{{--<script>--}}
{{--    // добавляет в fileInputContainer скрытое поле для добавления файла--}}
{{--    function createFileInput(i) {--}}
{{--        document.getElementById("fileInputContainer").innerHTML+='' +--}}
{{--            '<div class="form-group" id="hidden_div_'+i+'" style="display:none">' +--}}
{{--            '<b>Файл №'+i+':</b><input name="img_future_name_'+i+'" class="form-control" type="text" value="{{$imgName}}_'+i+'">' +--}}
{{--            '<input type="file" name="img_file_'+i+'" style="margin-top:4px;" accept=".jpg, .jpeg, .png, .bmp .gif">' +--}}
{{--            '</div>';--}}
{{--    }--}}
{{--    // делает скрытое явным--}}
{{--    function showHiddenDiv (i) {--}}
{{--        document.getElementById("hidden_div_" + i).style.display = "block";--}}
{{--    }--}}

{{--    //добавим 20 скрытых полей для загрузки файла--}}
{{--    for(let i=1; i<=20; i++) createFileInput(i);--}}

{{--    //сделаем 2 поля видимыми--}}
{{--    let i=1;--}}
{{--    for(i; i<=2; i++) showHiddenDiv(i);--}}

{{--    // назначим кнопке add_field_button обработчик события клика--}}
{{--    document.getElementById('add_field_button').onclick = function() {showHiddenDiv(i); i++}--}}
{{--</script>--}}

<!-- СКРИПТ ПО АВТООБНОВЛЕНИЮ HTML-КОНТЕНТА!-->
<script>
    // получим ссылки на поля textarea
    let htmlContent = document.getElementById("html_content");
    let htmlContentView = document.getElementById("html_content_view");
    htmlContent.onkeyup = function(e){htmlContentView.innerHTML = htmlContent.value;};
</script>
<!-- КОНЕЦ СКРИПТ ПО АВТООБНОВЛЕНИЮ HTML-КОНТЕНТА!-->


<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>




</body>
</html>
