<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class PostController extends Controller
{
//    private function remove4bytesCharFromUtf8Str($str)
//    {
//        return preg_replace_callback(
//            '/./u',
//            function (array $match) {return strlen($match[0]) >= 4 ? '<font color="red">[этот символ не поддерживается]</font>' : $match[0];},
//            $str
//        );
////        return preg_replace('/([\xF0-\xF7])/s', '#', $str);
//    }
// Добавление урока в БД и формирование html-файла
    public function addLessonPOST(Request $request, $sectionURL)
    {
//        $content = $this->remove4bytesCharFromUtf8Str($request->html_content);
        $arrayToInsert = [
            'name' => $request->lesson_name,
            'date' => $request->date,
            'preview_text' => $request->preview_text,
            'url' => $request->url,
            'full_url' => '/' . $sectionURL . '/' . $request->url,
            'section_id' => $request->section,
            'content' => $request->html_content, //$content,
            'user' => $request->user,
            'is_deleted' => null,
        ];
        // попытаемся добавить запись в БД
        try {
            DB::table('lessons')->insert($arrayToInsert);
        } // если вылетела ошибка
        catch (\Exception $exc) {
            dd('При добавлении записи в БД произошла ошибка. '.$exc->getMessage());
            return redirect()->back()->with('error', 'При добавлении записи в БД произошла ошибка. '.$exc->getMessage());
        }
        $this->mylog('warning', 'Добавил страницу: /' . $sectionURL . '/' . $request->url);
        return redirect('/' . $sectionURL . '/' . $request->url)->with('message', 'Страница успешно размещена!');
    }

    // редактирование урока в БД
    public function editLessonPOST(Request $request, $sectionURL)
    {
        $lessonID = $request->id_from_database;
        $sectionID = $request->section;
        $section = DB::table('sections')->where('id','=', $sectionID)->get()[0];
        // нужно помнить, что если мы переместим урок в другой раздел, то у урока и привязанных к нему тестах изменится full_url
        try {
            // проведём апдейт записи в таблице
            DB::table('lessons')
                ->where('id', '=', $lessonID)
                ->update(
                    [
                        'name' => $request->lesson_name,
                        'date' => $request->date,
                        'preview_text' => $request->preview_text,
                        'url' => $request->url,
                        'full_url' => '/' . $section->url . '/' . $request->url,
                        'section_id' => $section->id,
                        'content' => $request->html_content,
                        'user' => $request->user,
                    ]);
                }
        catch (\Exception $e) {
            return redirect()->back()->with('error', 'При обновлении записи БД произошла ошибка. '.$e->getMessage());
        }

        // проведем апдейт full_url для привязанных к уроку тестов
        try {
            $test_url = DB::table('test1_tests')
                ->where('lesson_id', '=', $lessonID)->select('lesson_id', 'url')->get()[0]->url;
            DB::table('test1_tests')
                ->where('lesson_id', '=', $lessonID)
                ->update(['full_url' => '/' . $section->url . '/' . $request->url . '/' . $test_url]);
        }
        catch (\Exception $e) {
            //return redirect()->back()->with('message', 'Страница успешно отредактирована! Но привязанные тесты не обновили свой full_url. Обновите привязанные тесты!');
        }
        $href = '/' . $sectionURL . '/' . $request->url;
        if ($request->url == 'glavnaya-stranica') $href = '/main';
        $this->mylog('warning', 'Произвел редактирование страницы: /' . $sectionURL . '/' . $request->url);
        return redirect($href)->with('message', 'Страница успешно отредактирована!');
    }

    //Загружает картинку на сайт
    public function addImgPOST(Request $request)
    {
        if ($request->isMethod('post')) {
            $request_all = $request->all();
            if ($request->hasFile('images')) {//echo 'один файл точно есть';
                $i = 1;
                foreach ($request->images as $file) {
                    // имя, под которым будет сохранен файл на сервере
                    $futureFileName = $request->img_future_name . '_' . $i . '.' . $file->getClientOriginalExtension();
                    Storage::putFileAs('/public/img/', $file, $futureFileName, 'public');
                    // отобразим сообщение об успешной загрузке файла
                    echo '<hr>Файл '.$i.' [' . $futureFileName . '] успешно загружен!<br>';
                    //echo htmlspecialchars('<img src="/storage/img/' . $futureFileName . '" class="img-fluid" alt="Картинка не найдена">');
                    echo '<br><div id="img_'.$i.'"><img src="/storage/img/' . $futureFileName . '" class="img-fluid" width="150px" alt="Картинка не найдена"></div><br>';
//                    echo '<textarea rows="2" cols="97" id="cont_' . $i . '"></textarea>';
//                    echo '<script>document.getElementById("cont_' . $i . '").value = document.getElementById("img_'.$i.'").innerHTML;</script>';
                    //echo '<br>Скопируйте ссылку и добавьте изображение с помощью соответсвующей кнопки в CKEditor.';
                    echo '<br>Ссылка на файл:<br>';
                    echo '<textarea rows="2" cols="97" id="cont_' . $i . '">';
                    echo '/storage/img/' . $futureFileName;
                    echo '</textarea><br>';
                    echo '<button onclick="copyToClipBoard_'.$i.'()">Копировать в буфер обмена</button>';

                    // подключаем обработчик по клику на кнопку "Копировать в буфер обмена"
                    echo '
                    <script>
                        function copyToClipBoard_'.$i.'() {
                            let copyText = document.getElementById(\'cont_'.$i.'\');
                            copyText.select();
                            document.execCommand("copy");
                            alert("Скопирована ссылка: " + copyText.value + "\n\nИспользуйте её как url при размещении изображения на сайте.");
                        }
                    </script>';
                    $i++;
                }

            } else {
                return redirect('/add_img')->with('error', 'Выберите файл (файлы)');
            }
        }
        $this->mylog('warning', 'Загрузил на сайт картинки с префиксом: ' . $request->img_future_name);
    }


    // Получает результаты теста и проверяет их
    public function verificateTest(Request $request, $testURL)
    {
        $your_answers = $request->all();
        // получим тест
        $test = DB::table('test1_tests')
            ->select('id', 'name', 'url', 'preview_text', 'full_url', 'user')
            ->where('url', '=', $testURL)
            ->orderBy('id', 'asc')
            ->get()[0];

        // получим вопросы и варианты ответов  к ним
        $correctAnswers = DB::table('test2_questions')
            ->where('test_id', '=', $test->id)
            //  вторая присоединяемая таблица,  поле id из первой таблицы,     =            поле question_id из второй таблицы
            ->join('test3_answers', 'test2_questions.id', '=', 'test3_answers.question_id')
            ->where('test3_answers.is_valid', '=', 1)
            ->select('test2_questions.name as question', 'test3_answers.id as answer_id', 'test3_answers.name as answer')
            ->get();
        $count = count($correctAnswers);
        $arResult = [];
        foreach ($correctAnswers as $index => $answer) {
            $arResult[$answer->question] = [
                'result' => ($your_answers['question_' . ($index + 1)] === $correctAnswers[$index]->answer) ? 'OK' : 'FAIL',
                'your_answer' => $your_answers['question_' . ($index + 1)],
                'correct_answer' => $correctAnswers[$index]->answer
            ];
        }

        $result = $count;
        $correctCount = 0;
        foreach ($arResult as $res) {
            if ($res['result'] === 'OK') {
                $correctCount++;
            }
        }

        $point = (int)ceil($correctCount / $count * 100);

        // вот тут попытаемся сохранить в базу
        try {
            // получим пользователя и его роль
            $user = Auth::user();
            $json = json_encode($arResult);
            DB::table('test4_results')->insert(
                [
                    'user_id' => $user->id,
                    'test_id' => $test->id,
                    'user_name' =>$user->name,
                    'point' => $point,
                    'details' => $json,
                    'datetime' => now(),
                ]);
            $this->mylog('info', 'Завершил прохождение теста: ' . $test->full_url . ' С результатом: ' . $point);
            return redirect()->back()->with('message', 'Тест пройден, результат сохранён.');
        }
        catch(\Exception $e) {
            return redirect()->back()->with('error', '!!! РЕЗУЛЬТАТЫ ТЕСТА НЕ СОХРАНЕНЫ  В БД! Ваш результат: ' . $point . '%');
        }
    }

    // размещает тест в БД (адищще)
    public function addTestPOST(Request $request, $lessonURL)
    {
        // зная lessonURL получим сам lesson:
        $lesson = DB::table('lessons')
            ->select('id', 'url', 'full_url')
            ->where('url', '=', $lessonURL)
            ->get()[0];
        // Для добавления вопросов->ответов нам нужно знать четко их ID и при этом ID должен быть уникальным
        // ID поледнего теста (наименьший свободный ID)
        // Если id теста указан явно (в случае редактирования теста, где мы удалили его, а потом снова добавили) -- в этот id и запишем
        if (isset($request->test_id)) {
            $testID = $request->test_id;
        }
        else {
            $testID = DB::table('test1_tests')
                    ->select('id')
                    ->orderBy('id', 'desc')
                    ->get()[0]->id + 1;
        }

        // ID поледнего вопроса (наименьший свободный ID)
        $questionID = DB::table('test2_questions')
                ->select('id')
                ->orderBy('id', 'desc')
                ->get()[0]->id + 1;

        // ID поледнего ответа (наименьший свободный ID)
        $answerID = DB::table('test3_answers')
                ->select('id')
                ->orderBy('id', 'desc')
                ->get()[0]->id + 1;
        //dd($latestTestID, $latestQuestionID, $latestAnswerID);


        // получим массив со всеми полями из POST
        $arr = $request->all();

        // очистим пустые поля
        foreach ($arr as $key => $elem) {
            if ($elem == null || $key == '_token') {
                unset($arr[$key]);
            }
        }

        // ДОБАВИМ ТЕСТ В БД ++
        try {
            DB::table('test1_tests')->insert(
                [
                    'id' => $testID,            // наименьший свободный ID
                    'lesson_id' => $arr['lesson_id'],
                    'name' => $arr['test_name'],
                    'url' => $arr['url'],
                    'full_url' => $lesson->full_url . '/' . $arr['url'],
                    'preview_text' => $arr['preview_text'],
                    'user' => Auth::user()->name,
                    'is_deleted' => null,
                ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'При добавлении теста в БД произошла ошибка. ' . $e->getMessage());
        }
        // получим массив вопросов,ответов и правильных ответов
        $questions = [];
        $answers = [];
        $correct_answers = [];
        foreach ($arr as $key => $elem) {
            if (preg_match('/^question_/', $key)) {
                $questions[$key] = $elem;
            }
            if (preg_match('/^answer_/', $key)) {
                $answers[$key] = $elem;
            }
            if (preg_match('/^correct_answer_from_question_/', $key)) {
                $tmpArr = explode('_', $elem);
                $correct_answers[$tmpArr[0]] = $tmpArr[1];
            }
        }

        // обработка массива с вопросами
        foreach ($questions as $questionCODE => $question) {
            // узнаем номер вопроса
            $tmpArr = explode('_', $questionCODE);
            $questionNomer = array_pop($tmpArr);
            //dump($questionNomer);

            // запишем вопрос в БД ++
            try {
                DB::table('test2_questions')->insert(
                    [
                        'id' => $questionID,
                        'test_id' => $testID,
                        'name' => $question
                    ]
                );
            } catch (\Exception $e) {
                echo '<h1>При добавлении вопроса в БД произошла ошибка.</h1>';
                echo '<font color="red">' . $e->getPrevious()->getMessage() . '</font><br><br>';
                echo '<input type="button" onclick="history.back();" value="Назад"/><br><br>';
                die();
            }
            // узнаем правильный ответ на этот вопрос
            $correctAnswerNom = 0;
            foreach ($correct_answers as $questionNom => $answerNom) {
                if ($questionNom == $questionNomer) {
                    $correctAnswerNom = $answerNom;
                }
            }

            // запишем все ответы в БД
            foreach ($answers as $answerCODE => $answer) {
                // выберем только те ответы, которые предназначены нашему вопросу
                $tmpArr = explode('_', $answerCODE);
                $answerNom = array_pop($tmpArr);
                $questionNom = array_pop($tmpArr);
                // если номер ответа, к которому прикреплен вопрос соответствует текущему:
                if ($questionNom == $questionNomer) {
                    try {
                        DB::table('test3_answers')->insert(
                            [
                                'id' => $answerID,
                                'question_id' => $questionID,
                                'name' => $answer,
                                'is_valid' => ($answerNom == $correctAnswerNom) ? 1 : 0 // если номер текущего ответа соответствует номеру правильного ответа
                            ]
                        );
                    } catch (\Exception $e) {
                        echo '<h1>При добавлении варианта ответа в БД произошла ошибка.</h1>';
                        echo '<font color="red">' . $e->getPrevious()->getMessage() . '</font><br><br>';
                        echo '<input type="button" onclick="history.back();" value="Назад"/><br><br>';
                        die();
                    }
                    $answerID++;
                }
            }
            $questionID++;
        }

        // получим инфу об уроке
        $lesson = DB::table('lessons')
            ->select('id', 'url', 'section_id')
            ->where('id', '=', $arr['lesson_id'])
            ->get()[0];

        // получим url раздела
        $sectionURL = DB::table('sections')
            ->select('id', 'url')
            ->where('id', '=', $lesson->section_id)
            ->get()[0]->url;

        $this->mylog('warning', 'Добавил тест: /'.$sectionURL.'/'.$lesson->url.'/'.$arr['url']);
        return redirect('/'.$sectionURL.'/'.$lesson->url .'/'.$arr['url'])->with('message', 'Тест ['.$request->test_name.'] успешно сохранен!');
    }

    // апдейт полей теста, удаление всех вопросов и ответов + перезалив оных (адищще)
    public function editTestPOST(Request $request, $testURL)
    {
        $test = DB::table('test1_tests')->where('url', '=', $testURL)->get()[0];
        $lesson = DB::table('lessons')
            ->select('id', 'url', 'full_url', 'section_id')
            ->where('id', '=', $request->lesson_id)
            ->get()[0];
        // получим массив со всеми полями из POST
        $arr = $request->all();
        // очистим пустые поля
        foreach ($arr as $key => $elem) {
            if ($elem == null || $key == '_token') {
                unset($arr[$key]);
            }
        }

        // в поле lesson_name лежит имя нового урока, к которому нужно перепривязать тест. нужно узнать id этого урока
        $newLesson = DB::table('lessons')
            ->select('id', 'name', 'url', 'full_url')
            ->where('name', '=', $arr['lesson_name'])
            ->get()[0];

        // ОБНОВИМ ТЕСТ В БД ++
        try {
            DB::table('test1_tests')
                ->where('id', '=', $test->id)
                ->update(
                    [
                        'lesson_id' => $newLesson->id,
                        'name' => $arr['test_name'],
                        'url' => $arr['url'],
                        'full_url' => $newLesson->full_url . '/' . $arr['url'],
                        'preview_text' => $arr['preview_text'],
                        'user' => Auth::user()->name,
                    ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'При обновлении теста в БД произошла ошибка. ' . $e->getMessage());
        }
// ТЕСТ АПДЕЙТНУТ, А ВОТ ВОПРОСЫ И ОТВЕТЫ МОЖНО УДАЛИТЬ, чтобы потом залить заново
        // получим все вопросы
        $questions = DB::table('test2_questions')
            ->select('id', 'test_id')
            ->where('test_id', '=', $test->id)
            ->get();
        // выберем ID удаляемых вопросов
        $questionsID = [];
        foreach ($questions as $question) {
            $questionsID[] = $question->id;
        }
        //получим все ответы, от которых зависят вопросы
        $answersID = [];
        foreach ($questionsID as $questionID) {
            $answers = DB::table('test3_answers')
                ->select('id', 'question_id')
                ->where('question_id', '=', $questionID)
                ->get();
            foreach ($answers as $ans) {
                $answersID[] = $ans->id;
            }
        }
        // Теперь мы имеем ID вопросов, ID ответов и можем их снизу-вверх удалить, не нарушив целостность БД
        try {
            foreach ($answersID as $answerID) {
                DB::table('test3_answers')->where('id', '=', $answerID)->delete();
            }
            foreach ($questionsID as $questionID) {
                DB::table('test2_questions')->where('id', '=', $questionID)->delete();
            }
        }
        catch (\Exception $e) {
            return redirect()->back()->with('error', 'При удалении теста ['.$test->name.'] из БД произошла ошибка. ' . $e->getMessage());
        }

// ВОПРОСЫ И ОТВЕТЫ УДАЛЕНЫ. ТЕПЕРЬ НУЖНО ИХ ЗАЛИТЬ.
    // ID поледнего вопроса (наименьший свободный ID)
        $questionID = DB::table('test2_questions')
                ->select('id')
                ->orderBy('id', 'desc')
                ->get()[0]->id + 1;
    // ID поледнего ответа (наименьший свободный ID)
        $answerID = DB::table('test3_answers')
                ->select('id')
                ->orderBy('id', 'desc')
                ->get()[0]->id + 1;
        // получим массив со всеми полями из POST
        $arr = $request->all();
        // очистим пустые поля
        foreach ($arr as $key => $elem) {
            if ($elem == null || $key == '_token') {
                unset($arr[$key]);
            }
        }

        // сформируем массив вопросов, ответов и правильных ответов на основании данных из POST
        $questions = [];
        $answers = [];
        $correct_answers = [];
        foreach ($arr as $key => $elem) {
            if (preg_match('/^question_/', $key)) {
                $questions[$key] = $elem;
            }
            if (preg_match('/^answer_/', $key)) {
                $answers[$key] = $elem;
            }
            if (preg_match('/^correct_answer_from_question_/', $key)) {
                $tmpArr = explode('_', $elem);
                $correct_answers[$tmpArr[0]] = $tmpArr[1];
            }
        }

        // обработка массива с вопросами
        foreach ($questions as $questionCODE => $question) {
            // узнаем номер вопроса
            $tmpArr = explode('_', $questionCODE);
            $questionNomer = array_pop($tmpArr);
            //dump($questionNomer);

            // запишем вопрос в БД ++
            try {
                DB::table('test2_questions')->insert(
                    [
                        'id' => $questionID,
                        'test_id' => $test->id,
                        'name' => $question
                    ]
                );
            } catch (\Exception $e) {
                echo '<h1>При добавлении вопроса в БД произошла ошибка.</h1>';
                echo '<font color="red">' . $e->getMessage() . '</font><br><br>';
                echo '<input type="button" onclick="history.back();" value="Назад"/><br><br>';
                die();
            }
            // узнаем правильный ответ на этот вопрос
            $correctAnswerNom = 0;
            foreach ($correct_answers as $questionNom => $answerNom) {
                if ($questionNom == $questionNomer) {
                    $correctAnswerNom = $answerNom;
                }
            }

            // запишем все ответы в БД
            foreach ($answers as $answerCODE => $answer) {
                // выберем только те ответы, которые предназначены нашему вопросу
                $tmpArr = explode('_', $answerCODE);
                $answerNom = array_pop($tmpArr);
                $questionNom = array_pop($tmpArr);
                // если номер ответа, к которому прикреплен вопрос соответствует текущему:
                if ($questionNom == $questionNomer) {
                    try {
                        DB::table('test3_answers')->insert(
                            [
                                'id' => $answerID,
                                'question_id' => $questionID,
                                'name' => $answer,
                                'is_valid' => ($answerNom == $correctAnswerNom) ? 1 : 0 // если номер текущего ответа соответствует номеру правильного ответа
                            ]
                        );
                    } catch (\Exception $e) {
                        echo '<h1>При добавлении варианта ответа в БД произошла ошибка.</h1>';
                        echo '<font color="red">' . $e->getPrevious()->getMessage() . '</font><br><br>';
                        echo '<input type="button" onclick="history.back();" value="Назад"/><br><br>';
                        die();
                    }
                    $answerID++;
                }
            }
            $questionID++;
        }
        // ТАКИМИ ВОТ МУЧЕНИЯМИ ЗАПИСАНЫ НОВЫЕ ВОПРОСЫ И ОТВЕТЫ.
        $this->mylog('warning', 'Произвел редактирование теста: /' . $newLesson->full_url);
        return redirect($newLesson->full_url . '/' . $arr['url'])->with('message', 'Тест ['.$test->name.'] успешно сохранен!');
    }

// Получает на вход массив со всеми пользователями и проводит их апдейт
public function editUsersPOST(Request $request)
{
    $this->mylog('warning', 'Произвел запрос на редактирование пользователей');
    $messages = [];
    $errors = [];
    $allFields = $request->all();
    // отсекаем лишнее
    unset($allFields['_token']);

    // приведем массив к виду id => [$user]
    $users = [];
    // пока что имеем вид: "user_role_name|6" => "Администратор"
    foreach ($allFields as $fieldNameID => $fieldValue) {
        // разобьем запись типа "user_role_name|6" на две составляющие: имя поля и id
        $tmp_arr = explode('|', $fieldNameID);

        $fieldName = $tmp_arr[0];
        $id = $tmp_arr[1];
        $users[$id] [$fieldName] = $fieldValue;
    }


    // Список всех ролей (чтобы пользователю можно было переназначить роль)
    $roles = DB::table('user_roles')
        ->get();
    // проведем апдейт пользователей:
    foreach ($users as $id => $user) {
        // если нужно кому-то сбрасывать аватар
        if (array_key_exists('reload_avatar', $user) && $user['reload_avatar'] == 'on') {
            // ОБНОВИМ АВУ
            try {
                // текущий пользователь
                $tmpUser = DB::table('users')
                    ->select('id', 'name')
                    ->where('id','=', $id)
                    ->get()[0];
                // генерим ему новую аву
                $this->generateSaveAvatar($tmpUser->id, $tmpUser->name);
            }
            catch (\Exception $e) {
                $errors[] = 'Произошла ошибка при переформировании аватарки пользователя: '.$user['name'].'('.$user['email'] .')' . $e->getMessage();
                $this->mylog('error', 'Произошла ошибка при переформировании аватарки пользователя: '.$user['name'].'('.$user['email'].')'. $e->getMessage());
            }
            $messages[]= 'Сброшен аватар пользователя: '.$user['name'].'('.$user['email'].')';
            $this->mylog('info', 'Сброшен аватар пользователя: '.$user['name'].'('.$user['email'].')');
        }
        unset($user['reload_avatar']);
        if (array_key_exists('delete_user', $user) && $user['delete_user'] == 'on') {
            // УДАЛЯЯ ПОЛЬЗОВАТЕЛЯ - УДАЛЯЕМ ВСЕ ЕГО РЕЗУЛЬТАТЫ
            try {
                DB::table('test4_results')->where('user_id','=', $id)->delete();
                $this->mylog('alert', 'Удалил результаты тестов для пользователя: '.$user['name'].'('.$user['email'].')');
            }
            catch (\Exception $e) {
                echo 'Не удалось удалить результат прохождения теста'; // ну не удалось, так не удалось. не критично
            }
            // попробуем удалить пользователя
            try {
                DB::table('users')->where('id', '=', $id)->delete();
                $messages []= 'Пользователь [' . $user['name'] . '] из [' . $user['class_name'] . '] и все его результаты удалены.';
                $this->mylog('alert', 'Удалил пользователя: '. $user['name'] . ' из ' . $user['class_name']);
                continue; // так как пользователь удалён -- дальнейшие действия с ним бесполезны
            }
            catch (\Exception $e) {
                $errors []= 'Не удалось удалить пользователя: ' . $e->getMessage();
            }
        }
        if ($user['password']!=null) {
            $user['password'] = Hash::make($user['password']);
            $this->mylog('alert', 'Обновил пароль пользователя: '. $user['name'] . ' из ' . $user['class_name'].'. Новый пароль: '.$user['password']);
            $messages []= 'Пароль для пользователя [' . $user['name'] . '] из [' . $user['class_name'] . '] обновлён.';
        }
        else {
            unset($user['password']);
        }
        try {
            // получим текущего пользователя, чтобы узнать, какие именно изменения будут записаны
            DB::table('users')
                ->where('id', '=', $id)
                ->get()[0];
            // TODO: доделать логгирование


            DB::table('users')
                ->where('id', '=', $id)
                ->update($user);
        } catch (\Exception $e) {
            $errors []= 'Не удалось обновить данные пользователя ['.$user['name'].']. ПАРОЛЬ ТАКЖЕ НЕ СБРОШЕН. ' . $e->getMessage();
        }
    }
    // TODO: доделать логи в PostController.php
    return redirect()->back()->with('errors', $errors)->with('messages', $messages)->with('message', 'Изменения применены');
}

    //удаляет результат прохождения теста
    public function deleteTestResultPOST (Request $request)
    {
        try {
            DB::table('test4_results')->where('id','=', $request->id)->delete();
            return redirect('/cabinet#' . $request->redirect_to)->with('message', 'Результат прохождения теста успешно удалён. ');
        }
        catch (\Exception $e) {
            return redirect()->back()->with('error', 'Не удалось удалить результат прохождения теста. ' . $e->getMessage());
        }
    }

    // адейт информации о пользователе из Личного кабинета пользователя
    public function changeUserInfoPOST(Request $request)
    {
        $user = Auth::user();
        $messages = [];
        $errors =[];

        // проведем апдейт емейла:
        if ($request->email != null) {
            // кусок проверки из RegisterController
            if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
                return back()->with('error', 'Некорректный e-mail');
            }
            // попробуем найти пользователя с таким же email
            try {
                $userWithAnalogEmail = DB::table('users')
                    ->select('email', 'id')
                    ->where('email', '=', $request->email)
                    ->get()[0];
            }
            catch (\Exception $e) {
                $userWithAnalogEmail = null;
            }
            // если такой емаил уже есть в системе и занят он не текущим пользователем
            if ($userWithAnalogEmail!==null && $userWithAnalogEmail->id !== $user->id) {
                return back()->with('error', 'Выбранный email занят');
            }
            // попробуем обновить запись в таблице
            try {
                DB::table('users')
                    ->where('id', '=', $user->id)
                    ->update(['email' => $request->email]);
                $messages []= 'Ваш новый email: '.$request->email;
            } catch (\Exception $e) {
                $errors []= 'Не удалось обновить email пользователя ['.$user->name.']. Обратитесь к администратору. ' . $e->getMessage();
            }
        }

        // проведем апдейт пароля:
        if ($request->password != null) {
            // кусок проверки из RegisterController
            if (strlen($request->password) < 4) {
                return back()->with('error', 'Длина пароля должна быть хотя бы 4 символа');
            }
            // попытаемся обновить пароль в таблице
            $password = Hash::make($user['password']);
            try {
                DB::table('users')
                    ->where('id', '=', $user->id)
                    ->update(['password' => $password]);
                $messages []= 'Ваш новый пароль: '.$request->password;
            } catch (\Exception $e) {
                $errors []= 'Не удалось обновить пароль пользователя ['.$user->name.']. Обратитесь к администратору. ' . $e->getMessage();
            }
        }

        return redirect('/cabinet')->with('messages', $messages)->with('errors', $errors);
    }

    public function reloadAvatar (Request $request)
    {
        $last_page = URL::previous();
        try {
            $this->generateSaveAvatar($request->userId, $request->userName);
        } catch (\Exception $e) {
            return redirect($last_page)->with('error', 'Не удалось обновить аватарку. '); $e->getMessage();
        }
        return redirect($last_page)->with('message', 'Аватарка обновлена. Если изменения не заметны нажмите Ctrl+F5');
    }
}
















