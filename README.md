
<p data-line-end="1" data-line-start="0">Локальный сервер выбираем сами, настройкой mysql занимаемся сами.</p>

<ol>
	<li data-line-end="6" data-line-start="2">
	<p data-line-end="5" data-line-start="2">Клонировать себе содержимое:<br />
	$ git clone&nbsp;<a href="https://github.com/oto2020/fizika.git">https://github.com/oto2020/fizika.git</a><br />
	</li>
	<li data-line-end="8" data-line-start="6">
	<p data-line-end="8" data-line-start="6">Зайти в папку с проектом, настроить конфигурауионный файл laravel .env:<br />
	<pre>
        <code>
            $ cd laravel 
            $ cp .env.example .env 
            $ nano .env <br>
            -------------------
            DB_DATABASE=fizika
            DB_USERNAME=root
            DB_PASSWORD=&lt;пароль от вашей БД&gt; // Как настроить пользователя mysql гуглим.
        </code>
	</pre>
	</li>
</ol>

<hr />


<ol start="2">
	<li data-line-end="16" data-line-start="13">
	<p data-line-end="15" data-line-start="13">Генерить ключ и создать ссылку на хранилище:<br />
	$ php artisan key:generate; php artisan storage:link</p>
	</li>
	<li data-line-end="19" data-line-start="16">
	<p data-line-end="18" data-line-start="16">Указать серверу папку laravel/public.<br />
	[тут уж в зависимости от самого сервера]</p>
	</li>
	<li data-line-end="22" data-line-start="19">
	<p data-line-end="21" data-line-start="19">Импорт БД fizika.sql через adminer или php-my-admin.<br />
	[или создайте свою таблицу &lsquo;fizika&rsquo;]</p>
	</li>
	<li data-line-end="25" data-line-start="22">
	<p data-line-end="24" data-line-start="22">Если ОС==Windows: в настройках IDE указываем путь к git.exe:<br />
	[например: C:\Program Files\Git\cmd\git.exe]</p>
	</li>
	<li data-line-end="29" data-line-start="25">
	<p data-line-end="28" data-line-start="25">VCS-&gt;Git-&gt;Pull. Тут предложат добавить remote репозиторий, применяем этот:<br />
	<a href="https://%3Ctoken%3E@github.com/igo4ek/laravel.git">https://&lt;token&gt;@github.com/igo4ek/laravel.git</a><br />
	[токен запрашивается у владельца репозитория. владелец генерирует токен тут:&nbsp;<a href="https://github.com/settings/tokens/">https://github.com/settings/tokens/</a>]</p>
	</li>
	<li data-line-end="31" data-line-start="29">
	<p data-line-end="30" data-line-start="29">Заходим в localhost &ndash; сайт должен работать на локальном сервере. Логинимся, проверяем.</p>
	</li>
</ol>

<p data-line-end="32" data-line-start="31">// TODO: добавить сюда fizika.sql, в которую будет вшита пробная учетная запись администратора для демонстрации всех возможностей сайта.</p>
