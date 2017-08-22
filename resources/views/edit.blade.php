@extends('layouts.app')

@section('content')
    <form class="form-horizontal" enctype="multipart/form-data" method="POST" action="{{  route('edit')  }}">
        {{ csrf_field() }}

        <fieldset>

            <!-- Form Name -->
            <legend>Редактировать профиль</legend>


            <div class="form-group">
                <label class="col-md-4 control-label">Логин</label>
                <div class="col-md-4 centered-top">
                    <div class="top-cover center-block" id="login">
                        {{ $user->login  }}
                    </div>
                </div>
            </div>

            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-4 control-label">Имя</label>
                <div class="col-md-4">
                    <input id="name" name="name" type="text" value="{{ $user->name }}" class="form-control input-md" required>
                </div>
            </div>

            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-4 control-label">Фамилия</label>
                <div class="col-md-4">
                    <input id="lastname" name="lastname" type="text" value=" {{  $user->lastname }}" class="form-control input-md" required>
                </div>
            </div>

            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-4 control-label">Изменить фото профиля</label>
                <div class="col-md-4" id="imageWrapper">
                    <div id="button" onclick="document.getElementById('photo').click()"></div>
                    <input id="image" type="text" class="form-control input-md" onclick="document.getElementById('photo').click()">
                    <input id="photo" name="u_photo" accept="image/jpg,image/jpeg,image/png,image/gif" type="file" onchange="document.getElementById('image').value = (this.files[0] != undefined) ? this.files[0].name : '' " class="form-control input-md">
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-4 control-label"></label>
                <div class="col-md-4">
                    <img id="img" src=" {{ $filePath }} ">
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

