<!DOCTYPE html>
<html lang = "ru">
<head>
    <title>Добавление теста</title>
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
        <h1>Добавление теста</h1>
        <small>Тест по уроку: <a href="/{{$sectionURL}}/{{$lesson->url}}" target="_blank">{{$lesson->name}}</a>. </small>
        <br>
    </div>

    <!--Содержимое страницы по ДОБАВЛЕНИЮ СТАТЬИ!-->
    <div class="form-row">
        <div class="col-xs-12 col-sm-10" style="padding-left: 25px;">
            <?php $counter = 1;?>
            <form name="test" method="post" action="/{{$lesson->url}}/add_test.php">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="lesson_id" value="{{$lesson->id}}">
                <div class="form-group col-6">
                    <b>Название теста:</b>
                    <input  name="test_name" id="test_name" class="form-control" type="text" size="40">
                </div>
                <div class="form-group col-6">
                    <b>URL теста:</b> <a href="#" onclick="translit('test_name', 'url')">транслитерировать</a>
                    <input name="url" id="url" class="form-control" type="text">
                </div>
                <div class="form-group col-6">
                    <b>Текст превью:</b>
                    <textarea name="preview_text" class="form-control" cols="40" rows="3"></textarea>
                </div>


                <div class="form-group col-6">
                    <b>Заполнение вопросов:</b>
                    <div id="all_questions">
                    <!-- ЖЕЛАЕМЫЙ HTML, КОТОРЫЙ БУДЕТ ГЕНЕРИТЬСЯ JS-ом DOM:
                        <small> Вопрос 1. </small>
                        <input  name="question_1" class="form-control" type="text" size="40">
                        <br>
                        <div class="form-check" id="question_1">
                            <input class="form-check-input" type="radio" name="correct_answer_from_question_1" value="1_1">
                            <input class="form-control" name="answer_1_1" type="text">
                            <br>
                            <input class="form-check-input" type="radio" name="correct_answer_from_question_1" value="1_2">
                            <input class="form-control" name="answer_1_2" type="text">
                            <br>
                            <input class="form-check-input" type="radio" name="correct_answer_from_question_1" value="1_3">
                            <input class="form-control" name="answer_1_3" type="text">
                            <br>
                            <input class="form-check-input" type="radio" name="correct_answer_from_question_1" value="1_4">
                            <input class="form-control" name="answer_1_4"  type="text">
                            <br>
                        </div>


                        <small> Вопрос 2. </small>
                        <input  name="question_2" id="test_name" class="form-control" type="text" size="40">
                        <br>
                        <div class="form-check" id="question_2">
                            <input class="form-check-input" type="radio" name="correct_answer_from_question_2" value="2_1">
                            <input class="form-control" name="answer_2_1" type="text">
                            <br>
                            <input class="form-check-input" type="radio" name="correct_answer_from_question_2" value="2_2">
                            <input class="form-control" name="answer_2_2" type="text">
                            <br>
                            <input class="form-check-input" type="radio" name="correct_answer_from_question_2" value="2_3">
                            <input class="form-control" name="answer_2_3" type="text">
                            <br>
                            <input class="form-check-input" type="radio" name="correct_answer_from_question_2" value="2_4">
                            <input class="form-control" name="answer_2_4"  type="text">
                            <br>
                        </div>
                    !-->
                    </div>
                </div>
                <div class="form-group col-6">
                    <button type="button" id="add_question_button" class="btn btn-success btn-sm btn-block">Добавить вопрос</button>
                </div>
                <div class="form-group col-6">
                    <input type="submit" class="btn btn-outline-success btn-lg btn-block" value="Отправить на сервер">
                </div>
            </form>
        </div>
    </div>
    <!-- КОНЕЦ Содержимого страницы!-->

    <!-- СКРИПТ ПО ТРАНСЛИТУ!-->
    <script src='/js/transliterURL.js'></script>;
    <!-- КОНЕЦ СКРИПТ ПО ТРАНСЛИТУ!-->

    <!-- СКРИПТ ПО ДИНАМИЧЕСКОМУ ФОРМИРОВАНИЮ ВОПРОСОВ И ОТВЕТОВ!-->
    <script>
        // добавляет в контейнер 'all_questions' вопрос под номером i
        function addQuestion (i) {
            document.getElementById('all_questions').innerHTML +=
                '<div id="hidden_div_' + i + '" style="display:none;">' +
                '<small> Вопрос ' + i + '. </small><br>' +
                '<input  name="question_' + i + '" style="width:100%; margin-bottom: 5px; " type="text" size="40">' +
                '<br>' +
                '<div class="form-check" id="question_' + i + '"></div><hr></div>';
        }

        // добавляет в контейнер ('question_' + i) поля для ввода и выбора ответов
        function addAnswers (i, count) {
            for (let j = 1; j <= count; j++) {
                document.getElementById('question_' + i).innerHTML +=
                    '<input type="radio" class="form-check-input"  name="correct_answer_from_question_' + i + '" value="' + i + '_' + j + '">' +
                    '<input type="text" style="width:70%; margin-bottom: 2px" name="answer_' + i + '_' + j + '">' +
                    '<br>'
            }
            // РЕАЛИЗАЦИЯ КНОПКИ "ДОБАВИТЬ ВАРИАНТ ОТВЕТА ПЕРЕНОСИТСЯ НА НЕОПРЕДЕЛЕННЫЙ СРОК"
            // document.getElementById('question_' + i).innerHTML +=
            //     '<button type="button" style="width:70%;" id="add_question_button_"' + i + ' class="btn btn-secondary btn-sm">Добавить ответ</button>';
            // document.getElementById("add_question_button_" + 1).onclick = function() {
            //     let countNoHiddenInputs = 0;
            //     for(let j=0;j<10)...
            // }
        }
        // создадим 50 невидимых вопросов
        for (let i=1; i<50; i++) {
            addQuestion (i);
            addAnswers (i, 4); // 4  вариантов ответа
        }

        // делает див ("hidden_div_" + i) видимым
        function showHiddenDiv (i) {
            document.getElementById("hidden_div_" + i).style.display = "inline";
        }

        let counter = 1;
        // сделаем первые 3 дива видимыми
        for(counter; counter<=3;counter++) {
            showHiddenDiv(counter);
        }

        // навесим обработчик на кнопку "Добавить вопрос"
        document.getElementById('add_question_button').onclick=function(){
            showHiddenDiv(counter);
            counter++;
        }
    </script>
    <!-- КОНЕЦ СКРИПТ ПО ДИНАМИЧЕСКОМУ ФОРМИРОВАНИЮ ВОПРОСОВ И ОТВЕТОВ!-->

</body>
</html>
