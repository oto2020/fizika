<!--require sections, section !-->
<div name = "layouts.top.menu">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <ul class="navbar-nav mr-auto">
        @foreach($sections as $s)
            <li class="nav-item {{isset($section) && $section->url == $s->url?'active':''}}">
                <a class="nav-link" href="/{{$s->url}}">{{$s->name}} </a>
            </li>
        @endforeach
    </ul>

    @if ($role != null)
    <div style="float:bottom; height: 50px">
        {{--Блок с вошедшим пользователем--}}
        <table style="height: 50px">
            <tr>
               <!--АВА ПОЛЬЗОВАТЕЛЯ 70px-->
                <td style="width:70px;">
                    <form method="post" action="/reload_avatar.php">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name = "userId" value="{{$user->id}}">
                        <input type="hidden" name = "userName" value="{{$user->name}}">
                        <input type="image" id="img" class="img-user-avatar-on-top-menu" src="{{$user->avatar_src}}">
                    </form>
                </td>
                <!--ИМЯ ПОЛЬЗОВАТЕЛЯ + РОЛЬ-->
                <td id="user_block">
                    <font color="white">
                        <h5 style="margin-bottom: 0.2rem;"> {{$user->name}} </h5>
                        <h7 id="role_block"> [{{$role->name}}: {{$user->class_name}}] </h7>
                    </font>
                </td>
               <!--КНОПКА ВЫХОД-->
                <td style="width:70px;">
                    <a style="float:right" href="/logout" onclick="return confirm ('Точно выйти?')">
                        <img id="img_exit" style="width:50px" src="/storage/img/exit_button_1.png">
                    </a>
                </td>
            </tr>
        </table>
    </div>

    <script>
        // анимация Аватарки пользователя
        let img = document.getElementById("img");
        img.addEventListener("mouseover", function(e){img.src = "/storage/img/avatar_reload_1.gif"}); //if(e.ctrlKey)
        img.addEventListener("mouseleave", function(){img.src = "{{$user->avatar_src}}"});

        // анимация кнопки "Выход"
        let imgExit = document.getElementById("img_exit");
        imgExit.addEventListener("mouseenter", function(){imgExit.src = "/storage/img/exit_button_action_1.png"});
        imgExit.addEventListener("mouseleave", function(){imgExit.src = "/storage/img/exit_button_1.png"});

        // анимация при наведении на область пользователя в верхнем меню
        let userBlock = document.getElementById("user_block");
        let roleBlock = document.getElementById("role_block");
        userBlock.addEventListener("mouseenter", function(){
            userBlock.style.cursor="pointer";
            // для сохранения ширины
            let tmp = userBlock.offsetWidth;
            roleBlock.innerHTML="<font color=\"white\">&#9733; [личный кабинет]</font>";
            userBlock.style.width = tmp;
        });
        userBlock.addEventListener("mouseleave", function(){roleBlock.innerHTML="[{{$role->name}}: {{$user->class_name}}]";});
        userBlock.addEventListener("mouseup", function(){document.location.href="/cabinet"});
    </script>
    @endif

</nav>
</div>


