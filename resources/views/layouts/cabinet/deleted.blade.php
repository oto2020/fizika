<div name="tab_content" style="display:none">
    @include('layouts.messages.message')
    <h2>Удаленные уроки:</h2>
    <?php //dump($deletedLessons);?>
    <table class="table table-striped table-inverse big-table">
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
    <table class="table table-striped table-inverse big-table">
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
