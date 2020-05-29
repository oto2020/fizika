<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
//    public function __construct()
//    {
//        $this->middleware('auth');
//    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    // тестовая страница
    public function testPage()
    {
        return view('testtesttest');
    }

    // Вытягивает из БД разделы сайта
    private function getSections()
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
    private function getLessons($sectionID)
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
    private function getSection($sectionURL)
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
    private function getLesson($lessonURL)
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
    private function getTests($lessonID)
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
    private function getTest($testURL)
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
    private function getRole($user)
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



    // страница с каким-либо разделом (Например: 7-class)
    public function showSectionPage($sectionURL) // где section - это url раздела
    {
        // получим пользователя и его роль
        $user = Auth::user();
        $role = $this->getRole($user);

        // ДЛЯ ВЕРХНЕГО МЕНЮ -- СПИСОК РАЗДЕЛОВ (ГЛАВНАЯ, 7 КЛАСС, 8 КЛАСС И ТД,)
        $sections = $this->getSections();
        // Текущий раздел
        $section = $this->getSection($sectionURL);
        // Уроки текущего раздела
        $lessons = $this->getLessons($section->id);
        if($sectionURL == 'main') {
            // КОНТЕНТ Главной страницы
            $lesson = $this->getLesson('glavnaya-stranica');
            return view('mainpage', compact('sections', 'section', 'lessons', 'lesson', 'user', 'role'));
        }
        return view('sectionpage', compact('sections', 'section', 'lessons', 'user', 'role'));
    }





    // страница с каким-нибудь уроком (Например: 7 класс/ урок 1)
    // Route::get('/{section}/{lesson}', 'MainController@showLessonPage');
    public function showLessonPage($sectionURL, $lessonURL)
    {
        // получим пользователя и его роль
        $user = Auth::user();
        $role = $this->getRole($user);
        // ДЛЯ ВЕРХНЕГО МЕНЮ -- СПИСОК РАЗДЕЛОВ (ГЛАВНАЯ, 7 КЛАСС, 8 КЛАСС И ТД,)
        $sections = $this->getSections();
        // Текущий раздел
        $section = $this->getSection($sectionURL);
        // Уроки по разделу
        $lessons = $this->getLessons($section->id);
        // КОНТЕНТ ТЕКУЩЕГО УРОКА
        $lesson = $this->getLesson($lessonURL);
        // ТЕСТЫ ТЕКУЩЕГО УРОКА
        $tests = $this->getTests($lesson->id);
        //dd($tests);
        return view('lessonpage', compact('sections', 'section', 'lessons', 'lesson', 'tests', 'user', 'role'));
    }




    // Страница Добавление урока
    public function addLessonPage($sectionURL)
    {
        if (Auth::user() == null || $this->getRole(Auth::user())->name !== 'Администратор') {
            return redirect('/main')->with('error', 'Только администратор может добавлять уроки!');
        }
        // соберем инфу о всех разделах
        $sections = $this->getSections();
        // для автозаполнения даты
        $date = date('Y-m-d');
        return view('addlesson', compact('sections', 'sectionURL', 'date'));
    }


    // Редактирование существующего урока
    public function editLessonPage($sectionURL, $lessonURL)
    {
        if (Auth::user() == null || $this->getRole(Auth::user())->name !== 'Администратор') {
            return redirect('/main')->with('error', 'Только администратор может редактировать уроки!');
        }
        // соберем инфу о всех разделах
        $sections = $this->getSections();
        // КОНТЕНТ ТЕКУЩЕГО УРОКА
        $lesson = $this->getLesson($lessonURL);
        return view('editlesson', compact('sections', 'lesson', 'sectionURL'));
    }

    // Ставит пометку is_deleted как 1
    public function markAsDeletedLesson($sectionURL, $lessonURL)
    {
        if (Auth::user() == null || $this->getRole(Auth::user())->name !== 'Администратор') {
            return redirect('/main')->with('error', 'Только администратор может помечать уроки, как удалённые!');
        }
        $lesson = $this->getLesson($lessonURL);
        if ($lessonURL == 'glavnaya-stranica') {
            return redirect('/main')->with('error', 'Это возмутительно! Не позволю удалить главную страницу этого сайта!');
        }
        try {
            DB::table('lessons')
                ->where('url', '=', $lessonURL)
                ->update(
                    [
                        'is_deleted' => 1, // а чтобы восстановить - нужно задать значение null
                    ]);
        } catch (\Exception $e) {
            return redirect('/cabinet#map')
                ->with('error', 'Произошла ошибка при попытке пометить урок ['.$lesson->name.'] удалённым. ' . $e->getMessage());
        }

        // все закрепленные за уроком тесты также отмечаются, как удалённые
        try {
            DB::table('test1_tests')
                ->where('lesson_id', '=', $lesson->id)
                ->update(
                    [
                        'is_deleted' => 1, // а чтобы восстановить - нужно задать значение null
                    ]);
        } catch (\Exception $e) {
            return redirect('/cabinet#map')
                ->with('error', 'Урок ['.$lesson->name.'] помечен как удалённый, но при попытке пометить закрепленные тесты как удалённые произошла ошибка. ' . $e->getMessage());
        }


        return redirect('/cabinet#map')
            ->with('message', 'Урок ['.$lesson->name.'] помечен как удалённый и не отображается на сайте. Его можно восстановить или уничтожить оконачательно.');
    }
    // Ставит пометку is_deleted как null
    public function restoreLesson($sectionURL, $lessonURL)
    {
        if (Auth::user() == null || $this->getRole(Auth::user())->name !== 'Администратор') {
            return redirect('/main')->with('error', 'Только администратор может восстанавливать уроки!');
        }
        $lesson = $this->getLesson($lessonURL);
        try {
            DB::table('lessons')
                ->where('url', '=', $lessonURL)
                ->update(
                    [
                        'is_deleted' => null, // восстановили
                    ]);
        } catch (\Exception $e) {
            return redirect('/'.$sectionURL.'/'.$lessonURL)
                ->with('error', 'Произошла ошибка при восстановлении урока ['.$lesson->name.'] . ' . $e->getMessage());
        }
        return redirect('/cabinet#map')
            ->with('message', 'Урок ['.$lesson->name.'] успешно восстановлен!');
    }

    // удалить урок из БД
    public function deleteLesson($sectionURL, $lessonURL)
    {
        if (Auth::user() == null || $this->getRole(Auth::user())->name !== 'Администратор') {
            return redirect('/main')->with('error', 'Только администратор может удалять уроки!');
        }
        $lesson = $this->getLesson($lessonURL);
        if ($lessonURL == 'glavnaya-stranica') {
            return redirect('/main')->with('error', 'Это возмутительно! Не позволю удалить главную страницу этого сайта!');
        }
        // удалим запись из БД
        try {
            DB::table('lessons')->where('url', '=',  $lessonURL)->delete();
        }
        catch (\Exception $e) {
            return redirect('/cabinet#map')->with('error', 'При удалении урока ['.$lesson->name.'] из БД произошла ошибка. 
            Совет: сделайте так, чтобы за уроком не было закреплено ни одного теста. Это можно сделать, удалив тест целиком, или, закрепив его за другим уроком.');
        }
        return redirect('/cabinet#map')->with('message', 'Урок ['.$lesson->name.'] успешно уничтожен!');
    }

    // Страница добавления картинки на сайт
    public function addImgPage()
    {
        $imgName = 'img_'. date('Y.m.d-H.i.s');
        return view('addimg', compact('imgName'));
    }

    // Страница с тестом
    public function showTestPage($sectionURL, $lessonURL, $testURL)
    {
        if (!(Auth::check() && ($this->getRole(Auth::user())->name == 'Администратор' || $this->getRole(Auth::user())->name == 'Ученик'))) {
            return redirect('/main')->with('error', 'Только ученик или администратор может проходить тесты!');
        }
        // получим пользователя и его роль
        $user = Auth::user();
        $role = $this->getRole($user);
        // ДЛЯ ВЕРХНЕГО МЕНЮ -- СПИСОК РАЗДЕЛОВ (ГЛАВНАЯ, 7 КЛАСС, 8 КЛАСС И ТД,)
        $sections = $this->getSections();
        // получим урок, зная его URL
        $lesson = $this->getLesson($lessonURL);
        // получим тест
        $test = $this->getTest($testURL);
        // попробуем получить результат теста вошедшего пользователя. вдруг тест уже пройден?
        try {
            $testResult = DB::table('test4_results')
                ->where('test_id', '=', $test->id)
                ->where('user_id', '=', $user->id)
                ->orderBy('datetime', 'desc')
                ->get()[0];
        }
        catch(\Exception $e) {
            $testResult = null;
        }
        // получим вопросы и варианты ответов  к ним
        $questions_with_answers = DB::table('test2_questions')
            ->where('test_id', '=', $test->id)
            //  вторая присоединяемая таблица,  поле id из первой таблицы,     =            поле question_id из второй таблицы
            ->join('test3_answers', 'test2_questions.id', '=', 'test3_answers.question_id' )
            ->select('test2_questions.name as question', 'test3_answers.id as answer_id', 'test3_answers.name as answer', 'test3_answers.is_valid')
            ->get();
        // сформируем двухуровневый массив, где первый уровень - вопрос, второй уровень - ответы к нему
        $questions = [];
        foreach ($questions_with_answers as $q_a) {
            $questions[$q_a->question][$q_a->answer_id]= ['answer' => $q_a->answer, 'is_valid' => $q_a->is_valid];
        }
        return view('testpage', compact('sections', 'sectionURL', 'lesson', 'test', 'questions', 'user', 'role', 'testResult'));
    }

    //добавление теста
    public function addTest($sectoinURL, $lessonURL)
    {
        if (Auth::user() == null || $this->getRole(Auth::user())->name !== 'Администратор') {
            return redirect('/main')->with('error', 'Только администратор может добавлять тесты!');
        }
        // Информация об уроке, к которому добавляется тест
        $lesson = $this->getLesson($lessonURL);
        $sectionURL = DB::table('sections')
            ->select('id', 'url')
            ->where('id', '=', $lesson->section_id)
            ->get()[0]->url;
        return view('addtest', compact('lesson', 'sectionURL'));
    }

    // Ставит пометку is_deleted как 1
    public function markAsDeletedTest($sectionURL, $lessonURL, $testURL)
    {
        if (Auth::user() == null || $this->getRole(Auth::user())->name !== 'Администратор') {
            return redirect('/main')->with('error', 'Только администратор может помечать тесты, как удалённые!');
        }
        $test = $this->getTest($testURL);
        try {
            DB::table('test1_tests')
                ->where('url', '=', $testURL)
                ->update(
                    [
                        'is_deleted' => 1, // а чтобы восстановить - нужно задать значение null
                    ]);
        } catch (\Exception $e) {
            return redirect('/cabinet#map')
                ->with('error', 'Произошла ошибка при попытке пометить тест ['.$test->name.'] удалённым. ' . $e->getMessage());
        }
        return redirect('/cabinet#map')
            ->with('message', 'Тест ['.$test->name.'] удалён, но не до конца. Вы можете восстановить его или добить окончательно в личном кабинете.');
    }
    // Ставит пометку is_deleted как null
    public function restoreTest($sectionURL, $lessonURL, $testURL)
    {
        if (Auth::user() == null || $this->getRole(Auth::user())->name !== 'Администратор') {
            return redirect('/main')->with('error', 'Только администратор может восстанавливать тесты!');
        }
        $test = $this->getTest($testURL);
        try {
            DB::table('test1_tests')
                ->where('url', '=', $testURL)
                ->update(
                    [
                        'is_deleted' => null, // восстановлено
                    ]);
        } catch (\Exception $e) {
            return redirect('/'.$sectionURL.'/'.$lessonURL.'/'.$testURL)
                ->with('error', 'Произошла ошибка при восстановлении теста ['.$test->name.']. ' . $e->getMessage());
        }
        return redirect('/cabinet#map')
            ->with('message', 'Тест ['.$test->name.'] успешно восстановлен!');
    }

    // удаление теста из БД
    public function deleteTest($sectionURL, $lessonURL, $testURL)
    {
        if (Auth::user() == null || $this->getRole(Auth::user())->name !== 'Администратор') {
            return redirect('/main')->with('error', 'Только администратор может удалять тесты!');
        }

        $test = $this->getTest($testURL);
        // УДАЛЯЯ ВОПРОС - УДАЛЯЕМ ВСЕ ЕГО РЕЗУЛЬТАТЫ
        try {
            DB::table('test4_results')->where('test_id','=', $test->id)->delete();
        }
        catch (\Exception $e) {
            echo 'Не удалось удалить результат прохождения теста'; // ну не удалось, так не удалось. не критично
        }
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

        // Теперь мы имеем ID теста, ID вопросов, ID ответов и можем их снизу-вверх удалить, не нарушив целостность БД
        try {
            foreach ($answersID as $answerID) {
                DB::table('test3_answers')->where('id', '=', $answerID)->delete();
            }
            foreach ($questionsID as $questionID) {
                DB::table('test2_questions')->where('id', '=', $questionID)->delete();
            }
            DB::table('test1_tests')->where('id', '=', $test->id)->delete();
        }
        catch (\Exception $e) {
            return redirect()->back()->with('error', 'При удалении теста ['.$test->name.'] из БД произошла ошибка. ' . $e->getMessage());
        }

        return redirect('/cabinet#map')->with('message', 'Тест ['.$test->name.'] успешно уничтожен!');
    }

    // редактирование теста
    public function editTest($sectionURL, $lessonURL, $testURL)
    {
        if (Auth::user() == null || $this->getRole(Auth::user())->name !== 'Администратор') {
            return redirect('/main')->with('error', 'Только администратор может редактировать тесты!');
        }
        // получим урок, зная его URL
        $lesson = $this->getLesson($lessonURL);
        // получим тест
        $test = $this->getTest($testURL);
        // получим вопросы и варианты ответов  к ним
        $questions_with_answers = DB::table('test2_questions')
            ->where('test_id', '=', $test->id)
            //  вторая присоединяемая таблица,  поле id из первой таблицы,     =            поле question_id из второй таблицы
            ->join('test3_answers', 'test2_questions.id', '=', 'test3_answers.question_id' )
            ->select('test2_questions.name as question', 'test3_answers.id as answer_id', 'test3_answers.name as answer', 'test3_answers.is_valid')
            ->get();
        // сформируем двухуровневый массив, где первый уровень - вопрос, второй уровень - ответы к нему
        $questions = [];
        foreach ($questions_with_answers as $q_a) {
            $questions[$q_a->question][$q_a->answer_id]= ['answer' => $q_a->answer, 'is_valid' => $q_a->is_valid];
        }
        // подготовим список всех уроков
        $section = $this->getSection($sectionURL);
        $lessons = DB::table('lessons')->select('id', 'name')->get();
        return view('edittest', compact( 'lessons','sectionURL', 'lesson', 'test', 'questions'));
    }

    // Страница личного кабинета
    public function cabinetPage() {
        // получим пользователя и его роль
        $user = Auth::user();
        $role = $this->getRole($user);
        // если не войден или неподтвержден - редирект с ошибкой
        if(!Auth::check() || $role == null || $role->name == 'Неподтверждённый') {
            return redirect('/main')->with('error', 'Личный кабинет не доступен. Войдите в систему и подтвердите свою учётную запись.');
        }
        // если админ - подготовим всё для админа
        if($role->name == "Администратор") {

            // для верхнего меню
            $sections = $this->getSections();

// контроль за другими пользователями
            $users_tmp = DB::table('users')
                ->select('id', 'name', 'user_role_id', 'class_name', 'email', 'verified_at', 'password', 'created_at')
                ->get();
            //dd($users_tmp);
            // сгруппируем пользователей по классам
            $users = [];
            // так как orderBy работает через одно место - сформируем массив вручную!
            foreach ($users_tmp as $user_tmp) {
                if ($user_tmp->class_name == 'Учителя') $users ['Учителя'] []= $user_tmp;
            }
            foreach ($users_tmp as $user_tmp) {
                if ($user_tmp->class_name == '7 класс') $users ['7 класс'] []= $user_tmp;
            }
            foreach ($users_tmp as $user_tmp) {
                if ($user_tmp->class_name == '8 класс') $users ['8 класс'] []= $user_tmp;
            }
            foreach ($users_tmp as $user_tmp) {
                if ($user_tmp->class_name == '9 класс') $users ['9 класс'] []= $user_tmp;
            }
            foreach ($users_tmp as $user_tmp) {
                if ($user_tmp->class_name == '10 класс') $users ['10 класс'] []= $user_tmp;
            }
            foreach ($users_tmp as $user_tmp) {
                if ($user_tmp->class_name == '11 класс') $users ['11 класс'] []= $user_tmp;
            }
            // Список всех ролей (чтобы пользователю можно было переназначить роль)
            $roles = DB::table('user_roles')
                ->get();

// Карта сайта
            $lessons = [];
            // соберем уроки по разделам
            foreach ($sections as $keySection => $section) { // да да, запросы к БД в цикле это плохо, но кто меня остановит?
                $class_lessons = DB::table('lessons')
                    ->select('id', 'name', 'date', 'preview_text', 'section_id', 'url', 'content', 'user', 'full_url', 'is_deleted')
                    ->where('section_id', '=', $section->id)
                    ->orderBy('is_deleted', 'asc')
                    ->get();
                foreach ($class_lessons as $lesson) {
                    $tests = DB::table('test1_tests')
                        ->select('id', 'lesson_id', 'name', 'url', 'preview_text', 'user', 'full_url', 'is_deleted')
                        ->where('lesson_id', '=', $lesson->id)
                        ->orderBy('is_deleted', 'asc')
                        ->get();
                    $lessons[$section->name][]= [
                        'name'=>$lesson->name,
                        'section_url' => $section->url,
                        'url' => $lesson->url,
                        'full_url'=> $lesson->full_url,
                        'is_deleted' => $lesson->is_deleted,
                        'id' => $lesson->id,
                        'tests' => $tests
                    ]; // собираем только то, что потребуется
                }
            }

// результаты тестов
            $test_results = DB::table('test4_results')
                ->join('test1_tests', 'test1_tests.id', '=', 'test4_results.test_id')
                ->select(
                    'test1_tests.id as test_id',
                    'test1_tests.name as test_name',
                    'test1_tests.full_url as test_full_url',
                    'test4_results.id as result_id',
                    'test4_results.point as point',
                    'test4_results.datetime as datetime',
                    'test4_results.details as details',
                    'test4_results.user_name as user_name',
                    'test4_results.user_id as user_id',
                    'test4_results.id as result_id')
                ->get();
            //dd($test_results);
            $testResultsByTests = [];
            $testResultsByUsers = [];
            foreach ($test_results as $result) {
                $testResultsByTests[$result->test_name][] = $result; // группировка по имени теста
                $testResultsByUsers[$result->user_name][] = $result; // группировка по имени ученика
            }

            //dd ($testResults);

// удаленные Уроки/Тесты
            $deletedLessons = DB::table('lessons')
                ->select('id', 'name', 'url', 'full_url')
                ->where('is_deleted', '!=', null)
                ->get();
            $deletedTests = DB::table('test1_tests')
                ->where('is_deleted', '!=', null)
                ->get();
            //dump('Зашел админ!', $user->name, $role);
            return view('cabinet', compact( 'sections','user', 'users', 'role', 'roles', 'lessons', 'testResultsByTests', 'testResultsByUsers', 'deletedLessons', 'deletedTests'));
        }


// -----------------------------------------------ЛИЧНЫЙ КАБИНЕТ УЧЕНИКА -----------------------
        // ну а тут подготовим для ученика кабинет
        if($role->name == "Ученик") {
            // для верхнего меню
            $sections = $this->getSections();
            // соберем результаты тестов для этого пользователя
            $testResults = DB::table('test4_results')
                ->join('test1_tests', 'test1_tests.id', '=', 'test4_results.test_id')
                ->where('test4_results.user_id', '=', $user->id)
                ->select(
                    'test1_tests.id as test_id',
                    'test1_tests.name as test_name',
                    'test1_tests.full_url as test_full_url',
                    'test4_results.id as result_id',
                    'test4_results.point as point',
                    'test4_results.datetime as datetime',
                    'test4_results.details as details',
                    'test4_results.user_name as user_name',
                    'test4_results.user_id as user_id',
                    'test4_results.id as result_id')
                ->get();

            //dd($testResults);

            return view('cabinetschoolar', compact( 'sections','user', 'role', 'testResults'));
        }
    }
}

