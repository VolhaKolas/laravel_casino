
@extends('layouts.main')

@section('content')




    @if(isset(\App\Classes\Position\Blinds::blinds()[0]) and \App\Classes\Position\Blinds::blinds()[1] != 0)
        @if(\App\User_card::where('user_id', auth()->id())->count() == 1)
            <div class="background">
                @if(\App\Classes\Position\Blinds::blinds()[0] != 0)
                    @include('partials.blinds')
                @else
                    @include('partials.blinds2')
                @endif

                @include('partials.dealerCards')
            </div>

            <script src="{{asset('js/dealerDetermine.js')}}"></script>


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

                <script src="{{asset('js/chips.js')}}"></script>
        @endif

        @if(\App\Table_card::where('table_id', auth()->user()->tableUsers->table_id)->value('open') >= 4)
            <script src="{{asset('js/newDeal.js')}}"></script>
        @endif


    @else
        <script src="{{asset('js/reload.js')}}"></script>
    @endif



    <script src="{{asset('js/choice.js')}}"></script>

@endsection

