<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class Lesson extends Model
{
    public static function addLessonFromFile($path)
    {
        $contents = File::get($path);
        dd($contents);


//        // Добавим onerror для latex-формул, которые рендерятся на стороннем сервисе
//        $content = $request->html_content;
//        // Выполняет поиск в строке subject совпадений с шаблоном pattern и заменяет их на replacement
//        $content = preg_replace ('/src="http:\/\/latex.codecogs.com/', ' onload="onLoadLatexImg(this)" src="http://latex.codecogs.com', $content);
//
//        $arrayToInsert = [
//            'name' => $request->lesson_name,
//            'date' => $request->date,
//            'preview_text' => $request->preview_text,
//            'url' => $request->url,
//            'full_url' => '/' . $sectionURL . '/' . $request->url,
//            'section_id' => $request->section,
//            'content' => $content,
//            'user' => $request->user,
//            'is_deleted' => null,
//        ];
//        // попытаемся добавить запись в БД
//        try {
//            DB::table('lessons')->insert($arrayToInsert);
//        } // если вылетела ошибка
//        catch (\Exception $exc) {
//            dd('При добавлении записи в БД произошла ошибка. ' . $exc->getMessage());
//            return redirect()->back()->with('error', 'При добавлении записи в БД произошла ошибка. ' . $exc->getMessage());
//        }
//        $this->mylog('warning', 'Добавил страницу: /' . $sectionURL . '/' . $request->url);
//        return redirect('/' . $sectionURL . '/' . $request->url)->with('message', 'Страница успешно размещена!');
    }
}
