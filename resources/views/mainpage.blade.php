@extends('layouts.app')

@section('content')
<!--ВСЁ, ЧТО ПОСЛЕ ВЕРХНЕГО МЕНЮ!-->
<div class="row row-full-width">
    <!-- ЛЕВОЕ МЕНЮ !-->
    <div class="col-xs-12 col-sm-2">
        @include('layouts.left.auth')
        <script type="text/javascript"
                src="//rf.revolvermaps.com/0/0/6.js?i=5bnsmkkp4o8&amp;m=7&amp;c=e63100&amp;cr1=ffffff&amp;f=arial&amp;l=0&amp;bv=90&amp;lx=-420&amp;ly=420&amp;hi=20&amp;he=7&amp;hc=a8ddff&amp;rs=80"
                async="async">
        </script>
    </div>
    <!-- КОНЕЦ ЛЕВОГО МЕНЮ!-->

    <!--Содержимое страницы!-->
    <div class="col-xs-12 col-sm-10" style="padding-left: 25px;">
        <p>
        <h1>{{$lesson->name}}</h1>
        @if ($role!==null && $role->name == 'Администратор')
            <a href="/{{$section->url}}/{{$lesson->url}}/edit_lesson">[редактировать]</a>
        @endif
    <!-- КОНТЕНТ СТРАНИЦЫ ИБ БД!-->
        {!!$lesson->content!!}
        <br>
        </p>
    </div>
    <!-- КОНЕЦ Содержимого страницы!-->
</div>
@endsection
