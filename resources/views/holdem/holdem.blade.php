
@extends('layouts.main')

@section('content')



    <div class="background">

        <!-- Dealer chip-->
        @if(isset($leftD))
        <div id="dealer" style="left: {{$leftD}}%; top: {{$topD}}%">
        </div>
        @endif

    <!-- Small blind chip-->
        <div id="smallblind" style="left: {{$leftSB}}%; top: {{$topSB}}%">
        </div>

        <!-- Big blind chip-->
        <div id="bigblind" style="left: {{$leftBB}}%; top: {{$topBB}}%">
        </div>

            @for($i = 0; $i < $numberOfPlayers * 2; $i++ )
            <!-- First player's card -->
                @if($i == $key * 2)
                    <div class="a{{$i}}" style="background-position: {{($cards[0] % 100 - 2) * 100/12}}% {{25 * (round($cards[0]/100))}}%">
                    </div>
                    <!-- Second player's card -->
                @elseif($i == $key * 2 + 1)
                <div class="a{{$i}}" style="background-position: {{($cards[1] % 100 - 2) * 100/12}}% {{25 * (round($cards[1]/100))}}%">
                </div>

                    <!-- Cards of another players -->
                @else
                    <div class="a{{$i}}">
                    </div>
                @endif

            <!-- User's money . One user - two cards that is why $i/2-->
                @if($i % 2 == 0)
                        <div class="b{{$i/2}}" id="money">
                            <?php
                                $currentUser = \App\User_card::where("user_place", $i/2 + 1)->value('user_id');
                                $money = \App\Table_user::where('user_id', $currentUser)->value('money');
                            ?>
                            {{$money}}$
                        </div>
                @endif

            @endfor

    </div>


<button onclick="send()">Send</button>

    <script>
        var conn = new WebSocket("ws://localhost:8080");

        conn.onmessage = function (e) {
            console.log("Полученные данные: " + e.data);
        }

        function send() {
            var data = "Данные для отправки: " + "Hello";
            conn.send(data);
            console.log("Отправлено: " + data);
        }

    </script>

@endsection

