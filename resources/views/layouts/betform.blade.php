<form enctype="multipart/form-data" method="POST" id="makeBet" action="{{  route('bet')  }}">
    @if(\Casino\Classes\Game\Players::checkMoney() >= 0)
        <div class="container" id="raiseBet">
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

    <input type="submit" value="Выбрать" onclick="sendSocket(); return false;" class="btn btn-success">
    {{ csrf_field() }}
</form>
