
@extends('layouts.main')

@section('content')



    <div class="background">

            @for($i = 0; $i < $numberOfPlayers * 2; $i++ )
                <div class="a{{$i}}">
                </div>
                <!--
                <div class="a$key" style="background-position: ($n % 100 - 2) * 100 /12}}% 25 * (round($n/100))% ">
                </div>
                -->
            @endfor

    </div>
{{$key}}

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

