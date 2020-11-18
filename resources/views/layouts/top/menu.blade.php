<!--require sections, section !-->

<script>
    // если true - мы на мобильном устройстве
    var isMobile = (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent));
</script>
<!-- Десктопная версия !-->
<div name="layouts.top.menu-desktop" id="top_menu_desktop">
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
                        <td id="user_ava_block" style="width:70px;">
                            <img class="img-user-avatar-on-top-menu" src="{{$user->avatar_src}}">
                        </td>
                        <!--ИМЯ ПОЛЬЗОВАТЕЛЯ + РОЛЬ-->
                        <td id="user_block">
                            <font color="white">
                                <h5 style="margin-bottom: 0.2rem;"> {{$user->name}} </h5>
                                <h7 id="role_block"> [{{$role->name}}: {{$user->class_name}}]</h7>
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
                // Этот JS написан для десктопной версии сайта
                if (!isMobile) {
                    // анимация кнопки "Выход"
                    let imgExit = document.getElementById("img_exit");
                    imgExit.addEventListener("mouseenter", function () {
                        imgExit.src = "/storage/img/exit_button_action_1.png"
                    });
                    imgExit.addEventListener("mouseleave", function () {
                        imgExit.src = "/storage/img/exit_button_1.png"
                    });


                    // анимация при наведении на область пользователя в верхнем меню
                    let userAvaBlock = document.getElementById("user_ava_block");
                    let userBlock = document.getElementById("user_block");
                    let roleBlock = document.getElementById("role_block");

                    // изменяет содержимое блока с ролью пользователя
                    function changeRoleBlockContent() {
                        userAvaBlock.style.cursor = "pointer";
                        userBlock.style.cursor = "pointer";
                        // для сохранения ширины
                        let tmp = userBlock.offsetWidth;
                        roleBlock.innerHTML = "<font color=\"white\">&#9733; [личный кабинет]</font>";
                        userBlock.style.width = tmp;
                    }

                    // возвращает содержимое блока с ролью пользователя обратно
                    function returnRoleBlockContent() {
                        roleBlock.innerHTML = "[{{$role->name}}: {{$user->class_name}}]";
                    }

                    // ссылка на кабинет управления
                    function hrefToCabinet() {
                        document.location.href = "/cabinet"
                    }

                    userAvaBlock.addEventListener("mouseenter", changeRoleBlockContent);
                    userAvaBlock.addEventListener("mouseleave", returnRoleBlockContent);
                    userAvaBlock.addEventListener("mouseup", hrefToCabinet);
                    userBlock.addEventListener("mouseenter", changeRoleBlockContent);
                    userBlock.addEventListener("mouseleave", returnRoleBlockContent);
                    userBlock.addEventListener("mouseup", hrefToCabinet);
                }
            </script>
        @endif
    </nav>
</div>


<!-- Мобильная версия !-->
<div name="layouts.top.menu-mobile" id="top_menu_mobile">
    <div style="height: 60px;">
        @if ($role != null)
            <div class="top-menu-mobile-column-left">
                <img class="img-user-avatar-on-top-menu" src="{{$user->avatar_src}}">
                Кабинет
            </div>
        @endif
        <div class="top-menu-mobile-column-center">
            Меню
        </div>
        @if ($role != null)
            <div class="top-menu-mobile-column-right">
                <a href="/logout" onclick="return confirm ('Точно выйти?')">
                    <img id="img_exit" style="width:50px" src="/storage/img/exit_button_1.png">
                </a>
                Выход
            </div>
        @endif
    </div>
</div>

<script>
    // займемся переключением десктопной и мобильной версии меню
    var desktopMenu = document.getElementById('top_menu_desktop');
    var mobileMenu = document.getElementById('top_menu_mobile');
    if (isMobile) desktopMenu.remove();
    else mobileMenu.remove();
</script>





