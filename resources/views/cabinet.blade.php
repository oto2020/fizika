<!DOCTYPE html>
<html lang="ru">
<head>
    <title>{{$role->name}} [Личный кабинет]</title>
    <link rel="icon" href="/storage/img/icon_1.ico" type="image/x-icon">
    <meta charset="utf8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="{{asset('/css/bootstrap.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('/css/main.css')}}" rel="stylesheet" type="text/css">
</head>
<body>
<div class="wrapper" >
    <!--костыль, чтобы экран не фокусировался на табе при клике!-->
    <div id="users"></div>
    <div id="map"></div>
    <div id="test_results"></div>
    <div id="user_results"></div>
    <div id="deleted"></div>

    <style>
        /* Когда ширина окна до 1366 пикселей, ширина content будет 100% */
        @media screen and (max-width: 1366px){
            .content {
                width: 100%;
            }
        }
    </style>
    <div class="container content">
        <br>
        <!--ВЕРХНЕЕ МЕНЮ!-->
    @include('layouts.top.menu')
    <!--ВСЁ, ЧТО ПОСЛЕ ВЕРХНЕГО МЕНЮ!-->
        <div class="row">
            <!--Содержимое страницы!-->
            <div class="col-12">
                <p>
                <h1>Личный кабинет</h1>
                </p>
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link" onclick="showTabContent(0)" name="tab" href="#users" id="users" >Пользователи</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" onclick="showTabContent(1)" name="tab" href="#map" id="map" >Карта сайта</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" onclick="showTabContent(2)" name="tab" href="#test_results" id="test_results">Результаты тестов</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" onclick="showTabContent(3)" name="tab" href="#user_results" id="user_results">Результаты учеников</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" onclick="showTabContent(4)" name="tab" href="#deleted" id="deleted" >Удаленные уроки/тесты</a>
                    </li>
                </ul>

                <div class="tab-content" style="min-height: 2048px">
                    <!--ВКЛАДКА ПОЛЬЗОВАТЕЛИ!-->
                    @include('layouts.cabinet.users')
                    <!--ВКЛАДКА КАРТА САЙТА!-->
                    @include('layouts.cabinet.map')
                    <!--ВКЛАДКА РЕЗУЛЬТАТЫ ТЕСТОВ (ТЕСТЫ)!-->
                    @include('layouts.cabinet.test_results')
                    <!--ВКЛАДКА РЕЗУЛЬТАТЫ ТЕСТОВ (УЧЕНИКИ)!-->
                    @include('layouts.cabinet.user_results')
                    <!--ВКЛАДКА УДАЛЕННЫЕ!-->
                    @include('layouts.cabinet.deleted')
                </div>
                <script>
                    showTabContent(0); // вкладка по умолчанию
                    // если якорь присутсвует в url - покажем нужную вкладку
                    if (window.location.hash === '#users')          showTabContent(0);
                    if (window.location.hash === '#map')            showTabContent(1);
                    if (window.location.hash === '#test_results')   showTabContent(2);
                    if (window.location.hash === '#user_results')   showTabContent(3);
                    if (window.location.hash === '#deleted')        showTabContent(4);
                    // отображает вкладку с определенным номером
                    function showTabContent(i) {
                        // // если переключились на другую вкладку - пусть сообщение layouts.messages.message исчезнет
                        // let messageDiv = document.getElementById('layouts.messages.message');
                        // console.log(messageDiv);
                        // messageDiv.style.display='none';
                        let arrTabsContent = document.getElementsByName('tab_content');
                        let arrTabs = document.getElementsByName('tab');
                        // делаем все контенты табов невидимыми
                        for (let j = 0; j < arrTabsContent.length; j++) {
                            arrTabsContent[j].style.display = 'none';
                            arrTabs[j].classList.remove('active');
                        }
                        arrTabsContent[i].style.display = 'inline';
                        arrTabs[i].classList.add('active');
                    }
                </script>
                <script>
                    // по нажатию на кнопку скрыват/показывает контент для вкладки с группировкой по тестам
                    function showHideResultDetailsByTestTab(id) {
                        let resultDetails = document.getElementById('details_test_'+id);
                        if (resultDetails.style.display === 'none') {
                            resultDetails.style.display = 'inline';
                            console.log('покажем', resultDetails);
                        }
                        else {
                            resultDetails.style.display = 'none';
                            console.log('скроем', resultDetails);
                        }
                    }
                    // по нажатию на кнопку скрыват/показывает контент для вкладки с группировкой по Ученикам
                    function showHideResultDetailsByUserTab(id) {
                        let resultDetails = document.getElementById('details_user_'+id);
                        if (resultDetails.style.display === 'none') {
                            resultDetails.style.display = 'inline';
                            console.log('покажем', resultDetails);
                        }
                        else {
                            resultDetails.style.display = 'none';
                            console.log('скроем', resultDetails);
                        }
                    }
                </script>
            </div>
        </div>

        <!-- КОНЕЦ Содержимого страницы!-->
        <footer class="footer container">КФУ им. В.И. Вернадского, Симферополь, 2020</footer>
    </div>

</div>

</div>
</body>
</html>
