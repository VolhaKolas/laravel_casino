<div id="formWrapper">
    @if(\Casino\Classes\Game\Players::currentBetter()->id == \Illuminate\Support\Facades\Auth::id())
        @if(0 == \Casino\Classes\Game\Players::lastBetter() and \Casino\Classes\Game\Players::currentBet() != 0)
            <div id="bet">
                @include('layouts.betform')
            </div>

            <div id="betDone" style="display: none">
                @include('layouts.betDoneform')
            </div>
            <div id="playerWaiting" style="display: none">
                Ожидание игрока: {{ \Casino\Classes\Game\Players::currentBetter()->login }}
            </div>
            <div id="nextGame" style="display: none">
                @include('layouts.nextGameform')
            </div>

        @elseif(\Casino\Classes\Game\Players::open() < 4)
            <div id="betDone">
                @include('layouts.betDoneform')
            </div>

            <div id="bet" style="display: none">
                @include('layouts.betform')
            </div>
            <div id="playerWaiting" style="display: none">
                Ожидание игрока: {{ \Casino\Classes\Game\Players::currentBetter()->login }}
            </div>
            <div id="nextGame" style="display: none">
                @include('layouts.nextGameform')
            </div>

        @endif
    @elseif(\Casino\Classes\Game\Players::open() < 4)
        <div id="playerWaiting">
            Ожидание игрока: {{ \Casino\Classes\Game\Players::currentBetter()->login }}
        </div>

        <div id="bet" style="display: none">
            @include('layouts.betform')
        </div>
        <div id="betDone" style="display: none">
            @include('layouts.betDoneform')
        </div>
        <div id="nextGame" style="display: none">
            @include('layouts.nextGameform')
        </div>

    @endif

    @if(\Casino\Classes\Game\Players::open() >= 4)
        <div id="nextGame">
            @include('layouts.nextGameform')
        </div>

        <div id="bet" style="display: none">
            @include('layouts.betform')
        </div>
        <div id="betDone" style="display: none">
            @include('layouts.betDoneform')
        </div>
        <div id="playerWaiting" style="display: none">
            Ожидание игрока: {{ \Casino\Classes\Game\Players::currentBetter()->login }}
        </div>

    @endif
</div>