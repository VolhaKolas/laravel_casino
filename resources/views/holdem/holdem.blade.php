
@extends('layouts.main')

@section('content')



    <div class="background">
        <!-- Dealer chip-->
        @if(isset(\App\Classes\Position\Blinds::blinds()[0]))
        @if(\App\User_card::where('user_id', auth()->id())->count() == 1)
            <div id="dealer" style="left: {{\App\Classes\Position\Position::left(\App\Classes\Position\Blinds::blinds()[0])}}%;
                top: {{\App\Classes\Position\Position::top(\App\Classes\Position\Blinds::blinds()[0])}}%">
            </div>


        <!-- Small blind chip-->
            <div id="smallblind" style="left: {{\App\Classes\Position\Position::left(\App\Classes\Position\Blinds::blinds()[1])}}%;
                top: {{\App\Classes\Position\Position::top(\App\Classes\Position\Blinds::blinds()[1])}}%">
            </div>

        <!-- Big blind chip-->
            <div id="bigblind" style="left: {{\App\Classes\Position\Position::left(\App\Classes\Position\Blinds::blinds()[2])}}%;
                top: {{\App\Classes\Position\Position::top(\App\Classes\Position\Blinds::blinds()[2])}}%">
            </div>


            @for($i = 1; $i <= \App\Table_user::where('table_id', auth()->user()->tableUsers->table_id)->count(); $i++)
                <div class="a{{($i - 1) * 2}}" style=" background-position: {{(\App\User_card::where('user_place', $i)->pluck('card')[0] % 100 - 2) * 100/12}}% {{25 * (round(\App\User_card::where('user_place', $i)->pluck('card')[0]/100))}}%">
                </div>


                <div id="b{{$i - 1}}">
                    <div class="user">
                        {{ \App\User::where('id', \App\User_card::where('user_place', $i)->value('user_id'))->value('name') }}
                        {{ \App\User::where('id', \App\User_card::where('user_place', $i)->value('user_id'))->value('surname') }}
                    </div>

                    <div class="money">
                        {{\App\Table_user::where('id', \App\User_card::where('user_place', $i)->value('user_id'))->value('money')}}$
                    </div>
                </div>
            @endfor

            <script>
                for(var i = 0; i < {{\App\Table_user::where('table_id', auth()->user()->tableUsers->table_id)->count()}}; i++) {
                    setTimeout(function () {
                        $(".background .a{{($i - 1) * 2}}").css('display', 'block');
                    }, 500 + 400 * i);
                }

                setTimeout(function () {
                    $("#dealer").css('display', 'block');
                }, 4500);

                setTimeout(function () {
                    $("#smallblind").css('display', 'block');
                    $("#bigblind").css('display', 'block');
                }, 5000);

            </script>


        @elseif(\App\User_card::where('user_id', auth()->id())->count() == 2)
            <div id="dealer" style="left: {{\App\Classes\Position\Position::left(\App\Classes\Position\Blinds::blinds()[0])}}%;
                    top: {{\App\Classes\Position\Position::top(\App\Classes\Position\Blinds::blinds()[0])}}%">
            </div>


            <!-- Small blind chip-->
            <div id="smallblind" style="left: {{\App\Classes\Position\Position::left(\App\Classes\Position\Blinds::blinds()[1])}}%;
                    top: {{\App\Classes\Position\Position::top(\App\Classes\Position\Blinds::blinds()[1])}}%">
            </div>

            <!-- Big blind chip-->
            <div id="bigblind" style="left: {{\App\Classes\Position\Position::left(\App\Classes\Position\Blinds::blinds()[2])}}%;
                    top: {{\App\Classes\Position\Position::top(\App\Classes\Position\Blinds::blinds()[2])}}%">
            </div>


            @for($i = 1; $i <= \App\Table_user::where('table_id', auth()->user()->tableUsers->table_id)->count(); $i++)

                @if(\App\User_card::where('user_place', $i)->value('user_id') == auth()->id())
                <div class="a{{($i - 1) * 2}}" style=" background-position: {{(\App\User_card::where('user_place', $i)->pluck('card')[0] % 100 - 2) * 100/12}}% {{25 * (round(\App\User_card::where('user_place', $i)->pluck('card')[0]/100))}}%">
                </div>

                <div class="a{{$i + ($i - 1)}}" style=" background-position: {{(\App\User_card::where('user_place', $i)->pluck('card')[1] % 100 - 2) * 100/12}}% {{25 * (round(\App\User_card::where('user_place', $i)->pluck('card')[1]/100))}}%">
                </div>
                @else
                    <div class="a{{($i - 1) * 2}}">
                    </div>

                    <div class="a{{$i + ($i - 1)}}">
                    </div>
                @endif



                <div id="b{{$i - 1}}">
                    <div class="user">
                        {{ \App\User::where('id', \App\User_card::where('user_place', $i)->value('user_id'))->value('name') }}
                        {{ \App\User::where('id', \App\User_card::where('user_place', $i)->value('user_id'))->value('surname') }}
                    </div>

                    <div class="money">
                        {{\App\Table_user::where('id', \App\User_card::where('user_place', $i)->value('user_id'))->value('money')}}$
                    </div>
                </div>
            @endfor


                <script>
                    @for($i = 1; $i <= \App\Table_user::where('table_id', auth()->user()->tableUsers->table_id)->count(); $i++)

                         setTimeout(function () {
                            $(".background .a{{($i - 1) * 2}}").css('display', 'block');
                            $(".background .a{{$i + ($i - 1)}}").css('display', 'block');
                        }, 500 + 300 * {{$i}});
                    @endfor


                    $("#dealer").css('display', 'block');
                    $("#smallblind").css('display', 'block');
                    $("#bigblind").css('display', 'block');


                </script>

            @endif
        @else
            <script>
                window.location.href = "/texas";
            </script>
        @endif









    <script>

            setTimeout(function () {
                $.ajax({
                    type: "GET",
                    url: "/cards",
                    success: function (data) {
                        console.log(data);
                    }
                });
                return false;
            }, 5000 + 100 * {{auth()->user()->userCards[0]->user_place}});





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


           /* var conn = new WebSocket("ws://localhost:8080");

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
            }*/



    </script>

@endsection

