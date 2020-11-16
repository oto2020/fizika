<?php

namespace App\Http\Controllers;


use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
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


//    // генерирует и сохраняет аватарку пользователя. Возвращает путь к урлу
//    protected function generateSaveAvatar($userId, $userName)
//    {
//        // генерируем случайный цвет из трёх частей
//        $backColor = str_pad(dechex(mt_rand(0, 200)), 2, '0', STR_PAD_LEFT)
//            . str_pad(dechex(mt_rand(0, 200)), 2, '0', STR_PAD_LEFT)
//            . str_pad(dechex(mt_rand(0, 200)), 2, '0', STR_PAD_LEFT);
//        // формируем url-запрос
//        $url = 'https://ui-avatars.com/api/?size=300&font-size=0.45&color=fff&rounded=false&name=' . $userName . '&background=' . $backColor;
//        // сохранение файла на диск
//        $contents = file_get_contents($url);
//        Storage::put('/public/img/' . 'avatar_' . $userName . '.png', $contents);
//        //echo '/storage/img/avatar_' . $user->name . '.png';
//
//        // попробуем обновить запись avatar_src у юзера
//        try {
//            DB::table('users')
//                ->where('id', '=', $userId)
//                ->update(
//                    [
//                        'avatar_src' => '/storage/img/' . 'avatar_' . $userName . '.png',
//                    ]);
//        } catch (\Exception $e) {
//            return redirect()->back()->with('error', 'При обновлении аватарки пользователя произошла ошибка. ' . $e->getMessage());
//        }
//        return true;
//
//    }

// генерирует и сохраняет аватарку пользователя. Возвращает путь к урлу
    protected function avatarGenerate(Request $request)
    {

        // источник, где я училась библиотеке GD2 : https://ruseller.com/lessons.php?id=439 и http://www.php.su/functions/?cat=image
        // Замена пути к шрифту на пользовательский
        $font = 'fonts/OpenSans-Bold.ttf';

        // сперва моя обёртка с нормальными названиями функций
        // function f(x){return x-2*(x-386);} инвертирование координат под js

        // рисует полигон
        function drawPolygonFilled($image, $arrayPoints, $color)
        {
            // считаем количество вершин
            $numPoints = count($arrayPoints)/2;
            // скармливаем массив и количество вершин этому методу из GD2
            imagefilledpolygon ($image, $arrayPoints, $numPoints, $color);
        }

        // рисует заполненный эллипс
        function drawEllipseFilled($image, $cx, $cy, $w, $h, $color)
        {
            imagefilledellipse($image, $cx, $cy, $w, $h, $color);
        }

        // функция рисует прямоугольник с закруглёнными краями и позаимствована тут: http://www.php.su/articles/?cat=graph&page=006
        function drawRectangleWithRoundedCorners($image, $x1, $y1, $x2, $y2, $r, $color) {
            // рисуем два прямоугольника без углов
            imagefilledrectangle($image, $x1+$r, $y1, $x2-$r, $y2, $color);
            imagefilledrectangle($image, $x1, $y1+$r, $x2, $y2-$r, $color);
            // рисуем круги в углах
            imagefilledellipse($image, $x1+$r, $y1+$r, $r*2, $r*2, $color);
            imagefilledellipse($image, $x2-$r, $y1+$r, $r*2, $r*2, $color);
            imagefilledellipse($image, $x1+$r, $y2-$r, $r*2, $r*2, $color);
            imagefilledellipse($image, $x2-$r, $y2-$r, $r*2, $r*2, $color);
        }
        // модификация с только одним закруглением
        function drawRectangleWithRoundedCornerLeftTop($image, $x1, $y1, $x2, $y2, $r, $color) {
            // рисуем два прямоугольника без углов
            imagefilledrectangle($image, $x1+$r, $y1, $x2, $y2, $color);
            imagefilledrectangle($image, $x1, $y1+$r, $x2, $y2, $color);
            // рисуем круги в углах
            imagefilledellipse($image, $x1+$r, $y1+$r, $r*2, $r*2, $color);
        }
// модификация с только одним закруглением
        function drawRectangleWithRoundedCornerRightTop($image, $x1, $y1, $x2, $y2, $r, $color) {
            // рисуем два прямоугольника без углов
            imagefilledrectangle($image, $x1, $y1, $x2-$r, $y2, $color);
            imagefilledrectangle($image, $x1, $y1+$r, $x2, $y2, $color);
            // рисуем круг в углу
            imagefilledellipse($image, $x2-$r, $y1+$r, $r*2, $r*2, $color);
        }



        // источник, где я училась библиотеке GD2 : https://ruseller.com/lessons.php?id=439 и http://www.php.su/functions/?cat=image

        //Устанавливаем отображение сообщений об ошибках
        ini_set ("display_errors", "1");
        error_reporting(E_ALL);

        //Устанавливаем тип содержимого
        header('content-type: image/png');
        //Определяем размеры изображения
        $image = imagecreate(800, 800);
        // Задаём цвет фона
        $colorFON = imagecolorallocate($image, 0, 0, 0);


// ЗАДАЁМ КООРДИНАТЫ:
//ТУЛОВИЩЕ:
        $arrBody = [
            [
                'type' => 'polygon',
                'text' => 'зелёный фон туловища',
                'color' => [161,193,47],
                'coord' => [295,318,477,318,477,452, 295,452],
            ],
            [
                'type' => 'polygon',
                'text' => 'большой почти треугольный воротник над зелёным туловищем',
                'color' => [205,202,195],
                'coord' => [306, 318, 466, 318, 392, 454, 380, 454],
            ],
            [
                'type' => 'polygon',
                'text' => 'того же цвета, что и воротник прямоугольник внизу туловища',
                'color' => [205,202,195],
                'coord' => [295, 453, 477, 453, 477, 498, 295, 498],
            ],
            [
                'type' => 'polygon',
                'text' => 'тёмный прямоугольник под туловищём, над ногами',
                'color' => [63,54,47],
                'coord' => [307,500,466,500,466,536,307,536],
            ],
            [
                'type' => 'ellipse',
                'text' => 'пуговка - красный фон',
                'color' => [160,42,30],
                'coord' => [386, 423, 26,26],
            ],
            [
                'type' => 'ellipse',
                'text' => 'пуговка - желтая сверху',
                'color' => [249,215,79],
                'coord' => [386, 423, 20, 20],
            ],
//    [
//        'type' => '',
//        'text' => '',
//        'color' => ,
//        'coord' => ,
//    ],
        ];


// НОГИ:
        $arrLegs =[
            [
                'type' => 'polygon',
                'text' => 'светлая вертикальная часть левой ступни',
                'color' => [141,134,124],
                'coord' => [308,603,378,602,378,631,308,631],
            ],
            [
                'type' => 'polygon',
                'text' => 'светлая вертикальная часть правой ступни',
                'color' => [141,134,124],
                'coord' => [394,603,464,602,464,631,394,631],
            ],
            [
                'type' => 'polygon',
                'text' => 'тёмная горизонтальная часть левой ступни',
                'color' => [30,27,22],
                'coord' => [321,538,378,538,378,602,308,603],
            ],
            [
                'type' => 'polygon',
                'text' => 'тёмная горизонтальная часть правой ступни',
                'color' => [30,27,22],
                'coord' => [394,538,452,538,464,602,394,603],
            ],
            [
                'type' => 'ellipse',
                'text' => 'стволы ног - левый эллипс',
                'color' => [132,125,115],
                'coord' => [347, 561, 44,20],
            ],
            [
                'type' => 'ellipse',
                'text' => 'стволы ног - правый эллипс',
                'color' => [132,125,115],
                'coord' => [426, 561, 44,20],
            ],
            [
                'type' => 'polygon',
                'text' => 'стволы ног - левый кусок ноги над эллипсом',
                'color' => [132,125,115],
                'coord' => [324,538,370,538,370,561,324,560],
            ],
            [
                'type' => 'polygon',
                'text' => 'стволы ног - правый кусок ноги над эллипсом',
                'color' => [132,125,115],
                'coord' => [404,538,449,538,449,561,404,560],
            ],

        ];

// РУКИ:
        $arrHands = [
            // правая клешня
            [
                'type' => 'polygon',
                'text' => 'правая клешня - горизонтальная плоскость клешни',
                'color' => [104,101,84],
                'coord' => [518,402, 615,402, 630,418, 526,418],
            ],
            [
                'type' => 'polygon',
                'text' => 'правая клешня - вертикальная задняя плоскость (треугольничек темный)',
                'color' => [65,61,50],
                'coord' => [518,402, 528,425, 518,425],
            ],
            [
                'type' => 'polygon',
                'text' => 'правая клешня - вертикальная плоскость клешни',
                'color' => [70,70,80],
                'coord' => [526,418, 630,418, 626,460, 608,460, 605,443, 595,443, 595,460, 563,460, 560,443, 550,443, 548,460, 530,460],
            ],
            // левая клешня
            [
                'type' => 'polygon',
                'text' => 'левая клешня - горизонтальная плоскость клешни',
                'color' => [104,101,84],
                'coord' => [254,402, 157,402, 142,418, 246,418],
            ],
            [
                'type' => 'polygon',
                'text' => 'левая клешня - вертикальная задняя плоскость (треугольничек темный)',
                'color' => [65,61,50],
                'coord' => [254,402, 244,425, 254,425],
            ],
            [
                'type' => 'polygon',
                'text' => 'левая клешня - вертикальная плоскость клешни',
                'color' => [70,70,80],
                'coord' => [246,418, 142,418, 146,460, 164,460, 167,443, 177,443, 177,460, 209,460, 212,443, 222,443, 224,460, 242,460],
            ],
            // тонкие части рук
            [
                'type' => 'polygon',
                'text' => 'тонкая горизонтальная серая часть Левой руки',
                'color' => [141,134,124],
                'coord' => [220,291, 269,291, 269,335, 220,335],
            ],
            [
                'type' => 'polygon',
                'text' => 'тонкая горизонтальная серая часть Правой руки',
                'color' => [141,134,124],
                'coord' => [500,291, 549,291, 549,335, 500,335],
            ],
            [
                'type' => 'polygon',
                'text' => 'тонкая вертикальная серая часть Правой руки',
                'color' => [141,134,124],
                'coord' => [551,369, 594,369, 594,405, 551,405],
            ],
            [
                'type' => 'polygon',
                'text' => 'тонкая вертикальная серая часть Левой руки',
                'color' => [141,134,124],
                'coord' => [221,369, 178,369, 178,405, 221,405],
            ],
            // зелёный рукав рубашки
            [
                'type' => 'polygon',
                'text' => 'зеленая часть Правой руки',
                'color' => [157,184,45],
                'coord' => [477,287, 527,287, 510,341, 477,341],
            ],
            [
                'type' => 'polygon',
                'text' => 'зеленая часть Левой руки',
                'color' => [157,184,45],
                'coord' => [245,287, 296,287, 296,341, 263,341],
            ],
            // правый надлокотник
            [
                'type' => 'ellipse',
                'text' => 'правый кружок внешней части локтя руки',
                'color' => [30,27,22],
                'coord' => [581, 304, 34,34],
            ],
            [
                'type' => 'polygon',
                'text' => 'правая темная часть надлокотника',
                'color' => [30,27,22],
                'coord' => [541,287, 577,287, 598,306, 598,366, 548,367, 522,342],
            ],
            [
                'type' => 'ellipse',
                'text' => 'правый кружок нижней части тёмного надлокотника',
                'color' => [30,27,22],
                'coord' => [573, 367, 51,14],
            ],
            [
                'type' => 'ellipse',
                'text' => 'правый кружок внутренней части локтя руки - заливка фоном',
                'color' => [0,0,0],
                'coord' => [526, 362, 34,34],
            ],
            // левый надлокотник
            [
                'type' => 'ellipse',
                'text' => 'левый кружок внешней части локтя руки',
                'color' => [30,27,22],
                'coord' => [191, 304, 34,34],
            ],
            [
                'type' => 'polygon',
                'text' => 'левый темная часть надлокотника',
                'color' => [30,27,22],
                'coord' => [231,287, 195,287, 174,306, 174,366, 224,367, 250,342],
            ],
            [
                'type' => 'ellipse',
                'text' => 'левый кружок нижней части тёмного надлокотника',
                'color' => [30,27,22],
                'coord' => [199, 367, 51,14],
            ],
            [
                'type' => 'ellipse',
                'text' => 'левый кружок внутренней части локтя руки - заливка фоном',
                'color' => [0,0,0],
                'coord' => [246, 362, 34,34],
            ],

        ];


//ЛИЦО ФОН
// рисуем челюсть
        $arrFaceBack = [
            [
                'type' => 'polygon',
                'text' => 'светлый прямоугольник челюсти',
                'color' => [141,134,124],
                'coord' => [289,271, 483,271, 483,317, 289,317],
            ],
            [
                'type' => 'polygon',
                'text' => 'рот - черный прямоугольник челюсти',
                'color' => [6,5,4],
                'coord' => [297,271, 475,271, 475,309, 297,309],
            ],
            [
                'type' => 'polygon',
                'text' => 'тонкая фронтальная вертикальная часть',
                'color' => [166,161,155],
                'coord' => [286,263, 486,263, 486,271, 286,271],
            ],
            [
                'type' => 'polygon',
                'text' => 'горизонтальная плоскость, на которой будет размещаться нос о_о',
                'color' => [110,100,90],
                'coord' => [296,249, 477,249, 486,263, 286,263],
            ],
            [
                'type' => 'polygon',
                'text' => 'прямоугольный вертикальный фон для лица',
                'color' => [122,115,105],
                'coord' => [296,169, 477,169, 477,249, 296,249],
            ],
            [
                'type' => 'polygon',
                'text' => 'тонкая фронтальная вертикальная часть',
                'color' => [166,161,155],
                'coord' => [313,160, 465,160, 477,169, 296,169],
            ],
        ];



//ЛИЦО ЭЛЕМЕНТЫ ЛИЦА
        $arrFaceFront = [
            //впадина для глаз
            [
                'type' => 'drawRectangleWithRoundedCornerLeftTop',
                'text' => ' левая впадина для глаз',
                'color' => [104,101,84],
                'coord' => [304, 182, 377,248,10],
            ],
            [
                'type' => 'drawRectangleWithRoundedCornerRightTop',
                'text' => 'правая впадина для глаз',
                'color' => [104,101,84],
                'coord' => [396, 182, 468,248,10],
            ],
            // правый глаз
            [
                'type' => 'ellipse',
                'text' => 'правый глаз - черный фон',
                'color' => [62,55,39],
                'coord' => [428, 218, 58,58],
            ],
            [
                'type' => 'ellipse',
                'text' => 'правый глаз - желтый фон',
                'color' => [249,221,96],
                'coord' => [428, 218, 47,47],
            ],
            [
                'type' => 'ellipse',
                'text' => 'правый глаз - черная точка, зрачок',
                'color' => [59,52,44],
                'coord' => [428, 218, 10,10],
            ],
            // левый глаз
            [
                'type' => 'ellipse',
                'text' => 'левый глаз - черный фон',
                'color' => [59,52,44],
                'coord' => [344, 218, 58,58],
            ],
            [
                'type' => 'ellipse',
                'text' => 'левый глаз - желтый фон',
                'color' => [249,221,96],
                'coord' => [344, 218, 47,47],
            ],
            [
                'type' => 'ellipse',
                'text' => 'левый глаз - черная точка, зрачок',
                'color' => [59,52,44],
                'coord' => [344, 218, 10,10],
            ],
            // зубы
            [
                'type' => 'polygon',
                'text' => 'левый зуб',
                'color' => [205,202,197],
                'coord' => [362,272, 379,272, 379,295, 362,295],
            ],
            [
                'type' => 'polygon',
                'text' => 'правый зуб',
                'color' => [205,202,197],
                'coord' => [394,272, 411,272, 411,295, 394,295],
            ],
            // злая стрелка на лбу, типа брови
            [
                'type' => 'polygon',
                'text' => 'стрелка на лбу',
                'color' => [59,52,44],
                'coord' => [361,181, 368,174, 381,187, 381,172, 391,172, 391,187, 405,174, 412,181, 392,201, 381,201],
            ],
            // нос
            [
                'type' => 'polygon',
                'text' => 'фоновый прямоугольник',
                'color' => [59,52,44],
                'coord' => [372,250, 400,250, 400,257, 372,257],
            ],
            [
                'type' => 'ellipse',
                'text' => 'нижний эллипс',
                'color' => [59,52,44],
                'coord' => [386, 257, 27,7],
            ],
            [
                'type' => 'ellipse',
                'text' => 'верхний эллипс',
                'color' => [68,61,53],
                'coord' => [386, 249, 27,10],
            ],
            [
                'type' => 'ellipse',
                'text' => 'самый верхний эллипс',
                'color' => [59,52,44],
                'coord' => [386, 249, 18,8],
            ],
        ];

// ЛАМПОВЫЕ АНТЕННЫ
        $arrLampAnten = [
            // Правая антенна:
            [
                'type' => 'drawRectangleWithRoundedCorners',
                'text' => 'прямоугольная подставка',
                'color' => [45,38,30],
                'coord' => [413,150,434,161, 2],
            ],
            [
                'type' => 'drawRectangleWithRoundedCorners',
                'text' => 'верхняя часть антенны',
                'color' => [235,170,80],
                'coord' => [400,75,447,105, 14],
            ],
            [
                'type' => 'polygon',
                'text' => 'туловище антенны',
                'color' => [235,170,80],
                'coord' => [400,95, 447,95, 434,150, 415,150],
            ],
            [
                'type' => 'polygon',
                'text' => 'усик внутри антенны',
                'color' => [255,156,62],
                'coord' => [413,84, 432,84, 432,88, 425,88, 425,149, 421,149, 420,88, 413,89],
            ],
            // Левая антенна:
            [
                'type' => 'drawRectangleWithRoundedCorners',
                'text' => 'прямоугольная подставка',
                'color' => [45,38,30],
                'coord' => [338,150,359,161, 2],
            ],
            [
                'type' => 'drawRectangleWithRoundedCorners',
                'text' => 'верхняя часть антенны',
                'color' => [235,170,80],
                'coord' => [325,75,372,105, 14],
            ],
            [
                'type' => 'polygon',
                'text' => 'туловище антенны',
                'color' => [235,170,80],
                'coord' => [372,95, 325,95, 338,150, 357,150],
            ],
            [
                'type' => 'polygon',
                'text' => 'усик внутри антенны',
                'color' => [255,156,62],
                'coord' => [359,84, 340,84, 340,88, 347,88, 347,149, 351,149, 352,88, 359,89],
            ],
//    [
//        'type' => 'polygon || ',
//        'text' => '',
//        'color' => ,
//        'coord' => ,
//    ],
        ];

        $allArrays = [$arrBody, $arrLegs, $arrHands, $arrFaceBack, $arrFaceFront, $arrLampAnten];
        foreach ($allArrays as $partArray) {
            foreach ($partArray as $elem) {
                // распарсим цвет
                $r = $elem['color'][0];
                $g = $elem['color'][1];
                $b = $elem['color'][2];
                $color = imagecolorallocate ($image, $r, $g, $b);

                // отрисуем в зависимости от типа
                if ($elem['type'] == 'polygon') {
                    drawPolygonFilled($image, $elem['coord'],  $color);
                }
                if ($elem['type'] == 'ellipse') {
                    drawEllipseFilled($image, $elem['coord'][0], $elem['coord'][1], $elem['coord'][2], $elem['coord'][3],  $color);
                }
                if ($elem['type'] == 'drawRectangleWithRoundedCorners') {
                    drawRectangleWithRoundedCorners($image, $elem['coord'][0], $elem['coord'][1], $elem['coord'][2], $elem['coord'][3], $elem['coord'][4], $color);       // прямоугольная подставка
                }
                if ($elem['type'] == 'drawRectangleWithRoundedCornerRightTop') {
                    drawRectangleWithRoundedCornerRightTop($image, $elem['coord'][0], $elem['coord'][1], $elem['coord'][2], $elem['coord'][3], $elem['coord'][4], $color);       // прямоугольная подставка
                }
                if ($elem['type'] == 'drawRectangleWithRoundedCornerLeftTop') {
                    drawRectangleWithRoundedCornerLeftTop($image, $elem['coord'][0], $elem['coord'][1], $elem['coord'][2], $elem['coord'][3], $elem['coord'][4], $color);       // прямоугольная подставка
                }
            }
        }


        //Сохраняем файл в формате png и выводим его
        imagepng($image);
        //Чистим использованную память
        imagedestroy($image);


    }


}
