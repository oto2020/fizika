<!--require sections, section !-->
<div name = "layouts.top_menu">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <ul class="navbar-nav mr-auto">
        @foreach($sections as $s)
            <li class="nav-item {{$section->url==$s->url?'active':''}}">
                <a class="nav-link" href="/{{$s->url}}">{{$s->name}} </a>
            </li>
        @endforeach
    </ul>
    <form class="form-inline my-2 my-lg-0">
        <input class="form-control mr-sm-2" type="search" placeholder="Поиск" aria-label="Search">
        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Поиск</button>
    </form>
</nav>
</div>
