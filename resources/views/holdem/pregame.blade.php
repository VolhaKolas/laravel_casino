
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
                        //data[0] - time before game, data[1] - users count
                        $('.timer var').html(data[0]);
                        if(data[0] <= 0 & data[1] >= 2 || data[1] == 8) {
                            window.location.href = "/texas";
                        }
                        else if(data[0] <= 0) {
                            window.location.href = "/userpage";
                        }
                    }
                });
                return false;
            }, 1000);

        });

    </script>


@endsection