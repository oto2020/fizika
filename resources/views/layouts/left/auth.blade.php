<!-- required sections, section, userInfo!-->
<div name = "layouts.left.auth">
    @if ($role == null)
        <br>
        Войдите
        <div class="login-block">
            <form  method="post" action="/login">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="form-group">
                    <span class="input-group-addon"></span>
                    <input name="email" type="text" class="form-control" placeholder="Ваш email" value="{{Session::get('login_email')}}">
                </div>

                <div class="form-group">
                    <span class="input-group-addon"></span>
                    <input name="password" type="password" class="form-control" placeholder="Ваш суперпароль">
                </div>
                <button class="btn btn-secondary btn-block" type="submit">ВОЙТИ НА САЙТ</button>
            </form>
            <div class="login-links">
                <p class="text-center" style="color:red">Нет аккаунта? <br>
                    <a class="txt-brand" href="/register"><font color="#29aafe">Регистрируйся</font></a>
                </p>
            </div>
        </div>
        <br>

    @else
        <hr>
        <h5>{{$user->name}}</h5>
        <img src="https://ui-avatars.com/api/?size=190&name=Конюхова+Антонина&font-size=0.45&background=40E0D0&color=fff&rounded=false" class="center-block">
        <div class="liteTooltip" >
            <h6>{{$role->name}}</h6>
        </div>
        <a href="/cabinet">&#9733; [личный кабинет]</a>
        <br>
        <a href="/logout" onclick="return confirm ('Точно выйти?')">&crarr; выйти</a>
        <hr>
    @endif
</div>
