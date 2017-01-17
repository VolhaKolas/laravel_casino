
@extends('layouts.main')

@section('content')



    <div class="background">
        <div class="wrapper">
            @foreach($numbers as $key => $n)
                <div class="a{{$key}}" style="background-position: {{($n % 100 - 2) * 100 /12}}% {{25 * (round($n/100))}}% ">
                </div>
            @endforeach

        </div>
    </div>


{{var_dump($priority)}}

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

