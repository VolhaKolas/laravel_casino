
<div id="table">
    @if(!Auth::guest())
        @if(\Casino\Classes\Game\Players::gameContinue() > 0)
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
        @else
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
                            @if(\Casino\Classes\Game\Players::players()[$i]->id == Illuminate\Support\Facades\Auth::id())
                                <div class="card1" style="background-position: {{100/12 * (\Casino\Classes\Game\Players::players()[$i]->uc_card % 100 - 2)}}% {{100/4 * floor(\Casino\Classes\Game\Players::players()[$i]->uc_card/100)}}%;"></div>
                                <div class="card2" style="background-position: {{100/12 * (\Casino\Classes\Game\Players::players()[$i + 1]->uc_card % 100 - 2)}}% {{100/4 * floor(\Casino\Classes\Game\Players::players()[$i + 1]->uc_card/100)}}%;"></div>
                            @else
                                <div class="card1"></div>
                                <div class="card2"></div>
                            @endif
                        </div>
                        <div class="player"><b>{{ \Casino\Classes\Game\Players::players()[$i]->login }}</b><p>{{ \Casino\Classes\Game\Players::players()[$i]->u_money }}$</p></div>
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
            @if(\Casino\Classes\Game\Players::currentBetter()->id == \Illuminate\Support\Facades\Auth::id())
                <div id="bet">
                    <form enctype="multipart/form-data" method="POST" action="{{  route('bet')  }}">
                        {{ csrf_field() }}
                        <div class="container">
                            <div class="row">
                                <div class="checkbox checkbox-info">
                                    <input id="raise" name="raise" type="checkbox">
                                    <label for="raise">
                                        Повысить ставку на {{ \Casino\Classes\Game\Players::BET }}$
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="container">
                            <div class="row">
                                <div class="checkbox checkbox-info">
                                    <input id="call" name="call" type="checkbox">
                                    <label for="call">
                                        Принять ставку
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="container">
                            <div class="row">
                                <div class="checkbox checkbox-info">
                                    <input id="fold" name="fold" type="checkbox">
                                    <label for="fold">
                                        Сбросить карты
                                    </label>
                                </div>
                            </div>
                        </div>

                        <input type="submit" value="Выбрать" class="btn btn-success">
                    </form>
                </div>
            @else
                <div id="playerWaiting">
                Ожидание игрока: {{ \Casino\Classes\Game\Players::currentBetter()->login }}
                </div>
            @endif
        @endif

    @endif
</div>
