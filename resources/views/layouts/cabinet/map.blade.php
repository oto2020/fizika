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
