<div id="table">
    @if(!Auth::guest())
        @if(\Casino\Classes\Game\Players::gameContinue() > 0) <!-- если определяется дилер при первой раздаче карт  -->
            @foreach(\Casino\Classes\Game\Players::players() as $player)
                @if($player->u_photo != null)
                    <div class="photo" id="photo{{ $player->u_place }}" style="background-image: url('photos/{{$player->id}}/{{$player->u_photo}}')"></div>
                @else
                    <div class="photo" id="photo{{ $player->u_place }}"></div>
                @endif
                <div id="player{{ $player->u_place }}" data-id="{{ $player->id  }}">
                    <div class="card">
                        @if($player->id == Illuminate\Support\Facades\Auth::id() or $player->id == \Casino\Classes\Game\Players::dealer())
                            <div class="card1" style="background-position: {{100/12 * ($player->u_dealer_card % 100 - 2)}}% {{100/4 * floor($player->u_dealer_card/100)}}%;"></div>
                        @else
                            <div class="card1"></div>
                        @endif
                        <div class="card2"></div>
                </div>
                    <div class="player"><b>{{ $player->login }}</b><p>{{ $player->u_money }}$</p></div>
                </div>
                    @if($player->id == \Casino\Classes\Game\Players::dealer())
                        <div id="dealer{{ $player->u_place }}"></div>
                    @endif
            @endforeach

                <div class="continue" id="continue">
                    <form enctype="multipart/form-data" method="POST" action="{{  route('continue')  }}">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <div class="col-md-4">
                                <input type="submit" value="Продолжить" class="btn btn-success">
                            </div>
                        </div>
                    </form>
                </div>
        @else <!-- обычная раздача карт -->
            @for($i = 0; $i < count(\Casino\Classes\Game\Players::players()); $i = $i + 2)
                @if(Casino\Classes\Game\Players::players()[$i]->u_photo != null)
                    <div class="photo" id="photo{{ Casino\Classes\Game\Players::players()[$i]->u_place }}"
                         style="background-image: url('photos/{{Casino\Classes\Game\Players::players()[$i]->id}}/{{Casino\Classes\Game\Players::players()[$i]->u_photo}}')"></div>
                @else
                    <div class="photo" id="photo{{ Casino\Classes\Game\Players::players()[$i]->u_place }}"></div>
                @endif
                @if(\Casino\Classes\Game\Players::players()[$i]->id == Illuminate\Support\Facades\Auth::id())
                @else
                @endif
                    <div id="player{{ \Casino\Classes\Game\Players::players()[$i]->u_place }}" data-id="{{ \Casino\Classes\Game\Players::players()[$i]->id  }}">
                        <div class="card">
                            @if(4 == \Casino\Classes\Game\Players::open() or \Casino\Classes\Game\Players::players()[$i]->id == Illuminate\Support\Facades\Auth::id() or
                            in_array(\Casino\Classes\Game\Players::players()[$i]->id, \Casino\Classes\Game\Players::foldUsers()))
                                <div class="card1" style="background-position: {{100/12 * (\Casino\Classes\Game\Players::players()[$i]->uc_card % 100 - 2)}}% {{100/4 * floor(\Casino\Classes\Game\Players::players()[$i]->uc_card/100)}}%;"></div>
                                <div class="card2" style="background-position: {{100/12 * (\Casino\Classes\Game\Players::players()[$i + 1]->uc_card % 100 - 2)}}% {{100/4 * floor(\Casino\Classes\Game\Players::players()[$i + 1]->uc_card/100)}}%;"></div>
                            @else
                                <div class="card1"></div>
                                <div class="card2"></div>
                            @endif
                        </div>
                        <div class="player">
                            <b>{{ \Casino\Classes\Game\Players::players()[$i]->login }}</b>
                            <p>{{ \Casino\Classes\Game\Players::players()[$i]->u_money }}$</p>
                            @if(in_array(\Casino\Classes\Game\Players::players()[$i]->id, \Casino\Classes\Game\Players::foldUsers()))
                                <p>fold</p>
                            @endif
                        </div>
                    </div>
                    @if(\Casino\Classes\Game\Players::players()[$i]->id == \Casino\Classes\Game\Players::dealer())
                        <div id="dealer{{ \Casino\Classes\Game\Players::players()[$i]->u_place }}"></div>
                    @endif
            @endfor
            <div id="bet">
            </div>
        @endif

        <div class="exit" id="exit">
            <form enctype="multipart/form-data" method="POST" action="{{  route('exit')  }}">
                {{ csrf_field() }}
                <div class="form-group">
                    <div class="col-md-4">
                        <input type="submit" value="Выйти" class="btn btn-danger">
                    </div>
                </div>
            </form>
        </div>

        <div id="pot">
            POT: {{ \Casino\Classes\Game\Players::tableMoney() }}$
        </div>


        @if(gettype(\Casino\Classes\Game\Players::currentBetter()) != 'integer')
            @include('layouts.forms')

            @if(\Casino\Classes\Game\Players::open() != null)
                @if(\Casino\Classes\Game\Players::open() >= 1)
                    <div id="flop1" style="background-position: {{100/12 * (\Casino\Classes\Game\Players::flop1() % 100 - 2)}}% {{100/4 * floor(\Casino\Classes\Game\Players::flop1()/100)}}%;">
                    </div>
                    <div id="flop2" style="background-position: {{100/12 * (\Casino\Classes\Game\Players::flop2() % 100 - 2)}}% {{100/4 * floor(\Casino\Classes\Game\Players::flop2()/100)}}%;">
                    </div>
                    <div id="flop3" style="background-position: {{100/12 * (\Casino\Classes\Game\Players::flop3() % 100 - 2)}}% {{100/4 * floor(\Casino\Classes\Game\Players::flop3()/100)}}%;">
                    </div>
                    @if(\Casino\Classes\Game\Players::open() >= 2)
                        <div id="turn" style="background-position: {{100/12 * (\Casino\Classes\Game\Players::turn() % 100 - 2)}}% {{100/4 * floor(\Casino\Classes\Game\Players::turn()/100)}}%;">
                        </div>
                        @if(\Casino\Classes\Game\Players::open() >= 3)
                            <div id="river" style="background-position: {{100/12 * (\Casino\Classes\Game\Players::river() % 100 - 2)}}% {{100/4 * floor(\Casino\Classes\Game\Players::river()/100)}}%;">
                            </div>
                        @endif
                    @endif
                @endif
            @endif
        @endif

    @endif
</div>
