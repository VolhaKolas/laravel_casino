<form action="{{ route('online')  }}" method="POST" id="onlineForm">
    <input type="hidden" value="" name="time" id="time">
    <input type="hidden" value="" name="online" id="online">
    {{ csrf_field() }}
</form>
