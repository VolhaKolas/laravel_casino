
@extends('layouts.main')

@section('content')




    @if(isset(\App\Classes\Position\Blinds::blinds()[0]) and \App\Classes\Position\Blinds::blinds()[1] != 0)
        @if(\App\User_card::where('user_id', auth()->id())->count() == 1)
            <div class="background">
                    <!-- Dealer chip-->
                @if(\App\Classes\Position\Blinds::blinds()[0] != 0)
                    @include('partials.blinds')
                @else
                    @include('partials.blinds2')
                @endif


                @include('partials.dealerCards')


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
                }, 5000 + 20 * {{auth()->user()->userCards[0]->user_place}});

            </script>


        @elseif(\App\User_card::where('user_id', auth()->id())->count() == 2)
            <div class="background">
                @if(\App\Classes\Position\Blinds::blinds()[0] != 0)
                    @include('partials.blinds')
                @else
                    @include('partials.blinds2')
                @endif


                @foreach(\App\Table_user::where('table_id', auth()->user()->tableUsers->table_id)->pluck('user_id') as $users)
                    <?php $user = \App\User_card::where('user_id', $users)->value('user_place') ?>

                    @if(\App\User_card::where('user_id', $users)->value('card') != null)
                            @if($users == auth()->id())
                                <!-- First and second player's cards -->
                                @include('partials.userCards')
                            @else
                                <!-- Cards of other players -->
                                @if(\App\Table_card::where('table_id', auth()->user()->tableUsers->table_id)->value('open') < 4)
                                    @include('partials.otherCards')
                                @else
                                    @include('partials.otherCardsOpen')
                                @endif
                            @endif
                    @endif
                           <!-- User name, surname and money -->
                        @include('partials.userData')
                @endforeach


                    @if(\App\Table_card::where('table_id', auth()->user()->tableUsers->table_id)->value('open') >= 1)
                        <!--  flop cards -->
                        @include('partials.flop')
                    @endif
                    @if(\App\Table_card::where('table_id', auth()->user()->tableUsers->table_id)->value('open') >= 2)
                        <!--  turn card -->
                            @include('partials.turn')
                    @endif
                    @if(\App\Table_card::where('table_id', auth()->user()->tableUsers->table_id)->value('open') >= 3)
                        <!--  turn card -->
                            @include('partials.river')
                    @endif


                <div class="tableChip">
                </div>
                <div class="tableMoney">
                    {{auth()->user()->tableUsers->tableCards->table_money}}$
                </div>
            </div>

        @if(\App\Table_card::where('table_id', auth()->user()->tableUsers->table_id)->value('open') < 4)
            @if(auth()->user()->userCards[0]->current_bet == 1)
               @include('partials.bet')
            @else
                @include('partials.waiting')
            @endif
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

        @if(\App\Table_card::where('table_id', auth()->user()->tableUsers->table_id)->value('open') >= 4)
            <script>

                var time;
                @if(\App\Table_card::where('table_id', auth()->user()->tableUsers->table_id)->value('open') < 5)
                        time = 10000;
                @else
                        time = 1000;
                @endif

                setTimeout(function () {
                    $.ajax({
                        type: "GET",
                        url: "/new-deal",
                        success: function (data) {
                            console.log(data);
                            window.location.href = "/texas";
                        }
                    });

                    setTimeout(function () {
                        conn.send('hello');
                    }, 100);
                }, time);

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

                @if(\App\Table_card::where('table_id', auth()->user()->tableUsers->table_id)->value('open') < 4)

                        $.ajax({
                        type: "POST",
                        url: "/choice",
                        data: $("#choice").serialize(),
                        success: function (data) {
                            window.location.href = "/texas";
                        }
                    });

                @endif

                setTimeout(function () {
                    conn.send('hello');
                }, 100);
            }

            $('form').on("submit", function (e) {
                e.preventDefault();
            });


    </script>

@endsection

