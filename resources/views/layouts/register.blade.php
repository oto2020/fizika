<div class="container" >
    <div class="row justify-content-center">
        <div class="" style="max-width:700px">
            <div class="" style="width:100%">
                <div class="" >Зарегистрируйтесь</div>

                <div class="">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="form-group">
                            <select class="browser-default custom-select" name="class_name">
                                @foreach ($sections as $section)
{{--                                    @if ($section->name!='Главная')--}}
                                    @if (preg_match('/^([0-9]){1,2}(.){0,}/', $section->name))
                                        <option>{{$section->name}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group ">
                            <div class="">
                                <input id="name" placeholder="Ваше имя" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group ">
                            <div class="">
                                <input id="email" placeholder="Ваш e-mail" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group ">
                            <div class="">
                                <input id="password" placeholder="Пароль" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group ">
                            <div class="">
                                <input id="password-confirm" placeholder="Повторите пароль" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>
                        @include('layouts.messages.err_register')

                        <button type="submit" class="btn btn-secondary btn-block">
                            РЕГИСТРАЦИЯ
                        </button>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
