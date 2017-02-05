<div id="bet">
    <form action="{{ route('choice')  }}" method="post" id="choice">
        {{ csrf_field() }}

        @if(\App\Table_user::where('table_id', auth()->user()->tableUsers->table_id)->max('bet') -
        \App\Table_user::where('user_id', auth()->id())->value('bet') == 0)
            <div>
                <input type="checkbox" id="call">
                <b>Продолжить</b>
                <b style="display: none">0</b>
            </div>
        @else
            <div>
                <input type="checkbox" id="call">
                <b>Принять ставку
                </b>

                <b>
                    {{\App\Table_user::where('table_id', auth()->user()->tableUsers->table_id)->max('bet') -
                    \App\Table_user::where('user_id', auth()->id())->value('bet')}}
                </b>$
            </div>

        @endif


        <div>
            <input type="checkbox" id="raise">
            <b>Увеличить ставку на
            </b>
            <b>
                {{\App\Table_user::where('table_id', auth()->user()->tableUsers->table_id)->max('bet')}}
            </b>$
        </div>
        @if(\App\Table_user::where('table_id', auth()->user()->tableUsers->table_id)->max('bet') -
                                \App\Table_user::where('user_id', auth()->id())->value('bet') != 0)
            <div>
                <input type="checkbox" id="fold">
                <b>Сбросить карты</b>
            </div>
        @endif
        <input type="hidden" name="answer" id="answer" value="">
        <button class="btn btn-success" onclick="send()">Выбрать</button>
    </form>
</div>