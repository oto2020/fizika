<!DOCTYPE html>
<html lang="ru">
<head>
    <title>{{$role->name}} [Личный кабинет]</title>
    <link rel="icon" href="/storage/img/icon_1.ico" type="image/x-icon">
    <meta charset="utf8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="{{asset('/css/bootstrap.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('/css/main.css')}}" rel="stylesheet" type="text/css">
</head>
<body>
<div class="wrapper" >
    <!--костыль, чтобы экран не фокусировался на табе при клике!-->
    <div id="users"></div>
    <div id="map"></div>
    <div id="test_results"></div>
    <div id="user_results"></div>
    <div id="deleted"></div>

    <style>
        /* Когда ширина окна до 1366 пикселей, ширина content будет 100% */
        @media screen and (max-width: 1366px){
            .content {
                width: 100%;
            }
        }
    </style>
    <div class="container content">
        <br>
        <!--ВЕРХНЕЕ МЕНЮ!-->
    @include('layouts.top.menu')
    <!--ВСЁ, ЧТО ПОСЛЕ ВЕРХНЕГО МЕНЮ!-->
        <div class="row">
            <!--Содержимое страницы!-->
            <div class="col-12">
                <p>
                <h1>Личный кабинет</h1>
                </p>
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link" onclick="showTabContent(0)" name="tab" href="#users" id="users" >Пользователи</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" onclick="showTabContent(1)" name="tab" href="#map" id="map" >Карта сайта</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" onclick="showTabContent(2)" name="tab" href="#test_results" id="test_results">Результаты тестов</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" onclick="showTabContent(3)" name="tab" href="#user_results" id="user_results">Результаты учеников</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" onclick="showTabContent(4)" name="tab" href="#deleted" id="deleted" >Удаленные уроки/тесты</a>
                    </li>
                </ul>

                <div class="tab-content" style="min-height: 2048px">
<!--ВКЛАДКА ПОЛЬЗОВАТЕЛИ!-->
                    <div name="tab_content" style="display:none">
                        @include('layouts.messages.message')
                        <form name="test" method="post" action="/edit_users.php">
                        @csrf
                        @foreach ($users as $class_name => $class_users)
                            <h3>
                                {{$class_name}}:
                            </h3>
                            <table class="table table-striped table-inverse">
    <!--Заголовок таблицы с пользователями !-->
                            <thead class="">
                                <tr>
                                    <th>#</th>
                                    <th>ФИО</th>
                                    <th>E-mail</th>
                                    <th>Класс</th>
                                    <th>Задать пароль</th>
                                    <th>Изменить роль</th>
                                    <th>Удалить</th>
                                </tr>
                            </thead>
    <!--Тело таблицы с пользователями !-->
                            <tbody>
                                @foreach ($class_users as $class_user)
                                <tr>
                                    <div class="form-group">
                                        <td name="id|{{$class_user->id}}">{{$class_user->id}} </td>
                                    </div>
                                    <div class="form-group">
                                        <td><input name="name|{{$class_user->id}}" value="{{$class_user->name}}" type="text" class="form-control"></td>
                                    </div>
                                    <div class="form-group">
                                        <td><input name="email|{{$class_user->id}}" value="{{$class_user->email}}" type="email" class="form-control"></td>
                                    </div>
                                    <div class="form-group">
                                    <td>
        <!--Выбор КЛАССА !-->
                                        <select name="class_name|{{$class_user->id}}" class="browser-default custom-select" class="form-control" style="width:110px">
                                            <option {{($class_name == 'Учителя') ? 'selected' : ''}} >
                                                Учителя
                                            </option>
                                            @foreach ($sections as $section)
{{--                                                @if ($section->name!='Главная')--}}
                                                @if (preg_match('/^([0-9]){1,2}(.){0,}/', $section->name))
                                                    <option {{($class_name == $section->name) ? 'selected' : ''}}>
                                                        {{$section->name}}
                                                    </option>
                                                @endif
                                            @endforeach

                                        </select>
                                    </td>
                                    </div>
                                    <div class="form-group">
                                        <td><input name="password|{{$class_user->id}}" type="password" style="width:110px" class="form-control"></td>
                                    </div>
                                    <div class="form-group">
                                        <td>
        <!--Выбор РОЛИ !-->
                                            <select name="user_role_id|{{$class_user->id}}" class="form-control">
                                                @foreach ($roles as $role)
                                                    <option {{($class_user->user_role_id == $role->id) ? 'selected' : ''}} value="{{$role->id}}">
                                                        {{$role->name}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </div>
                                    <div class="form-group">
                                        <td>
                                            <input name="delete_user|{{$class_user->id}}" id="delete_user|{{$class_user->id}}" type="checkbox" style="display:inline; width:30px; height:30px" class="form-control">
    {{--                                        <button onclick="let chbx = document.getElementById('delete_user|{{$class_user->id}}');chbx.style.display='inline'; chbx.checked=true; this.style.display='none'" class="btn btn-outline-danger">Удалить</button>--}}
                                        </td>
                                    </div>
                                <tr>
                                @endforeach
                            </tbody>
                            </table>
                        @endforeach
                        <input type="submit" class="btn btn-success btn-lg btn-block" value="Применить изменения">
                        </form>
                    </div>
<!--ВКЛАДКА КАРТА САЙТА!-->
                    <div name="tab_content" style="display:none">
                        @include('layouts.messages.message')
                        <?php //dump($lessons);?>
                        @foreach ($lessons as $sectionName => $class_lessons)
{{--                            @if ($sectionName == 'Главная')--}}
                            @if (!preg_match('/^([0-9]){1,2}(.){0,}/', $sectionName))
                                @continue
                            @endif
                            <h3>{{$sectionName}}. Уроки:</h3>
                            <?php $counter = 1;?>
                            <style>
                                .marked_as_deleted {
                                    color: rgb(140, 140, 140);
                                }
                                .marked_as_showed {
                                    color: rgb(0, 0, 0);
                                }
                            </style>
                            <table class="table">
                                <thead>
                                    <tr > <!--style="background-color: rgb(100,100,100); color:rgb(255,255,255)"!-->
                                        <th style="width:120px">
                                            Номер
                                        </th>
                                        <th>
                                            Название урока/теста
                                        </th>
                                        <th style="width:235px">
                                            Редактировать
                                        </th>
                                        <th style="width:250px">
                                            Скрыть/удалить
                                        </th>
                                        <th>
                                            Добавить тест
                                        </th>
                                    </tr>
                                </thead>
    <!--Список уроков !-->
                                <tbody>
                                    @foreach ($class_lessons as $lesson)
                                        <?php $markAsDeleted = ($lesson['is_deleted'] != null);?>

                                            <tr class="{{$markAsDeleted?'marked_as_deleted':''}}">
{{--                                                style="{{($lesson['is_deleted'] != null)--}}
{{--                                                    ?--}}
{{--                                                    'background-color: rgba(255, 0, 0, 0.1)'--}}
{{--                                                    :--}}
{{--                                                    'background-color: rgba(0, 255, 0, 0.1)'--}}
{{--                                                    }}--}}
{{--                                                    "--}}
                                                <td  style="font-size:26px; float:right">
                                                    Урок {{$counter}}.
                                                </td>
                                                <td>
                                                    <a href="{{$lesson['full_url']}}"
                                                        target="_blank"
                                                        class="{{$markAsDeleted?'marked_as_deleted':'marked_as_showed'}}"
                                                        style="font-size:26px"
                                                    >
                                                        {{$lesson['name']}}
                                                    </a>
                                                </td>

                                                <td>
                                                    @if ($lesson['is_deleted'] == null)
                                                        <a href="{{$lesson['full_url'].'/edit_lesson'}}" target="_blank" class="btn btn-outline-primary btn-sm">
                                                            Редактировать
                                                        </a>
                                                    @else
                                                        <a href="{{$lesson['full_url'].'/edit_lesson'}}" target="_blank" class="btn btn-outline-secondary btn-sm">
                                                            Редактировать
                                                        </a>
                                                        [урок скрыт]
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($lesson['is_deleted'] == null)
                                                        <a href="{{$lesson['full_url'].'/mark_as_deleted'}}"
                                                           onclick="return confirm ('Внимание: перед удалением урока необходимо удалить все его тесты! Отправляем урок в удалённые?')"
                                                           class="btn btn-outline-danger btn-sm"
                                                           style="width:215px"
                                                        >
                                                            Скрыть
                                                        </a>
                                                    @else
                                                        <a type="button" href="{{$lesson['full_url'].'/restore_lesson'}}" class="btn btn-outline-success btn-sm">
                                                            Восстановить
                                                        </a>
                                                        <a type="button"
                                                            href="{{$lesson['full_url'].'/delete_lesson'}}"
                                                            class="btn btn-outline-danger btn-sm"
                                                            onclick="return confirm ('Убедитесь что за уроком не закреплены тесты. Точно удалить урок навсегда?')"
                                                            style="width:105px"
                                                        >
                                                            Удалить
                                                        </a>
                                                    @endif
                                                </td>

                                                <td>
                                                    @if ($lesson['is_deleted'] == null)
                                                        <a href="{{$lesson['full_url'].'/add_test'}}"
                                                           target="_blank"
                                                           class="btn btn-outline-success btn-sm"
                                                        >
                                                            Добавить тест
                                                        </a>
                                                    @else
                                                        [урок скрыт]
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
        <!--Список тестов!-->
                                            <?php $test_counter =1;?>
                                            @foreach ($lesson['tests'] as $test)
                                                <?php $markAsDeleted = ($lesson['is_deleted'] != null || $test->is_deleted != null);?>
                                                <tr  class="{{$markAsDeleted?'marked_as_deleted':''}}">
                                                    <td style="float:right">
                                                        Тест {{$test_counter}}.
                                                    </td>
                                                    <td>
                                                        <a href="{{$test->full_url}}"
                                                           target="_blank"
                                                           class="{{$markAsDeleted?'marked_as_deleted':'marked_as_showed'}}"
                                                        >
                                                            {{$test->name}}
                                                        </a>
                                                    </td>
                                                    <td>
                                                        @if($test->is_deleted == null)
                                                            <a href="{{$test->full_url.'/edit_test'}}" target="_blank" class="btn btn-outline-primary btn-sm">
                                                                Редактировать
                                                            </a>
                                                        @else
                                                            <a href="{{$test->full_url.'/edit_test'}}" target="_blank" class="btn btn-outline-secondary btn-sm">
                                                                Редактировать
                                                            </a>
                                                            [тест скрыт]
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($test->is_deleted == null)
                                                            <a href="{{$test->full_url.'/mark_as_deleted'}}"
                                                               onclick="return confirm ('Отправляем тест в удалённые?')"
                                                               class="btn btn-outline-danger btn-sm"
                                                               style="width:215px"
                                                            >
                                                                Скрыть
                                                            </a>
                                                        @else

                                                            <a type="button" href="{{$test->full_url . '/restore_test'}}" class="btn btn-outline-success btn-sm">
                                                                Восстановить
                                                            </a>
                                                            <a type="button"
                                                               href="{{$test->full_url . '/delete_test'}}"
                                                               class="btn btn-outline-danger btn-sm"
                                                               onclick="return confirm ('Тест и все результаты его прохождения другими пользователями будут удалены. Точно удалить тест навсегда?')"
                                                               style="width:105px"
                                                            >
                                                                Удалить
                                                            </a>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <?php $test_counter++;?>
                                            @endforeach


{{--                                            <!--Список тестов !-->--}}
{{--                                            <ul class="list-group">--}}
{{--                                                @if (count($lesson['tests']) > 0)--}}
{{--                                                    Тесты по уроку:--}}
{{--                                                    <?php $test_counter =1;?>--}}
{{--                                                    @foreach ($lesson['tests'] as $test)--}}
{{--                                                        <li class="list-group-item">--}}

{{--                                                            <div class="row"--}}
{{--                                                                 style="{{($test->is_deleted != null)--}}
{{--                                                                                                                                    ?--}}
{{--                                                                                                                                    'padding: 0.5rem 1.25rem;background-color:#f0d7da;border-radius:0.25rem;'--}}
{{--                                                                                                                                    :--}}
{{--                                                                                                                                    'padding: 0.5rem 1.25rem;background-color:#d7f0da;border-radius:0.25rem;'--}}
{{--                                                                                                                                    }}"--}}
{{--                                                            >--}}
{{--                                                                <div class="col-6">--}}
{{--                                                                    <small>#{{$test->id}}</small>--}}
{{--                                                                    <a href="{{$test->full_url}}" target="_blank">--}}
{{--                                                                        Тест {{$test_counter}}. {{$test->name}}--}}
{{--                                                                    </a>--}}
{{--                                                                </div>--}}
{{--                                                                <div class="col-6">--}}
{{--                                                                    @if($test->is_deleted == null)--}}
{{--                                                                        <a href="{{$test->full_url.'/edit_test'}}"--}}
{{--                                                                           target="_blank"--}}
{{--                                                                           class="btn btn-outline-primary btn-sm"--}}
{{--                                                                        >--}}
{{--                                                                            [редактировать]--}}
{{--                                                                        </a>--}}
{{--                                                                        <a href="{{$test->full_url.'/mark_as_deleted'}}"--}}
{{--                                                                           onclick="return confirm ('Отправляем тест в удалённые?')"--}}
{{--                                                                           class="btn btn-outline-danger btn-sm"--}}
{{--                                                                        >--}}
{{--                                                                            [скрыть/поместить в удалённые]--}}
{{--                                                                        </a>--}}
{{--                                                                    @else--}}

{{--                                                                        <a type="button" href="{{$test->full_url . '/restore_test'}}" class="btn btn-outline-success btn-sm">--}}
{{--                                                                            Восстановить--}}
{{--                                                                        </a>--}}
{{--                                                                        <a type="button" href="{{$test->full_url . '/delete_test'}}" class="btn btn-outline-danger btn-sm" onclick="return confirm ('Точно удалить навсегда?')">--}}
{{--                                                                            Уничтожить--}}
{{--                                                                        </a>--}}
{{--                                                                    @endif--}}
{{--                                                                </div>--}}
{{--                                                            </div>--}}
{{--                                                        </li>--}}
{{--                                                        <?php $test_counter++;?>--}}
{{--                                                    @endforeach--}}
{{--                                                @endif--}}
{{--                                            </ul>--}}


                                        <?php $counter++;?>
                                    @endforeach
                                </tbody>
                            </table>
                            <a href="{{'/'.$class_lessons[0]['section_url'].'/add_lesson'}}"
                               class="btn btn-outline-success btn-sm"
                            >
                                [добавить урок]
                            </a>
                            <br><br>
                        @endforeach
                    </div>
{{--                    <div name="tab_content" style="display:none">--}}
{{--                        @include('layouts.messages.message')--}}
{{--                        <?php //dump($lessons);?>--}}
{{--                        @foreach ($lessons as $sectionName => $class_lessons)--}}
{{--                            @if ($sectionName == 'Главная')--}}
{{--                                @continue--}}
{{--                            @endif--}}
{{--                            <h3>{{$sectionName}}. Уроки:</h3>--}}
{{--                            <?php $counter = 1;?>--}}
{{--                            @foreach ($class_lessons as $lesson)--}}
{{--                                <ul class="list-group">--}}
{{--                                    <li class="list-group-item">--}}
{{--        <!--Список уроков !-->--}}
{{--                                        <div class="row"--}}
{{--                                             style="{{($lesson['is_deleted'] != null)--}}
{{--                                             ?--}}
{{--                                             'padding: 0.5rem 1.25rem;background-color:#f0d7da;border-radius:0.25rem;'--}}
{{--                                             :--}}
{{--                                             'padding: 0.5rem 1.25rem;background-color:#d7f0da;border-radius:0.25rem;'--}}
{{--                                             }}"--}}
{{--                                        >--}}
{{--                                            <div class="col-6">--}}
{{--                                                <small>#{{$lesson['id']}}</small>--}}
{{--                                                <a href="{{$lesson['full_url']}}" target="_blank">--}}
{{--                                                    Урок {{$counter}}. {{$lesson['name']}}--}}
{{--                                                </a>--}}
{{--                                            </div>--}}
{{--                                            <div class="col-6">--}}
{{--                                                @if ($lesson['is_deleted'] == null)--}}
{{--                                                    <a href="{{$lesson['full_url'].'/edit_lesson'}}"--}}
{{--                                                       target="_blank"--}}
{{--                                                       class="btn btn-outline-primary btn-sm"--}}
{{--                                                    >--}}
{{--                                                        [редактировать]--}}
{{--                                                    </a>--}}
{{--                                                    <a href="{{$lesson['full_url'].'/mark_as_deleted'}}"--}}
{{--                                                       onclick="return confirm ('Внимание: перед удалением урока необходимо удалить все его тесты! Отправляем урок в удалённые?')"--}}
{{--                                                       class="btn btn-outline-danger btn-sm"--}}
{{--                                                    >--}}
{{--                                                        [скрыть/поместить в удалённые]--}}
{{--                                                    </a>--}}
{{--                                                    <a href="{{$lesson['full_url'].'/add_test'}}"--}}
{{--                                                       target="_blank"--}}
{{--                                                       class="btn btn-outline-primary btn-sm"--}}
{{--                                                    >--}}
{{--                                                        [добавить тест]--}}
{{--                                                    </a>--}}
{{--                                                @else--}}

{{--                                                    <a type="button" href="{{$lesson['full_url'].'/restore_lesson'}}" class="btn btn-outline-success btn-sm">--}}
{{--                                                        Восстановить--}}
{{--                                                    </a>--}}
{{--                                                    <a type="button" href="{{$lesson['full_url'].'/delete_lesson'}}" class="btn btn-outline-danger btn-sm" onclick="return confirm ('Точно удалить навсегда?')">--}}
{{--                                                        Уничтожить--}}
{{--                                                    </a>--}}
{{--                                                @endif--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                        <ul class="list-group">--}}
{{--                                            @if (count($lesson['tests']) > 0)--}}
{{--                                                Тесты по уроку:--}}
{{--                                                <?php $test_counter =1;?>--}}
{{--                                                @foreach ($lesson['tests'] as $test)--}}
{{--                                                    <li class="list-group-item">--}}
{{--        <!--Список тестов !-->--}}
{{--                                                        <div class="row"--}}
{{--                                                            style="{{($test->is_deleted != null)--}}
{{--                                                                ?--}}
{{--                                                                'padding: 0.5rem 1.25rem;background-color:#f0d7da;border-radius:0.25rem;'--}}
{{--                                                                :--}}
{{--                                                                'padding: 0.5rem 1.25rem;background-color:#d7f0da;border-radius:0.25rem;'--}}
{{--                                                                }}"--}}
{{--                                                        >--}}
{{--                                                            <div class="col-6">--}}
{{--                                                                <small>#{{$test->id}}</small>--}}
{{--                                                                <a href="{{$test->full_url}}" target="_blank">--}}
{{--                                                                    Тест {{$test_counter}}. {{$test->name}}--}}
{{--                                                                </a>--}}
{{--                                                            </div>--}}
{{--                                                            <div class="col-6">--}}
{{--                                                                @if($test->is_deleted == null)--}}
{{--                                                                    <a href="{{$test->full_url.'/edit_test'}}"--}}
{{--                                                                       target="_blank"--}}
{{--                                                                       class="btn btn-outline-primary btn-sm"--}}
{{--                                                                    >--}}
{{--                                                                        [редактировать]--}}
{{--                                                                    </a>--}}
{{--                                                                    <a href="{{$test->full_url.'/mark_as_deleted'}}"--}}
{{--                                                                       onclick="return confirm ('Отправляем тест в удалённые?')"--}}
{{--                                                                       class="btn btn-outline-danger btn-sm"--}}
{{--                                                                    >--}}
{{--                                                                        [скрыть/поместить в удалённые]--}}
{{--                                                                    </a>--}}
{{--                                                                @else--}}

{{--                                                                    <a type="button" href="{{$test->full_url . '/restore_test'}}" class="btn btn-outline-success btn-sm">--}}
{{--                                                                        Восстановить--}}
{{--                                                                    </a>--}}
{{--                                                                    <a type="button" href="{{$test->full_url . '/delete_test'}}" class="btn btn-outline-danger btn-sm" onclick="return confirm ('Точно удалить навсегда?')">--}}
{{--                                                                        Уничтожить--}}
{{--                                                                    </a>--}}
{{--                                                                @endif--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}
{{--                                                    </li>--}}
{{--                                                    <?php $test_counter++;?>--}}
{{--                                                @endforeach--}}
{{--                                            @endif--}}
{{--                                        </ul>--}}
{{--                                    </li>--}}
{{--                                </ul>--}}
{{--                                <?php $counter++;?>--}}
{{--                            @endforeach--}}
{{--                            <a href="{{'/'.$class_lessons[0]['section_url'].'/add_lesson'}}"--}}
{{--                               class="btn btn-outline-primary btn-sm"--}}
{{--                            >--}}
{{--                                [добавить урок]--}}
{{--                            </a>--}}
{{--                            <br><br>--}}
{{--                        @endforeach--}}
{{--                    </div>--}}

<!--ВКЛАДКА РЕЗУЛЬТАТЫ ТЕСТОВ (ТЕСТЫ)!-->
                    <div name="tab_content" style="display:none">
                        @include('layouts.messages.message')
                        <?php// dump($testResultsByTests);?>
                        <br>
                        @foreach($testResultsByTests as $testName => $results)
                            <h3>
{{--                                <small>#{{$results[0]->test_id}}</small>--}}
                                <a href="{{$results[0]->test_full_url}}"
                                   target="_blank"
                                   style="color:#000000"
                                >
                                    {{$testName}}
                                </a>
                            </h3>
                            @if (count($results) > 0)
                                <ul>
                                    <table class="table table-striped table-inverse">
                                        <thead>
{{--                                            <th>#</th>--}}
                                            <th style="width:300px">Имя пользователя</th>
                                            <th>Результат</th>
                                            <th>Дата прохождения</th>
                                            <th>Удалить результат</th>
                                            <th style="width:500px">Подробнее</th>
                                        </thead>
                                        <tbody>
                                        @foreach ($results as $result)
                                            <tr style="background-color: rgba(0, 0, 0, 0.05);">
{{--                                                <td>--}}
{{--                                                    {{$result->user_id}}--}}
{{--                                                </td>--}}
                                                <td>
                                                    {{$result->user_name}}
                                                </td>
                                                <td>
                                                    {{$result->point}} %
                                                </td>
                                                <td>
                                                    {{$result->datetime}}
                                                </td>
                                                <td>
                                                    <form action="/delete_test_result.php" method="post">
                                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                        <input type="hidden" name="id" value="{{$result->result_id}}">
                                                        <input type="hidden" name="redirect_to" value="test_results">
                                                        <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm ('Точно очистить?')">Очистить</button>
                                                    </form>
                                                </td>
                                                <td colspan="4">
                                                    <button onclick="showHideResultDetailsByTestTab({{$result->result_id}})" class="btn btn-secondary btn-sm">Скрыть/показать результаты</button>
                                                    <div id="details_test_{{$result->result_id}}" style="display:none;">
                                                        <br>
                                                        <?php $details = json_decode($result->details); ?>
                                                        <ul>
                                                            @foreach ($details as $questionName => $fields)
                                                                <li>
                                                                    <?php $counter = 0; ?>
                                                                    <b>{{$questionName}}</b><br>
                                                                    @foreach ($fields as $field)
                                                                        @if ($counter === 0) - Результат:           <b>{{$field}}</b> <br>
                                                                        @elseif ($counter === 1) - ответ участника: <i>{{$field}}</i> <br>
                                                                        @elseif ($counter === 2) - верный  ответ:   <i>{{$field}}</i> <br>
                                                                        @endif
                                                                        <?php $counter++; ?>
                                                                    @endforeach
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>

                                        @endforeach
                                        </tbody>
                                    </table>
                                </ul>
                            @else
                                Ни одного теста не пройдено.
                            @endif
                        <br>
                        @endforeach
                    </div>



<!--ВКЛАДКА РЕЗУЛЬТАТЫ ТЕСТОВ (УЧЕНИКИ)!-->
                    <div name="tab_content" style="display:none">
                        @include('layouts.messages.message')
                        <?php// dump($testResultsByTests);?>
                        <br>
                        @foreach($testResultsByUsers as $userName => $results)
                            <h3>
                                {{$userName}}
                            </h3>
                            @if (count($results) > 0)
                                <ul>
                                    <table class="table table-striped table-inverse">
                                        <thead>
{{--                                        <th>#</th>--}}
                                        <th style="width:300px">Название теста</th>
                                        <th>Результат</th>
                                        <th>Дата прохождения</th>
                                        <th>Удалить результат</th>
                                        <th style="width:500px">Подробнее</th>
                                        </thead>
                                        <tbody>
                                        @foreach ($results as $result)
                                            <tr style="background-color: rgba(0, 0, 0, 0.05);">
{{--                                                <td>--}}
{{--                                                    {{$result->user_id}}--}}
{{--                                                </td>--}}
                                                <td>
                                                    <a href="{{$result->test_full_url}}"
                                                       target="_blank"
                                                       style="color:#000000"
                                                    >
                                                        {{$result->test_name}}
                                                    </a>
                                                </td>
                                                <td>
                                                    {{$result->point}} %
                                                </td>
                                                <td>
                                                    {{$result->datetime}}
                                                </td>
                                                <td>
                                                    <form action="/delete_test_result.php" method="post">
                                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                        <input type="hidden" name="id" value="{{$result->result_id}}">
                                                        <input type="hidden" name="redirect_to" value="user_results">
                                                        <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm ('Точно очистить?')">Очистить</button>
                                                    </form>
                                                </td>
                                                <td colspan="4">
                                                    <button onclick="showHideResultDetailsByUserTab({{$result->result_id}})" class="btn btn-secondary btn-sm">Скрыть/показать результаты</button>
                                                    <div id="details_user_{{$result->result_id}}" style="display:none;">
                                                        <br>
                                                        <?php $details = json_decode($result->details); ?>
                                                        <ul>
                                                            @foreach ($details as $questionName => $fields)
                                                                <li>
                                                                    <?php $counter = 0; ?>
                                                                    <b>{{$questionName}}</b><br>
                                                                    @foreach ($fields as $field)
                                                                        @if ($counter === 0) - Результат:           <b>{{$field}}</b> <br>
                                                                        @elseif ($counter === 1) - ответ участника: <i>{{$field}}</i> <br>
                                                                        @elseif ($counter === 2) - верный  ответ:   <i>{{$field}}</i> <br>
                                                                        @endif
                                                                        <?php $counter++; ?>
                                                                    @endforeach
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>

                                        @endforeach
                                        </tbody>
                                    </table>
                                </ul>
                            @else
                                Ни одного теста не пройдено.
                            @endif
                            <br>
                        @endforeach
                    </div>



<!--ВКЛАДКА УДАЛЕННЫЕ!-->
                    <div name="tab_content" style="display:none">
                        @include('layouts.messages.message')
                        <h2>Удаленные уроки:</h2>
                        <?php //dump($deletedLessons);?>
                        <table class="table table-striped table-inverse">
                            <thead>
                                <th>#</th>
                                <th style="width:300px">Название урока</th>
                                <th>Восстановить</th>
                                <th>Удалить навсегда</th>
                            </thead>
                            <tbody>
                                @foreach ($deletedLessons as $deletedLesson)
                                    <tr>
                                        <td>{{$deletedLesson->id}}</td>
                                        <td><a href="{{$deletedLesson->full_url}}" target="_blank">{{$deletedLesson->name}}</a></td>
                                        <td>
                                            <a type="button" href="{{$deletedLesson->full_url.'/restore_lesson'}}" class="btn btn-outline-success btn-sm">
                                                Восстановить
                                            </a>
                                        </td>
                                        <td>
                                            <a type="button" href="{{$deletedLesson->full_url.'/delete_lesson'}}"
                                                class="btn btn-outline-danger btn-sm"
                                                onclick="return confirm ('Убедитесь что за уроком не закреплены тесты. Точно удалить урок навсегда?')">
                                                Уничтожить
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <h2>Удаленные тесты:</h2>
                        <?php //dump($deletedTests);?>
                        <table class="table table-striped table-inverse">
                            <thead>
                            <th>#</th>
                            <th style="width:300px">Название теста</th>
                            <th>Восстановить</th>
                            <th>Удалить навсегда</th>
                            </thead>
                            <tbody>
                            @foreach ($deletedTests as $deletedTest)
                                <tr>
                                    <td>{{$deletedTest->id}}</td>
                                    <td><a href="{{$deletedTest->full_url}}" target="_blank">{{$deletedTest->name}}</a></td>
                                    <td>
                                        <a type="button" href="{{$deletedTest->full_url . '/restore_test'}}" class="btn btn-outline-success btn-sm">
                                            Восстановить
                                        </a>
                                    </td>
                                    <td>
                                        <a type="button" href="{{$deletedTest->full_url . '/delete_test'}}"
                                           class="btn btn-outline-danger btn-sm"
                                           onclick="return confirm ('Тест и все результаты его прохождения другими пользователями будут удалены. Точно удалить тест навсегда?')">
                                            Уничтожить
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
                <script>
                    showTabContent(0); // вкладка по умолчанию
                    // если якорь присутсвует в url - покажем нужную вкладку
                    if (window.location.hash === '#users')          showTabContent(0);
                    if (window.location.hash === '#map')            showTabContent(1);
                    if (window.location.hash === '#test_results')   showTabContent(2);
                    if (window.location.hash === '#user_results')   showTabContent(3);
                    if (window.location.hash === '#deleted')        showTabContent(4);
                    // отображает вкладку с определенным номером
                    function showTabContent(i) {
                        // // если переключились на другую вкладку - пусть сообщение layouts.messages.message исчезнет
                        // let messageDiv = document.getElementById('layouts.messages.message');
                        // console.log(messageDiv);
                        // messageDiv.style.display='none';
                        let arrTabsContent = document.getElementsByName('tab_content');
                        let arrTabs = document.getElementsByName('tab');
                        // делаем все контенты табов невидимыми
                        for (let j = 0; j < arrTabsContent.length; j++) {
                            arrTabsContent[j].style.display = 'none';
                            arrTabs[j].classList.remove('active');
                        }
                        arrTabsContent[i].style.display = 'inline';
                        arrTabs[i].classList.add('active');
                    }
                </script>
                <script>
                    // по нажатию на кнопку скрыват/показывает контент для вкладки с группировкой по тестам
                    function showHideResultDetailsByTestTab(id) {
                        let resultDetails = document.getElementById('details_test_'+id);
                        if (resultDetails.style.display === 'none') {
                            resultDetails.style.display = 'inline';
                            console.log('покажем', resultDetails);
                        }
                        else {
                            resultDetails.style.display = 'none';
                            console.log('скроем', resultDetails);
                        }
                    }
                    // по нажатию на кнопку скрыват/показывает контент для вкладки с группировкой по Ученикам
                    function showHideResultDetailsByUserTab(id) {
                        let resultDetails = document.getElementById('details_user_'+id);
                        if (resultDetails.style.display === 'none') {
                            resultDetails.style.display = 'inline';
                            console.log('покажем', resultDetails);
                        }
                        else {
                            resultDetails.style.display = 'none';
                            console.log('скроем', resultDetails);
                        }
                    }
                </script>
            </div>
        </div>

        <!-- КОНЕЦ Содержимого страницы!-->
        <footer class="footer container">КФУ им. В.И. Вернадского, Симферополь, 2020</footer>
    </div>

</div>

</div>
</body>
</html>
