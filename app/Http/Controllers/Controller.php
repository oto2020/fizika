<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    protected static function mylog ($level, $message)
    {
        $message = (Auth::check() ? Auth::user()->name : 'Аноним') . '['. $_SERVER["REMOTE_ADDR"]. '] ' . $message;
        if ($level === 'info') Log::info($message);
        if ($level === 'warning') Log::warning($message);
        if ($level === 'alert') Log::alert($message);
    }

    // Вытягивает из БД разделы сайта
    protected function getSections()
    {
        try {
            $sections = DB::table('sections')
                ->select('id', 'name', 'url', 'ico')
                ->orderBy('id', 'asc')
                ->get();
        }
        catch (\Exception $e) {
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
        }
        catch (\Exception $e) {
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
        }
        catch (\Exception $e) {
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
        }
        catch (\Exception $e) {
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
        }
        catch (\Exception $e) {
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

        }
        catch (\Exception $e) {
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
        }
        catch (\Exception $e) {
            $role = null;
            // тут мы не приостанавливаем выполнение страницы. считаем, что если $role=null, значит пользователь не вошел.
        }
        return $role;
    }
}
