<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Excel;


class ExcelController extends Controller
{
    // открывает страницу загрузки эксель файла
    public function openExcelPage()
    {
        return view('openexcel');
    }
    // отображает эксель файл
    public function openExcelPOST(Request $request)
    {
        $file = $request->file;
        $content=Excel::load($file, function($reader) {})->get();
        dd($content);
    }
}
