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
    <h1>Загрузка excel-файла</h1>
</div>


<!--Содержимое страницы по ДОБАВЛЕНИЮ СТАТЬИ!-->
<div class="col-6" style="padding-left:3%">
    <form name="test" method="post" action="openexcel" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div id="fileInputContainer">
            <div class="form-group">
                <input type="file"  name="file" style="margin-top:4px;" accept=".xlsx">
            </div>
        </div>

        {{--        <button type="button" id="add_field_button" class="btn btn-success btn-sm btn-block">Добавить ещё один файл</button>--}}
        {{--        <br>--}}
        <div class="row alert alert-secondary" role="alert">
            <div class="col-8">
                (Принимается файл с разрешением <i>.xlsx</i>)
            </div>
            <div class="col-4">
                <input  class="btn btn-outline-success btn-lg btn-block" style="float:right;" type="submit" value="Загрузить">
            </div>
        </div>
        <br>
    </form>
</div>

<!-- КОНЕЦ Содержимого страницы!-->



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
