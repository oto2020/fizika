<!DOCTYPE html>
<html lang = "ru">
    <head>
        <title>Тест - {{$test->name}}</title>
        <link rel="icon"href="/storage/img/icon_1.ico" type="image/x-icon">
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
                <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
                    <ul class="navbar-nav mr-auto">
                        @foreach($sections as $s)
                            <?php if($sectionURL==$s->url) $className = $s->name; // сохраним название класса, в котором находимся?>
                            <li class="nav-item {{$sectionURL==$s->url?'active':''}}">
                                <a class="nav-link" href="/{{$s->url}}">{{$s->name}} </a>
                            </li>
                        @endforeach
                    </ul>
                    <form class="form-inline my-2 my-lg-0">
                        <input class="form-control mr-sm-2" type="search" placeholder="Поиск" aria-label="Search">
                        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Поиск</button>
                    </form>
                </nav>
                <!--ВСЁ, ЧТО ПОСЛЕ ВЕРХНЕГО МЕНЮ!-->
                <div class="row">
                    <!-- ЛЕВОЕ МЕНЮ !-->
                    <div class="col-2">
                        <br>
                        @include('layouts.left.auth')
                        <!--Навигационный бар в левом меню!-->

                    </div>
                    <!-- КОНЕЦ ЛЕВОГО МЕНЮ!-->

                    <!--Содержимое страницы!-->
                    <div class="col-10">
                        <p>
                            <h1>
                                {{$test->name}}
                            </h1>
                            @if ($role!==null && $role->name == 'Администратор')
                                <a href="/{{$sectionURL}}/{{$lesson->url}}/{{$test->url}}/edit_test">
                                    [редактировать]
                                </a>
                                <a href="/{{$sectionURL}}/{{$lesson->url}}/{{$test->url}}/mark_as_deleted"
                                   onclick="return confirm ('Помещаем тест в удалённые?')"
                                >
                                    [скрыть/поместить в удалённые]
                                </a>
                            @endif
                            <h6>
                                <small>Тест по уроку: <a href="/{{$sectionURL}}/{{$lesson->url}}" target="_blank">{{$lesson->name}}</a>. </small>
                                <h6>Автор теста: {{$test->user}}</h6>
                                {{$test->preview_text}}
                            </h6>
                        </p>
                        <!--Если результат существует, покажем его, но тест проходить не будем!!-->
                        @if ($testResult !== null)
                            <br>
                            <div class="row ">
                                <div class="col-6 border border-secondary" style="">
                                    <h1 style="">Ваш результат: {{$testResult->point}} %</h1>
                                </div>
                                <div class="col-6 border border-secondary" style="">
                                    <h1 style="">Дата: {{$testResult->datetime}}</h1>
                                </div>
                            </div>
                        @endif
                        @if ($testResult == null || $role->name == 'Администратор') <!-- админы могут видеть тест даже если его уже проходили!-->
                            <form name="test" method="post" action="/{{$test->url}}/verificate_test.php">
                                <div id="question_window" style="padding-left: 5px">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <?php $counter = 0; ?>
                                    @foreach($questions as $question => $answers)
                                        <?php $counter++; ?>
                                            <div id="question_{{$counter}}">
                                                <br>
                                                    <small> Вопрос {{$counter}}. </small>
                                                    <h5>{{$question}}</h5>
                                                <?php $answer_counter = 0;?>
                                                @foreach($answers as $answer_id => $answer)
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" {{$answer_counter==0?'checked':''}} name="question_{{$counter}}" id="answer_{{$answer_id}}" value="{{$answer['answer']}}">
                                                        <label class="form-check-label" for="answer_{{$answer_id}}">
                                                            {{$answer['answer']}}
                                                        </label>
                                                    </div>
                                                    <?php $answer_counter++;?>
                                                @endforeach
                                            </div>
                                    @endforeach

                                </div>
                                <div id="buttons">
                                    <button type="button" id="prev_button" class="btn btn-outline-primary" style="display:none;">Предыдущий вопрос</button>
                                    <button type="button" id="next_button" class="btn btn-outline-primary" style="display:none;">Следующий вопрос</button>
                                    <button type="submit" id="submit_button" class="btn btn-outline" style="float:right; display:block;">Отправить результат</button>
                                </div>
                            </form>
                            <br>

                            <!--Скрипт по переключению контента!-->
                            <script>
                                // получаем div, внутри которого лежат все тесты + кнопки
                                let questionWindow = document.getElementById('question_window');
                                let prevButton = document.getElementById('prev_button');
                                let nextButton = document.getElementById('next_button');
                                let submitButton = document.getElementById('submit_button');
                                submitButton.disabled=true;
                                // фиксируем высоту в 250 пикселей
                                questionWindow.style.height = "270px";
                                // всего вопросов
                                let count = {{$counter}};

                                // отобразим только первый вопрос
                                let showedQuestionID = 1;
                                function refreshQuestion() {
                                    for (let i=1; i<=count; i++) {
                                        document.getElementById('question_' + i).style.display="none";
                                    }
                                    document.getElementById('question_' + showedQuestionID).style.display="inline";
                                }
                                refreshQuestion();

                                // сделаем кнопки "предыдущий вопрос" и "следующий вопрос" видимыми
                                prevButton.style.display="inline";
                                nextButton.style.display="inline";
                                // добавим обработчик события по нажатию на кнопки
                                prevButton.onclick = function(e) {
                                    nextButton.disabled = false;
                                    if (showedQuestionID > 1) {
                                        showedQuestionID--;
                                        refreshQuestion();
                                    }
                                    if (showedQuestionID === 1) {
                                        prevButton.disabled = true;
                                    }
                                };
                                nextButton.onclick = function(e) {
                                    prevButton.disabled = false;
                                    if (showedQuestionID < count) {
                                        showedQuestionID++;
                                        refreshQuestion();
                                    }
                                    if (showedQuestionID === count) {
                                        nextButton.disabled = true;
                                        submitButton.disabled = false;
                                        submitButton.classList.add('btn-outline-danger');
                                    }
                                };

                                console.log({{$counter}});
                            </script>
                        @endif

                    <!-- КОНЕЦ Содержимого страницы!-->
                    </div>
                </div>
                <footer class="footer container">КФУ им. В.И. Вернадского, Симферополь, 2020</footer>
            </div>
        </div>
    </body>
</html>
