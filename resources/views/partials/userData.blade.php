<div id="b{{$user - 1}}">
    <div class="user">
    {{ \App\User::where('id', $users)->value('name') }} <!--  correct this because you will have trouble when more than one tables be in play-->
        {{ \App\User::where('id', $users)->value('surname') }}
    </div>

    <div class="money">
        {{\App\Table_user::where('user_id', $users)->value('money')}}$
    </div>
</div>