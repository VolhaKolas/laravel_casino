
@extends('layouts.main')

@section('content')



    @if(isset(\App\Classes\Position\Blinds::blinds()[0]))
        @if(\App\User_card::where('user_id', auth()->id())->count() == 1)
            <div class="background">
                    <!-- Dealer chip-->
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
            </div>
            <script>
                @for($i = 1; $i <= \App\Table_user::where('table_id', auth()->user()->tableUsers->table_id)->count(); $i++)
                    setTimeout(function () {
                        $(".background .a{{($i - 1) * 2}}").css('display', 'block');
                    }, 500 + 400 * {{$i}});
                @endfor

                setTimeout(function () {
                    $("#dealer").css('display', 'block');
                }, 4500);

                setTimeout(function () {
                    $("#smallblind").css('display', 'block');
                    $("#bigblind").css('display', 'block');
                }, 5000);


                setTimeout(function () {
                    $.ajax({
                        type: "GET",
                        url: "/cards",
                        success: function (data) {
                            window.location.href = "/texas";
                        }
                    });
                    return false;
                }, 5000 + 100 * {{auth()->user()->userCards[0]->user_place}});

            </script>


        @elseif(\App\User_card::where('user_id', auth()->id())->count() == 2)
            <div class="background">
                    <!-- Dealer chip-->
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

                                    <!-- First player's card -->
                                    <div class="a{{($i - 1) * 2}}" style=" background-position: {{(\App\User_card::where('user_place', $i)->pluck('card')[0] % 100 - 2) * 100/12}}% {{25 * (round(\App\User_card::where('user_place', $i)->pluck('card')[0]/100))}}%">
                                    </div>

                                    <!-- Second player's card -->
                                    <div class="a{{$i + ($i - 1)}}" style=" background-position: {{(\App\User_card::where('user_place', $i)->pluck('card')[1] % 100 - 2) * 100/12}}% {{25 * (round(\App\User_card::where('user_place', $i)->pluck('card')[1]/100))}}%">
                                    </div>
                            @else
                                <!-- Cards of other players -->
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

                <div class="tableChip">
                </div>
                <div class="tableMoney">
                    {{auth()->user()->tableUsers->tableCards->table_money}}$
                </div>

            </div>


            @if(auth()->user()->userCards[0]->user_place == \App\Classes\Position\Blinds::blinds()[3])
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
                    Ожидание игрока
                    {{\App\User::where('id', \App\User_card::where('user_place', \App\Classes\Position\Blinds::blinds()[3])->value('user_id'))->value('name')}}
                    {{\App\User::where('id', \App\User_card::where('user_place', \App\Classes\Position\Blinds::blinds()[3])->value('user_id'))->value('surname')}}
                </div>
            @endif



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






            @if(isset($deal))
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

                    <!-- Cards of other players -->
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

