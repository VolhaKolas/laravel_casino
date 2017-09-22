@if(\Casino\Classes\Game\Players::currentBetter()->id == \Illuminate\Support\Facades\Auth::id())
    @if(0 == \Casino\Classes\Game\Players::lastBetter() and \Casino\Classes\Game\Players::currentBet() != 0)
        <div id="bet">
            <form enctype="multipart/form-data" method="POST" id="makeBet" action="{{  route('bet')  }}">
                {{ csrf_field() }}
                @if(\Casino\Classes\Game\Players::checkMoney() >= 0)
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
                @endif
                <div class="container">
                    <div class="row">
                        <div class="checkbox checkbox-info">
                            <input id="call" name="call" type="checkbox">
                            <label for="call">
                                Принять ставку {{ \Casino\Classes\Game\Players::currentBet() }}$
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

                <input type="submit" value="Выбрать" onclick="sendSocket();" class="btn btn-success">
            </form>
        </div>
    @elseif(\Casino\Classes\Game\Players::open() < 4)
        <div id="bet">
            <form enctype="multipart/form-data" method="POST" id="nextBet" action="{{  route('next')  }}">
                {{ csrf_field() }}

                @if(\Casino\Classes\Game\Players::checkMoney() >= 0)
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
                @endif
                <div class="container">
                    <div class="row">
                        <div class="checkbox checkbox-info">
                            <input id="next" name="next" type="checkbox">
                            <label for="next">
                                Продолжить
                            </label>
                        </div>
                    </div>
                </div>

                <input type="submit" value="Выбрать" onclick="sendSocket();" class="btn btn-success">
            </form>
        </div>
    @endif
@elseif(\Casino\Classes\Game\Players::open() < 4)
    <div id="playerWaiting">
        Ожидание игрока: {{ \Casino\Classes\Game\Players::currentBetter()->login }}
    </div>
@endif

@if(4 == \Casino\Classes\Game\Players::open())
    <div id="bet">
        <form enctype="multipart/form-data" method="POST" action="{{  route('nextGame')  }}">
            {{ csrf_field() }}
            <input type="submit" value="Играть далее" onclick="sendSocket();" class="btn btn-success">
        </form>
    </div>
@endif