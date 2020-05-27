<!-- required sections, section, userInfo!-->
<div name = "layouts.left_auth_menu">
    @if ($role == null)
        <br>
        @include('layouts.login')
    @else
        <hr>
        <h5>{{$user->name}}</h5>
        <div class="liteTooltip" >
            <h6>{{$role->name}}</h6>
        </div>
        <a href="/logout" onclick="return confirm ('Точно выйти?')">выйти</a>
        <hr>
    @endif
</div>
