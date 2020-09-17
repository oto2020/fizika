<div name="tab_content" style="display:none">
    @include('layouts.messages.message')
    <form name="test" method="post" action="/edit_users.php">
        @csrf
        @foreach ($users as $class_name => $class_users)
            <h3>
                {{$class_name}}:
            </h3>
            <table class="table table-striped table-inverse">
                <!--Заголовок таблицы с пользователями !-->
                <thead class="">
                <tr>
                    <th>#</th>
                    <th>AVA</th>
                    <th>ФИО</th>
                    <th>E-mail</th>
                    <th>Класс</th>
                    <th>Задать пароль</th>
                    <th>Изменить роль</th>
                    <th>Удалить</th>
                </tr>
                </thead>
                <!--Тело таблицы с пользователями !-->
                <tbody>
                @foreach ($class_users as $class_user)
                    <tr>
                        <div class="form-group">
                            <td>{{$class_user->id}} </td>
                        </div>
                        <div class="form-group">
                            <td>
                                <img id="ava_reload_{{$class_user->id}}" class="img-user-avatar-on-cabinet" src="{{$class_user->avatar_src}}">
                                <input id="checkbox_reload_{{$class_user->id}}" name="reload_avatar|{{$class_user->id}}" id="reload_avatar|{{$class_user->id}}" type="checkbox" style="display:none; width:30px; height:30px" class="form-control">
                            </td>
                            <script>
                                //  текущий объект img изображение авы
                                let ava_{{$class_user->id}} = document.getElementById("ava_reload_{{$class_user->id}}");
                                // скрытый чекбокс по сбросу аватарки
                                let checkbox_{{$class_user->id}} = document.getElementById("checkbox_reload_{{$class_user->id}}");
                                // при наведении мыши на аву -- будет отображаться светлая гифка перезагрузки
                                ava_{{$class_user->id}}.addEventListener("mouseenter", function(){ava_{{$class_user->id}}.src = "/storage/img/final_light_1.gif"});
                                // когда мышь уходит -- отображается снова ава
                                ava_{{$class_user->id}}.addEventListener("mouseleave", function(){ava_{{$class_user->id}}.src="{{$class_user->avatar_src}}"});
                                // если кликнуть по аве -- на её месте отобразится чекбокс с проставленной галочкой
                                ava_{{$class_user->id}}.addEventListener("mouseup", function(){ava_{{$class_user->id}}.style.display="none"; checkbox_{{$class_user->id}}.style.display="inline"; checkbox_{{$class_user->id}}.checked = true;});
                                // если снять галочку с чекбокса -- вернется ава
                                checkbox_{{$class_user->id}}.addEventListener("mouseup", function(){ava_{{$class_user->id}}.style.display="inline"; checkbox_{{$class_user->id}}.style.display="none";});
                            </script>
                        </div>

                        <div class="form-group">
                            <td><input name="name|{{$class_user->id}}" value="{{$class_user->name}}" type="text" class="form-control"></td>
                        </div>
                        <div class="form-group">
                            <td><input name="email|{{$class_user->id}}" value="{{$class_user->email}}" type="email" class="form-control"></td>
                        </div>
                        <div class="form-group">
                            <td>
                                <!--Выбор КЛАССА !-->
                                <select name="class_name|{{$class_user->id}}" class="browser-default custom-select" class="form-control" style="width:110px">
                                    <option {{($class_name == 'Учителя') ? 'selected' : ''}} >
                                        Учителя
                                    </option>
                                    @foreach ($sections as $section)
                                        {{--                                                @if ($section->name!='Главная')--}}
                                        @if (preg_match('/^([0-9]){1,2}(.){0,}/', $section->name))
                                            <option {{($class_name == $section->name) ? 'selected' : ''}}>
                                                {{$section->name}}
                                            </option>
                                        @endif
                                    @endforeach

                                </select>
                            </td>
                        </div>
                        <div class="form-group">
                            <td><input name="password|{{$class_user->id}}" type="password" style="width:110px" class="form-control"></td>
                        </div>
                        <div class="form-group">
                            <td>
                                <!--Выбор РОЛИ !-->
                                <select name="user_role_id|{{$class_user->id}}" class="form-control">
                                    @foreach ($roles as $role)
                                        <option {{($class_user->user_role_id == $role->id) ? 'selected' : ''}} value="{{$role->id}}">
                                            {{$role->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                        </div>
                        <div class="form-group">
                            <td>
                                <input name="delete_user|{{$class_user->id}}" id="delete_user|{{$class_user->id}}" type="checkbox" style="display:inline; width:30px; height:30px" class="form-control">
                                {{--                                        <button onclick="let chbx = document.getElementById('delete_user|{{$class_user->id}}');chbx.style.display='inline'; chbx.checked=true; this.style.display='none'" class="btn btn-outline-danger">Удалить</button>--}}
                            </td>
                        </div>
                    <tr>
                @endforeach
                </tbody>
            </table>
        @endforeach
        <input type="submit" class="btn btn-success btn-lg btn-block" value="Применить изменения">
    </form>
</div>
