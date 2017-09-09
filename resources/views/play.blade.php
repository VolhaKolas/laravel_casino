@extends('layouts.app')

@if(!Auth::guest())
@section('content')

    <form enctype="multipart/form-data" method="POST" action="{{  route('play')  }}" id="play">
        {{ csrf_field() }}

        <div class="container">
            <h2 class="section-heading text-center">Создать игру</h2>
            <h3 class="lead text-center"></h3>

            <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h3 class="text-center">Выбранные пользователи</h3>
                            <p class="text-center">Выбрано пользователей: <b id="count">0</b></p>
                        </div>

                        <div class="panel-body" id="selected">
                        </div>

                        <div class="panel-footer">
                            <input type="submit" value="Создать игру" onclick="sendInvitation();" class="form-control input-md btn btn-primary">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h3 class="text-center">Выбрать пользователей</h3>
                            <p class="text-center">Минимальное число пользователей: 1</p>
                            <p class="text-center">Максимальное число пользователей: 5</p>
                        </div>

                        <div class="panel-body" id="select">
                            @if(count($users) > 0)
                                @foreach($users as $user)
                                    <div class="container">
                                        <div class="row">
                                            <div class="checkbox checkbox-info">
                                                <input id="checkbox{{  $user->id  }}" data-name="{{ $user->id }}" name="checkbox{{  $user->id  }}" type="checkbox">
                                                <label for="checkbox{{  $user->id  }}">
                                                    {{  $user->name  }} {{  $user->lastname  }} ({{  $user->login  }})
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </form>


    <div class="container" id="message">
        <div class="alert alert-danger">
            <button href="#" type="button" class="close">x</button>
            <h4>Сообщение об ошибке</h4>
            <p></p>
        </div>
    </div>


@endsection
@endif