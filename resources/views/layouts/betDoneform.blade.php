<form enctype="multipart/form-data" method="POST" id="nextBet" action="{{  route('next')  }}">
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

    <input type="submit" value="Выбрать" onclick="sendSocket(); return false;" class="btn btn-success">
    {{ csrf_field() }}
</form>