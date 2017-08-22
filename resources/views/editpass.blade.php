@extends('layouts.app')

@section('content')
    <form class="form-horizontal" enctype="multipart/form-data" method="POST" action="{{ route('editpass') }} ">
        {{ csrf_field() }}

        <fieldset>

            <!-- Form Name -->
            <legend>Изменить пароль</legend>

            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-4 control-label">Введите новый пароль</label>
                <div class="col-md-4">
                    <input id="password" name="password" type="password" value="" class="form-control input-md" required>
                </div>
            </div>

            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-4 control-label">Повторите пароль</label>
                <div class="col-md-4">
                    <input id="password_confirm" name="password_confirmation" type="password" value="" class="form-control input-md" required>
                </div>
            </div>

            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-4 control-label">Введите старый пароль</label>
                <div class="col-md-4">
                    <input id="oldpassword" name="oldpassword" type="password" value="" class="form-control input-md" required>
                </div>
            </div>

            <!-- submit input-->
            <div class="form-group">
                <label class="col-md-4 control-label"></label>
                <div class="col-md-4">
                    <input type="submit" value="Сохранить" class="form-control input-md btn btn-primary">
                </div>
            </div>

        </fieldset>
    </form>
@endsection