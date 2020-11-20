@extends('layouts.app')

@section('content')
    <div class="row row-full-width">
        <!-- ЛЕВОЕ МЕНЮ !-->
        <div class="col-xs-12 col-sm-2">
            @include('layouts.left.auth')
            @include('layouts.left.menu')
        </div>
        <!-- КОНЕЦ ЛЕВОГО МЕНЮ!-->

        <!--Содержимое страницы!-->
        <div class="col-xs-12 col-sm-10" style="padding-left: 25px;">
            @if($section->url=='main')
                На главной странице будет что-то другое, кроме списка уроков.
            @elseif(count($lessons)==0)
                Уроки пока не добавлены.
            @else
                <ul class="list-group list-group-flush">
                    @foreach($lessons as $l)
                        <li class="list-group-item">
                            <p>
                            <h2>{{$l->name}}</h2>

                            <h6>Дата добавления урока: {{$l->date}} | Автор урока: {{$l->user}}</h6>

                            {{$l->preview_text}}
                            <a class="nav-link" href="/{{$section->url}}/{{$l->url}}">Подробнее -></a>
                            </p>
                        </li>
                    @endforeach
                </ul>

            @endif
        </div>
        <!-- КОНЕЦ Содержимого страницы!-->
    </div>
@endsection


