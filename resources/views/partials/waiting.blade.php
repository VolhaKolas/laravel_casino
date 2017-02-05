<div id="waiting">
    Ожидание игрока
    @foreach(\App\Table_user::where('table_id', auth()->user()->tableUsers->table_id)->pluck('user_id') as $users)
    @if(\App\User_card::where('user_id', $users)->value('current_bet') == 1)
    {{\App\User::where('id', $users)->value('name')}}
    {{\App\User::where('id', $users)->value('surname')}}
    @endif
    @endforeach
</div>