
@extends('layouts.main')

@section('content')



    <div class="background">
        <!-- Dealer chip-->
        @if(isset($dealer))
        <div id="dealer" style="left: {{\App\Classes\Position\Position::left($dealer)}}%; top: {{\App\Classes\Position\Position::top($dealer)}}%">
        </div>
        @endif

    <!-- Small blind chip-->
        <div id="smallblind" style="left: {{\App\Classes\Position\Position::left($smallBlind)}}%; top: {{\App\Classes\Position\Position::top($smallBlind)}}%">
        </div>

        <!-- Big blind chip-->
        <div id="bigblind" style="left: {{\App\Classes\Position\Position::left($bigBlind)}}%; top: {{\App\Classes\Position\Position::top($bigBlind)}}%">
        </div>

            @for($i = 0; $i < $numberOfPlayers * 2; $i++ )
            <?php
            $currentUser = \App\User_card::where("user_place", $i/2 + 1)->value('user_id');
            $money = \App\Table_user::where('user_id', $currentUser)->value('money');
            $userName = \App\User::where('id', $currentUser)->value('name');
            $userSurname = \App\User::where('id', $currentUser)->value('surname');
            ?>

            <!-- First player's card -->
                @if($i == auth()->user()->userCards[0]->user_place * 2 - 2)
                    <div class="a{{$i}}" style="background-position: {{(auth()->user()->userCards[0]->card % 100 - 2) * 100/12}}% {{25 * (round(auth()->user()->userCards[0]->card/100))}}%">
                    </div>

                    <!-- Second player's card -->
                @elseif($i == auth()->user()->userCards[0]->user_place * 2 - 1)
                <div class="a{{$i}}" style="background-position: {{(auth()->user()->userCards[1]->card % 100 - 2) * 100/12}}% {{25 * (round(auth()->user()->userCards[1]->card/100))}}%">
                </div>

                    <!-- Cards of another players -->
                @else
                    <div class="a{{$i}}">
                    </div>
                @endif

            <!-- User's money . One user - two cards that is why $i/2-->
                @if($i % 2 == 0)
                    <div id="b{{$i/2}}">
                            <div class="money">
                            {{$money}}$
                            </div>
                            <div class="user">
                                {{$userName}} {{$userSurname}}
                            </div>
                    </div>
                @endif

            @endfor

        <div class="tableChip">
        </div>
        <div class="tableMoney">
            {{auth()->user()->tableUsers->tableCards->table_money}}$
        </div>
     </div>

        @if(auth()->user()->userCards[0]->user_place == $firstBeter)
            <div id="bet">
                <form action="{{ route('choice')  }}" method="post">
                    {{ csrf_field() }}
                    <div>
                        <input type="checkbox" id="call">
                        <b>Принять ставку 100$</b>
                    </div>
                    <div>
                        <input type="checkbox" id="raise">
                        <b>Поднять ставку на 100$</b>
                    </div>
                    <div>
                        <input type="checkbox" id="fold">
                        <b>Сбросить карты</b>
                    </div>
                    <button class="btn btn-primary" onclick="send()">Выбрать</button>
                </form>
            </div>
        @else
            <div id="waiting">
                Ожидание игрока {{\App\User::where('id', $firstBeterId)->value('name')}} {{\App\User::where('id', $firstBeterId)->value('surname')}}
            </div>
        @endif



    <script>

            var check = document.querySelectorAll("[type='checkbox']");
            for(var j = 0 ; j < check.length; j++) {
                check[j].onclick = function () {
                    for(var i = 0; i < check.length; i++) {
                        if(check[i] == this) {
                            check[i].checked = 'checked';
                            check[i].setAttribute('checked', 'checked');
                        }
                        else {
                            check[i].checked = '';
                            check[i].setAttribute('checked', 'false');
                        }
                    }
                }

            }


            var conn = new WebSocket("ws://localhost:8080");

            conn.onmessage = function (e) {
                console.log("Полученные данные: " + e.data);
            }

            function send() {



                for(var a = 0; a < check.length; a++) {
                    if(check[a].getAttribute('checked') != 'false') {
                        var choice = check[a].id;
                    }
                }
                conn.send(choice);
            }



    </script>

@endsection

