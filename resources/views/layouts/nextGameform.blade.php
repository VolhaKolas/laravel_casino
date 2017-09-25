<form enctype="multipart/form-data" method="POST" action="{{  route('nextGame')  }}">
    <input type="submit" value="Играть далее" onclick="sendSocket();" class="btn btn-success">
    {{ csrf_field() }}
</form>