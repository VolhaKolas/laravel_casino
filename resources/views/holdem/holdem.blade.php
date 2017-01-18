
@extends('layouts.main')

@section('content')



    <div class="background">

            @for($i = 0; $i < $numberOfPlayers * 2; $i++ )
                @if($i == $key * 2)
                    <div class="a{{$i}}" style="background-position: {{($cards[0] % 100 - 2) * 100/12}}% {{25 * (round($cards[0]/100))}}%">
                    </div>

                @elseif($i == $key * 2 + 1)
                <div class="a{{$i}}" style="background-position: {{($cards[1] % 100 - 2) * 100/12}}% {{25 * (round($cards[1]/100))}}%">
                </div>

                @else
                    <div class="a{{$i}}">
                    </div>
                @endif

                <!--
                <div class="a$key" style="background-position: ($n % 100 - 2) * 100 /12}}% 25 * (round($n/100))% ">
                </div>
                -->
            @endfor

    </div>


<button onclick="send()">Send</button>

    <script>
        var conn = new WebSocket("ws://localhost:8080");
        conn.onopen = function (e) {
        };

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

