
@foreach(\App\Table_user::where('table_id', auth()->user()->tableUsers->table_id)->pluck('user_id') as $users)
    <?php $user = \App\User_card::where('user_id', $users)->value('user_place') ?>
    <div id="a{{($user - 1) * 2}}" class="cards" style=" background-position: {{(\App\User_card::where('user_id', $users)->pluck('card')[0] % 100 - 2) * 100/12}}% {{25 * (round(\App\User_card::where('user_id', $users)->pluck('card')[0]/100))}}%">
    </div>

    <div id="b{{$user - 1}}">
        <div class="user">
            {{ \App\User::where('id', $users)->value('name') }}
            {{ \App\User::where('id', $users)->value('surname') }}
        </div>

        <div class="money">
            {{\App\Table_user::where('id', $users)->value('money')}}$
        </div>
    </div>
@endforeach