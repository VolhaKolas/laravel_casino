
@extends('layouts.main')

@section('content')


    @if(isset(\App\Classes\Position\Blinds::blinds()[0]))
        @if(\App\User_card::where('user_id', auth()->id())->count() == 1)
            <div class="background">
                    <!-- Dealer chip-->
                @if(\App\Classes\Position\Blinds::blinds()[0] != 0)
                <div id="dealer" style="left: {{\App\Classes\Position\Position::left(\App\Classes\Position\Blinds::blinds()[0])}}%;
                        top: {{\App\Classes\Position\Position::top(\App\Classes\Position\Blinds::blinds()[0])}}%">
                </div>
                @endif


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
                @if(\App\Classes\Position\Blinds::blinds()[0] != 0)
                    <div id="dealer" style="left: {{\App\Classes\Position\Position::left(\App\Classes\Position\Blinds::blinds()[0])}}%;
                            top: {{\App\Classes\Position\Position::top(\App\Classes\Position\Blinds::blinds()[0])}}%">
                    </div>
                @endif

                    <!-- Small blind chip-->
                    <div id="smallblind" style="left: {{\App\Classes\Position\Position::left(\App\Classes\Position\Blinds::blinds()[1])}}%;
                            top: {{\App\Classes\Position\Position::top(\App\Classes\Position\Blinds::blinds()[1])}}%">

                    </div>

                        <!-- Big blind chip-->
                        <div id="bigblind" style="left: {{\App\Classes\Position\Position::left(\App\Classes\Position\Blinds::blinds()[2])}}%;
                                top: {{\App\Classes\Position\Position::top(\App\Classes\Position\Blinds::blinds()[2])}}%">
                        </div>


                        @for($i = 1; $i <= \App\Table_user::where('table_id', auth()->user()->tableUsers->table_id)->count(); $i++)

                            @if(\App\User_card::where('user_place', $i)->value('card') != null)
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
                            @endif


                            <div id="b{{$i - 1}}">
                                <div class="user">
                                    {{ \App\User::where('id', \App\User_card::where('user_place', $i)->value('user_id'))->value('name') }} <!--  correct this because you will have trouble when more than one tables be in play-->
                                    {{ \App\User::where('id', \App\User_card::where('user_place', $i)->value('user_id'))->value('surname') }}
                                </div>

                                <div class="money">
                                    {{\App\Table_user::where('user_id', \App\User_card::where('user_place', $i)->value('user_id'))->value('money')}}$
                                </div>
                            </div>
                        @endfor


                    @if(\App\Table_card::where('table_id', auth()->user()->tableUsers->table_id)->value('flop_open') == 1)
                        <div class="t0" style="display: block; background-position: {{(\App\Table_card::where('table_id', auth()->user()->tableUsers->table_id)->value('flop1') % 100 - 2) * 100/12}}% {{25 * (round(\App\Table_card::where('table_id', auth()->user()->tableUsers->table_id)->value('flop1')/100))}}%">
                        </div>

                        <div class="t1" style="display: block; background-position: {{(\App\Table_card::where('table_id', auth()->user()->tableUsers->table_id)->value('flop2') % 100 - 2) * 100/12}}% {{25 * (round(\App\Table_card::where('table_id', auth()->user()->tableUsers->table_id)->value('flop2')/100))}}%">
                        </div>

                        <div class="t2" style="display: block; background-position: {{(\App\Table_card::where('table_id', auth()->user()->tableUsers->table_id)->value('flop3') % 100 - 2) * 100/12}}% {{25 * (round(\App\Table_card::where('table_id', auth()->user()->tableUsers->table_id)->value('flop3')/100))}}%">
                        </div>
                    @endif
                    @if(\App\Table_card::where('table_id', auth()->user()->tableUsers->table_id)->value('turn_open') == 1)
                        <div class="t3" style="display: block; background-position: {{(\App\Table_card::where('table_id', auth()->user()->tableUsers->table_id)->value('turn') % 100 - 2) * 100/12}}% {{25 * (round(\App\Table_card::where('table_id', auth()->user()->tableUsers->table_id)->value('turn')/100))}}%">
                        </div>
                    @endif
                    @if(\App\Table_card::where('table_id', auth()->user()->tableUsers->table_id)->value('river_open') == 1)
                        <div class="t4" style="display: block; background-position: {{(\App\Table_card::where('table_id', auth()->user()->tableUsers->table_id)->value('river') % 100 - 2) * 100/12}}% {{25 * (round(\App\Table_card::where('table_id', auth()->user()->tableUsers->table_id)->value('river')/100))}}%">
                        </div>
                    @endif


                <div class="tableChip">
                </div>
                <div class="tableMoney">
                    {{auth()->user()->tableUsers->tableCards->table_money}}$
                </div>

            </div>


            @if(auth()->user()->userCards[0]->current_bet == 1)
                <div id="bet">
                    <form action="{{ route('choice')  }}" method="post" id="choice">
                        {{ csrf_field() }}

                        @if(\App\Table_user::where('table_id', auth()->user()->tableUsers->table_id)->max('bet') -
                        \App\Table_user::where('user_id', auth()->id())->value('bet') == 0)
                            <div>
                                <input type="checkbox" id="call">
                                <b>Продолжить</b>
                                <b style="display: none">0</b>
                            </div>
                        @else
                            <div>
                                <input type="checkbox" id="call">
                                <b>Принять ставку
                                </b>

                                <b>
                                    {{\App\Table_user::where('table_id', auth()->user()->tableUsers->table_id)->max('bet') -
                                    \App\Table_user::where('user_id', auth()->id())->value('bet')}}
                                </b>$
                            </div>

                        @endif


                            <div>
                                <input type="checkbox" id="raise">
                                <b>Увеличить ставку на
                                </b>
                                <b>
                                    {{\App\Table_user::where('table_id', auth()->user()->tableUsers->table_id)->max('bet')}}
                                </b>$
                            </div>
                        @if(\App\Table_user::where('table_id', auth()->user()->tableUsers->table_id)->max('bet') -
                                                \App\Table_user::where('user_id', auth()->id())->value('bet') != 0)
                            <div>
                                <input type="checkbox" id="fold">
                                <b>Сбросить карты</b>
                            </div>
                        @endif
                        <input type="hidden" name="answer" id="answer" value="">
                        <button class="btn btn-primary" onclick="send()">Выбрать</button>
                    </form>
                </div>

            @else


                <div id="waiting">
                    Ожидание игрока
                    {{\App\User::where('id', \App\User_card::where('current_bet', 1)->value('user_id'))->value('name')}}
                    {{\App\User::where('id', \App\User_card::where('current_bet', 1)->value('user_id'))->value('surname')}}
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






    <script>

            var check = document.querySelectorAll("[type='checkbox']");
            if(check[0] != null) {
                var sum1 = check[0].parentNode.childNodes[5].innerText;
                var sum2 = check[1].parentNode.childNodes[5].innerText;

                for (var j = 0; j < check.length; j++) {
                    check[j].onclick = function () {
                        var sum = 0;
                        if (this.parentNode.childNodes[5] == null) {
                            sum = 0;
                        }
                        else {
                            sum = Number(this.parentNode.childNodes[5].innerText);
                        }
                        if (this.id == 'raise') {
                            sum = Number(sum1) + Number(sum2);
                        }
                        var answer = document.querySelector('#answer');
                        answer.value = sum;
                        for (var i = 0; i < check.length; i++) {
                            if (check[i] == this) {
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
            }


           var conn = new WebSocket("ws://localhost:8080");

            conn.onmessage = function (e) {
                window.location.href = "/texas";
            }

            function send() {
                $.ajax({
                    type: "POST",
                    url: "/choice",
                    data: $("#choice").serialize(),
                    success: function (data) {
                        window.location.href = "/texas";
                    }
                });

                conn.send('hello');
            }

            $('form').on("submit", function (e) {
                e.preventDefault();
            });


    </script>

@endsection

