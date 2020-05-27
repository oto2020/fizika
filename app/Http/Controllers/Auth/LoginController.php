<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        session(['login_email' => $request->email]);
        if (Auth::attempt(['email'=>$request->email, 'password'=>$request->password], ($request->remember == 'on'))) {
            // Аутентификация успешна...
            // Получим роль пользователя:
            try {
                $role = DB::table('user_roles')
                    ->select('id', 'name', 'description')
                    ->where('id', '=', Auth::user()->user_role_id)
                    ->get()[0];
            }
            catch(\Exception $e) {
                dd('Не удалось определить роль пользователя. Обратитесь к администратору!');
            }
            return redirect('/main')->with('message', 'Вы вошли в систему как ['.$role->name.']. '.$role->description);
        }
        return redirect('/login')->with('err_login', 'Неправильный логин или пароль');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->back()->with('message', 'Вы зачем-то вышли.');
    }
}
