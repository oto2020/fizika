@extends('layouts.app', ['title'=>$lesson->name])

@section('content')
    <!--ВСЁ, ЧТО ПОСЛЕ ВЕРХНЕГО МЕНЮ!-->
    <div class="row">
        <!-- ЛЕВОЕ МЕНЮ !-->
        <div class="col-xs-12 col-sm-2">
            @include('layouts.left.auth')
            @include('layouts.left.menu')
        </div>
        <!-- КОНЕЦ ЛЕВОГО МЕНЮ!-->

        <!--Содержимое страницы!-->
        <div class="col-xs-12 col-sm-10" style="padding-left: 25px;">
            <p>
            <h1>{{$lesson->name}}</h1>
            @if ($role!==null && $role->name == 'Администратор')
                <a href="/{{$section->url}}/{{$lesson->url}}/edit_lesson">
                    [редактировать]
                </a>
                <a href="/{{$section->url}}/{{$lesson->url}}/mark_as_deleted"
                   onclick="return confirm ('Внимание: перед удалением урока необходимо удалить все его тесты! Отправляем урок в удалённые?')"
                >
                    [не отображать на сайте]
                </a>
                <br>
            @endif
        <!-- КОНТЕНТ СТРАНИЦЫ ИБ БД!-->
            <div id="onlyLessonPageContentCSS">
                {!!$lesson->content!!}
            </div>
            <br>
            <h6>Дата добавления урока: {{$lesson->date}} | Автор урока: {{$lesson->user}}</h6>
            </p>

            <!-- Тесты !-->
            @if (($role!==null && ($role->name == 'Администратор' || $role->name == 'Ученик')))
                <p>
                <hr/>
                <h1>Тесты по уроку:</h1>
                <ul class="list-group">
                    @foreach($tests as $test)
                        <li class="list-group-item">
                            <a href="/{{$section->url}}/{{$lesson->url}}/{{$test->url}}">{{$test->name}}</a>
                        </li>
                    @endforeach
                </ul>
                </p>
            @endif
            @if ($role!==null && $role->name == 'Администратор')
                <ul class="list-group">
                    <li class="list-group-item">
                        <a href="{{$lesson->full_url}}/add_test/">[добавить тест]</a>
                    </li>
                </ul>
            @endif
        <!-- Тесты !-->
            <hr>
            <br>
            <h3>
                Комментарии к уроку:
            </h3>
            <br>
            @foreach ($comments as $comment)
                <div class="media-block">
                    <a class="media-left" href="#">
                        <img class="img-circle img-sm" name="avatar_image[]" src="{{$comment->avatar_src}}"
                             onerror="this.src = '/storage/img/AVATAR_ZAYAC.png'">
                    </a>
                    <div class="media-body">

                        <div class="mar-btm">
                            <a href="#"
                               class="btn-link text-semibold media-heading box-inline">{{$comment->user_name}}</a>
                            <p class="text-muted text-sm"> {{$comment->datetime}}</p>
                        </div>
                        <p>{{$comment->content}}</p>
                        <hr>
                    </div>
                </div>
            @endforeach
            @if ($role!==null && $role->level > 10)
                <div class="col-md-12">
                    <div class="panel">
                        <div style="width:100%">
                            <form method="post" action="/add_comment">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="user_id" value="{{$user->id}}">
                                <input type="hidden" name="lesson_id" value="{{$lesson->id}}">
                                <input name="content" type="text" class="form-control"
                                       placeholder="Добавьте Ваш комментарий">

                                <div class="mar-top clearfix">
                                    <button class="btn btn-sm btn-outline-dark" type="submit"
                                            style="width:160px; float:right; margin-top:10px"> Добавить
                                    </button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            @else
                <div class="alert alert-warning" role="alert">
                    Только зарегистрированные и подтверждённые ученики могут писать комментарии.
                </div>
        @endif
            <br>
            <br>
        </div>
        <!-- КОНЕЦ Содержимого страницы!-->
    </div>

@endsection

