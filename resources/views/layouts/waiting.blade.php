<div id="center">
    <div class="row form">
        <div class="col-xs-2 col-xs-offset-5">
            Ожидание игроков:
            @foreach(\Casino\User::loginIdToAnswer() as $key => $login)
                <p id="{{  $key  }}">- {{  $login  }}</p>
            @endforeach
        </div>
    </div>
</div>
