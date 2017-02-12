<!-- First player's card -->
<div class='cards' id="a{{($user - 1) * 2}}" style=" background-position: {{(\App\User_card::where('user_id', $users)->pluck('card')[0] % 100 - 2) * 100/12}}% {{25 * (round(\App\User_card::where('user_id', $users)->pluck('card')[0]/100))}}%">
</div>

<!-- Second player's card -->
<div class="cards" id="a{{$user + ($user - 1)}}" style=" background-position: {{(\App\User_card::where('user_id', $users)->pluck('card')[1] % 100 - 2) * 100/12}}% {{25 * (round(\App\User_card::where('user_id', $users)->pluck('card')[1]/100))}}%">
</div>