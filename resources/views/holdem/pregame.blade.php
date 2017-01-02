
@extends('layouts.main')

@section('content')
    <div class="timer">Игра начнется через <var></var></div>



    <script>

        $(document).ready(function() {
            var data = "<?= $table_id ?>";

            setInterval(function () {
                $.ajax({
                    type: "GET",
                    url: "/before",
                    data: {table: data},
                    success: function (data) {
                        //data[0] - time before game, data[1] - count of users
                        var timeBefore = data[0];
                        var users = data[1];
                        if (timeBefore <= 0 & users >= 2 || users == 8) {
                            window.location.href = "/texas";
                        }
                        else if (timeBefore <= 0 & users == 1) {
                            window.location.href = "/userpage";
                        }
                        $('.timer var').html(timeBefore);
                    }
                });
                return false;
            }, 1000);

        });
    </script>


@endsection