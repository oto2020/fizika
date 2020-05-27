<p class="has-line-data" data-line-start="0" data-line-end="1">Установкой и настройкой LAMP/WAMP занимаемся сами. В качестве WAMP советую использовать Laragon.</p>
<p class="has-line-data" data-line-start="2" data-line-end="4">1] Клонировать проект:<br>
$ git clone <a href="https://github.com/oto2020/fizika.git">https://github.com/oto2020/fizika.git</a></p>
<p class="has-line-data" data-line-start="5" data-line-end="6">2] Перейти в папку проекта и настроить подключение к БД в конфигурационном файле .env :</p>
<pre><code>$ cd папка_проекта 
$ cp .env.example .env 
$ nano .env &lt;br&gt;
[---------Редактируем содержимое----------]
DB_DATABASE=fizika
DB_USERNAME=root
DB_PASSWORD=<пароль от вашей БД></пароль> // Как настроить пользователя mysql гуглим.
</code></pre>
<p class="has-line-data" data-line-start="15" data-line-end="16">3] Генерируем ключ и создаем символьную ссылку на хранилище в папку /public :</p>
<pre><code>$ php artisan key:generate
$ php artisan storage:link
</code></pre>
<p class="has-line-data" data-line-start="20" data-line-end="21">4] Сайт готов к работе, необходимо указать серверу в качестве источника папку:</p>
<pre><code>корень_проекта/public
</code></pre>
<p class="has-line-data" data-line-start="24" data-line-end="25">5] Создаём пустую БД mysql с именем “fizika”, производим импорт БД через adminer из файла fizika.sql (файл предоставляется отдельно.)</p>
<pre><code>www localhost/adminer-mysql-la-la-la.php
</code></pre>
<p class="has-line-data" data-line-start="28" data-line-end="29">6] Распаковываем архив ckeditor.7z, чтобы получилась папка ckeditor.</p>
<pre><code>$ cd корень_проекта/storage/app/public
превращаем [архив] ckeditor.7z &gt;&gt;&gt;&gt; [папка] ckeditor
</code></pre>
<p class="has-line-data" data-line-start="35" data-line-end="36">// TODO: добавить сюда fizika.sql, в которую будет вшита пробная учетная запись администратора для демонстрации всех возможностей сайта</p>
<p class="has-line-data" data-line-start="37" data-line-end="38">Сайт для создания README.MD : <a href="https://dillinger.io/">https://dillinger.io/</a></p>
