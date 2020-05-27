<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function register(Request $request) {
        // запишем в сессию, чтобы было автозаполнение
        // чтобы например, при вводе неправильного пароля после перезагрузки страницы не пришлось заполнять все поля заново
        session(['register_class_name' => $request->class_name]);
        session(['register_name' => $request->name]);
        session(['register_email' => $request->email]);

        // быстро проверим на ошибки
        if (!preg_match('/^[а-яёА-ЯЁ\-\s]+$/u', $request->name)) {
            return back()->with('err_register', 'Некорректное ФИО');
        }
        if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            return back()->with('err_register', 'Некорректный e-mail');
        }
        if (isset(DB::table('users')->
            select('email')->
            where('email', '=', $request->email)->
            get()[0])) {
            return back()->with('err_register', 'Выбранный email занят');
        }
        if (strlen($request->password) < 4) {
            return back()->with('err_register', 'Длина пароля должна быть хотя бы 4 символа');
        }
        if ($request->password !== $request->password_confirmation) {
            return back()->with('err_register', 'Пароли не совпадают');
        }

        $userArray = [
            'name' => $request->name,
            'email' => $request->email,
            'user_role_id' => 3, // неподтверждённый участник
            'password' => Hash::make($request->password),
            'class_name' => $request->class_name,
            'created_at' =>  date('Y-m-d H:i:s'),
        ];

        //dd($userArray);
        //dd('2020-05-19 15:37:52', date('Y-m-d H:i:s'));
        try {
            DB::table('users')->insert($userArray);
        }
        catch (\Exception $e) {
            return back()->with('err_register', 'Обратитесь к администратору. Произошла ошибка на сайте во время регистрации: ' . $e->getMessage());
        }

        //после успешной регистрации нас перебросит на страничку входа, и логин будет уже заполнен. мелочь, а приятно
        session(['login_email' => $request->email]);
        return redirect()->to('/login')->with('message', 'Вы успешно зарегистрированы. Email: \'' . $request->email . '\' Пароль: \'' . $request->password . '\'');


//        dd($validator->errors()->getMessageBag()->getMessages());
//        return redirect()->to('register')->with('errors', $validator->errors()->getMessageBag()->getMessages()); //@include('layouts.messages.err_register')
    }

}
