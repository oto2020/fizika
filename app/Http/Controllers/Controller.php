<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function mylog($level, $message)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $role = $this->getRole($user);
            $message =
                $role->name . ', ' .
                $user->class_name . ', ' .
                $user->name .
                '(' . $user->email . ')' .
                '[' . $_SERVER["REMOTE_ADDR"] . '] ' .
                $message;
        } else {
            $message = 'Аноним' .
                '[' . $_SERVER["REMOTE_ADDR"] . '] ' .
                $message;
        }
        if ($level === 'info') Log::info($message);
        if ($level === 'warning') Log::warning($message);
        if ($level === 'alert') Log::alert($message);
    }

    protected function getUser()
    {
        return Auth::user();
    }

    // Вытягивает из БД разделы сайта
    protected function getSections()
    {
        try {
            $sections = DB::table('sections')
                ->select('id', 'name', 'url', 'ico')
                ->orderBy('id', 'asc')
                ->get();
        } catch (\Exception $e) {
            dd('Не удалось вытянуть из БД разделы сайта. Обратитесь к администратору!', $e->getMessage());
        }
        return $sections;
    }

    // Вытягивает из БД уроки, относящиеся к определённому разделу
    protected function getLessons($sectionID)
    {
        try {
            $lessons = DB::table('lessons')
                ->select('id', 'name', 'date', 'preview_text', 'section_id', 'url', 'content', 'user', 'full_url')
                ->where('section_id', '=', $sectionID)
                ->where('is_deleted', '=', null)
                ->orderBy('id', 'asc')
                ->get();
        } catch (\Exception $e) {
            dd('Не удалось вытянуть из БД уроки. Обратитесь к администратору!', $e->getMessage());
        }
        return $lessons;
    }

    // Находит конкретный раздел по его url
    protected function getSection($sectionURL)
    {
        try {
            $section = DB::table('sections')
                ->select('id', 'name', 'url')
                ->where('url', '=', $sectionURL)
                ->get()[0];
        } catch (\Exception $e) {
            dd('Не удалось найти раздел по заданному url. Обратитесь к администратору!', $e->getMessage());
        }
        return $section;
    }

    // Находит конкретный урок по его url
    protected function getLesson($lessonURL)
    {
        try {
            $lesson = DB::table('lessons')
                ->select('id', 'name', 'date', 'preview_text', 'url', 'section_id', 'content', 'user', 'full_url')
                ->where('url', '=', $lessonURL)
                ->orderBy('id', 'asc')
                ->get()[0];
        } catch (\Exception $e) {
            dd('Не удалось найти урок по заданному url. Обратитесь к администратору!', $e->getMessage());
        }
        return $lesson;
    }

    // Находит тесты, относящиеся к уроку по ID урока
    protected function getTests($lessonID)
    {
        try {
            $tests = DB::table('test1_tests')
                ->select('id', 'lesson_id', 'name', 'url', 'preview_text', 'user', 'full_url')
                ->where('lesson_id', '=', $lessonID)
                ->where('is_deleted', '=', null)
                ->orderBy('id', 'asc')
                ->get();
        } catch (\Exception $e) {
            dd('Не удалось найти тесты по текущему уроку. Обратитесь к администратору!', $e->getMessage());
        }
        return $tests;
    }

    // Получает тест по его URL
    protected function getTest($testURL)
    {
        try {
            $test = DB::table('test1_tests')
                ->select('id', 'name', 'url', 'preview_text', 'user', 'full_url')
                ->where('url', '=', $testURL)
                ->orderBy('id', 'asc')
                ->get()[0];

        } catch (\Exception $e) {
            dd('Не удалось найти тест по URL. Обратитесь к администратору!', $e->getMessage());
        }
        return $test;
    }

    // Получает роль пользователя
    protected function getRole($user)
    {
        try {
            $role = DB::table('user_roles')
                ->select('id', 'name', 'level', 'description')
                ->where('id', '=', $user->user_role_id)
                ->get()[0];
        } catch (\Exception $e) {
            $role = null;
            // тут мы не приостанавливаем выполнение страницы. считаем, что если $role=null, значит пользователь не вошел.
        }
        return $role;
    }

    // возвращает комментарии к уроку и информацию о пользователе
    protected function getLessonComments($lessonId)
    {
        try {
            $comments = DB::table('lesson_comments')
                //  вторая присоединяемая таблица,  поле id из первой таблицы,     =            поле question_id из второй таблицы
                ->join('users', 'lesson_comments.user_id', '=', 'users.id')
                ->select('lesson_comments.user_id as user_id',
                    'lesson_comments.lesson_id as lesson_id',
                    'lesson_comments.content as comment_content',
                    'lesson_comments.datetime as comment_datetime',
                    'users.name as user_name',
                    'users.class_name as user_class_name',
                    'users.created_at as user_created_at',
                    'users.avatar_src as avatar_src'
                )
                ->where('lesson_comments.lesson_id', '=', $lessonId)
                ->get();
        } catch (\Exception $e) {
            $comments = null;
            // тут мы не приостанавливаем выполнение страницы. считаем, что если $comments=null, значит комментов нет.
        }
        return $comments;
    }


    // генерирует и сохраняет аватарку пользователя. Возвращает путь к урлу
    protected function generateSaveAvatar($userId, $userName)
    {
        // генерируем случайный цвет из трёх частей
        $backColor = str_pad(dechex(mt_rand(0, 200)), 2, '0', STR_PAD_LEFT)
            . str_pad(dechex(mt_rand(0, 200)), 2, '0', STR_PAD_LEFT)
            . str_pad(dechex(mt_rand(0, 200)), 2, '0', STR_PAD_LEFT);
        // формируем url-запрос
        $url = 'https://ui-avatars.com/api/?size=300&font-size=0.45&color=fff&rounded=false&name=' . $userName . '&background=' . $backColor;
        // сохранение файла на диск
        $contents = file_get_contents($url);
        Storage::put('/public/img/' . 'avatar_' . $userName . '.png', $contents);
        //echo '/storage/img/avatar_' . $user->name . '.png';

        // попробуем обновить запись avatar_src у юзера
        try {
            DB::table('users')
                ->where('id', '=', $userId)
                ->update(
                    [
                        'avatar_src' => '/storage/img/' . 'avatar_' . $userName . '.png',
                    ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'При обновлении аватарки пользователя произошла ошибка. ' . $e->getMessage());
        }
        return true;

    }
}
