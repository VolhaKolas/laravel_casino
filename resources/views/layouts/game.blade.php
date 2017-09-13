<div id="table">
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
            <div class="player"><b>{{ $player->login }}</b><p>{{ $player->u_money }}</p></div>
        </div>
            @if($player->id == \Casino\Classes\Game\Players::dealer())
                <div id="dealer{{ $player->u_place }}"></div>
            @endif
    @endforeach
</div>
