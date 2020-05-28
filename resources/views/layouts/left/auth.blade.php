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
{{--    машины:--}}
{{--        <img src="https://ui-avatars.com/api/?size=190&name=Конюхова+Антонина&font-size=0.45&background=40E0D0&color=fff&rounded=false" class="center-block">--}}
{{--        <img src="https://ui-avatars.com/api/?size=190&name=Конюхова+Антонина&font-size=0.45&background=90ee90&color=fff&rounded=false" class="center-block">--}}

{{--        <img src="https://ui-avatars.com/api/?size=190&name=Конюхова+Антонина&font-size=0.45&background=87cefa&color=fff&rounded=false" class="center-block">--}}
{{--        <img src="https://ui-avatars.com/api/?size=190&name=Конюхова+Антонина&font-size=0.45&background=dda0dd&color=fff&rounded=false" class="center-block">--}}
{{--        <img src="https://ui-avatars.com/api/?size=190&name=Конюхова+Антонина&font-size=0.45&background=778899&color=fff&rounded=false" class="center-block">--}}
{{--        <img src="https://ui-avatars.com/api/?size=190&name=Конюхова+Антонина&font-size=0.45&background=008080&color=fff&rounded=false" class="center-block">--}}
{{--    тосины:--}}
{{--        <img src="https://ui-avatars.com/api/?size=190&name=Конюхова+Антонина&font-size=0.45&background=66cdaa&color=fff&rounded=false" class="center-block">--}}
{{--        <img src="https://ui-avatars.com/api/?size=190&name=Конюхова+Антонина&font-size=0.45&background=4682b4&color=fff&rounded=false" class="center-block">--}}
{{--        <img src="https://ui-avatars.com/api/?size=190&name=Конюхова+Антонина&font-size=0.45&background=fa8072&color=fff&rounded=false" class="center-block">--}}
{{--        <img src="https://ui-avatars.com/api/?size=190&name=Конюхова+Антонина&font-size=0.45&background=6b8e23&color=fff&rounded=false" class="center-block">--}}
{{--        <img src="https://ui-avatars.com/api/?size=190&name=Конюхова+Антонина&font-size=0.45&background=bdb76b&color=fff&rounded=false" class="center-block">--}}
{{--        <img src="https://ui-avatars.com/api/?size=190&name=Конюхова+Антонина&font-size=0.45&background=8b4513&color=fff&rounded=false" class="center-block">--}}
        <div class="liteTooltip" >
            <h6>
{{--                <img src="https://ui-avatars.com/api/?size=190&name=Конюхова+Антонина&font-size=0.45&background=ffa07a&color=fff&rounded=false" class="center-block" width="30px">--}}

                {{$role->name}}
            </h6>
        </div>
        <a href="/cabinet">&#9733; [личный кабинет]</a>
        <br>
        <a href="/logout" onclick="return confirm ('Точно выйти?')">&crarr; выйти</a>
        <hr>
    @endif
</div>
