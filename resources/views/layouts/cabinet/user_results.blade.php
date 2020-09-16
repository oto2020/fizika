<div name="tab_content" style="display:none">
    @include('layouts.messages.message')
    <?php// dump($testResultsByTests);?>
    <br>
    @foreach($testResultsByUsers as $userName => $results)
        <h3>
            {{$userName}}
        </h3>
        @if (count($results) > 0)
            <ul>
                <table class="table table-striped table-inverse">
                    <thead>
                    {{--                                        <th>#</th>--}}
                    <th style="width:300px">Название теста</th>
                    <th>Результат</th>
                    <th>Дата прохождения</th>
                    <th>Удалить результат</th>
                    <th style="width:500px">Подробнее</th>
                    </thead>
                    <tbody>
                    @foreach ($results as $result)
                        <tr style="background-color: rgba(0, 0, 0, 0.05);">
                            {{--                                                <td>--}}
                            {{--                                                    {{$result->user_id}}--}}
                            {{--                                                </td>--}}
                            <td>
                                <a href="{{$result->test_full_url}}"
                                   target="_blank"
                                   style="color:#000000"
                                >
                                    {{$result->test_name}}
                                </a>
                            </td>
                            <td>
                                {{$result->point}} %
                            </td>
                            <td>
                                {{$result->datetime}}
                            </td>
                            <td>
                                <form action="/delete_test_result.php" method="post">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="id" value="{{$result->result_id}}">
                                    <input type="hidden" name="redirect_to" value="user_results">
                                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm ('Точно очистить?')">Очистить</button>
                                </form>
                            </td>
                            <td colspan="4">
                                <button onclick="showHideResultDetailsByUserTab({{$result->result_id}})" class="btn btn-secondary btn-sm">Скрыть/показать результаты</button>
                                <div id="details_user_{{$result->result_id}}" style="display:none;">
                                    <br>
                                    <?php $details = json_decode($result->details); ?>
                                    <ul>
                                        @foreach ($details as $questionName => $fields)
                                            <li>
                                                <?php $counter = 0; ?>
                                                <b>{{$questionName}}</b><br>
                                                @foreach ($fields as $field)
                                                    @if ($counter === 0) - Результат:           <b>{{$field}}</b> <br>
                                                    @elseif ($counter === 1) - ответ участника: <i>{{$field}}</i> <br>
                                                    @elseif ($counter === 2) - верный  ответ:   <i>{{$field}}</i> <br>
                                                    @endif
                                                    <?php $counter++; ?>
                                                @endforeach
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </td>
                        </tr>

                    @endforeach
                    </tbody>
                </table>
            </ul>
        @else
            Ни одного теста не пройдено.
        @endif
        <br>
    @endforeach
</div>
