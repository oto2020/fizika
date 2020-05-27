<div name = "layouts.left.menu">
    <!--Уроки!-->
    @if(count($lessons) == 0)
        Уроки пока не добавлены.
    @else
        <?php $counter = 1;?>
        {{$section->name}}. Уроки:
        <nav class="navbar  navbar-light bg-light">
            <ul class="navbar-nav mr-auto">
                @foreach($lessons as $l)
                    <li class="nav-item active">
                        <a class="nav-link" href="/{{$section->url}}/{{$l->url}}">Урок {{$counter}}
                            . {{$l->name}} </a>
                    </li>
                    <?php $counter++;?>
                @endforeach
            </ul>

        </nav>
    @endif
    @if ($role!==null && $role->name == 'Администратор')
        <a href="/{{$section->url}}/add_lesson"> [добавить урок]</a>
    @endif
    <br>
    <!-- Тесты !-->
    @if (isset($tests) && count($tests) > 0)
        <hr/>
        <?php $counter = 1;?>
        Тесты по текущему уроку:
        @if (($role!==null && ($role->name == 'Администратор' || $role->name == 'Ученик')))
            <nav class="navbar  navbar-light bg-light">
                <ul class="navbar-nav mr-auto">
                    @foreach($tests as $test)
                        <li class="nav-item active">
                            <a class="nav-link" href="/{{$section->url}}/{{$lesson->url}}/{{$test->url}}">Тест {{$counter}}
                                . {{$test->name}}</a>
                        </li>
                        <?php $counter++;?>
                    @endforeach
                </ul>
            </nav>
        @else
            <div class="alert alert-danger" role="alert">
                Станьте учеником, чтобы проходить тесты
            </div>
        @endif
    @endif
    @if (isset($lesson) && $role!==null && $role->name == 'Администратор')
        <a href="{{$lesson->full_url}}/add_test/">[добавить тест]</a>
    @endif
</div>
