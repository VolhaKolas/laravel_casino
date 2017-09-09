<div id="center">
    <div class="row form">
        <div class="col-xs-2 col-xs-offset-5">
            <form action="{{ route('admission')   }}" method="post" enctype="multipart/form-data" id="offer">
                {{ csrf_field() }}
                <input type="submit" value="Играть" onclick="sendAdmission();" class="btn btn-primary">
                <input type="hidden" value="{{ \Casino\User::players()  }}" id="admission">
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-2 col-xs-offset-5">
            <form action="{{ route('break')   }}" enctype="multipart/form-data" method="post" id="break">
                {{ csrf_field() }}
                <input type="submit" value="Отказаться" onclick="sendRefusal();" class="btn btn-danger">
                <input type="hidden" value="{{ \Casino\User::players()  }}" id="refusal">
            </form>
        </div>
    </div>
</div>
