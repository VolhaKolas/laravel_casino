var conn = new WebSocket("ws://localhost:8080");


conn.onmessage = function (e) {
    var data = JSON.parse(e.data);
    if(data['answer'] != undefined) {
        $.ajax({
            type: "POST",
            url: "/answer",
            data: data,
            success: function (getData) {
                if(0 == getData) {
                    $("#waiting").css("display", "none");
                    window.location.href = "/preplay";
                }
                else if(2 == getData) {
                    $("#waiting").css("display", "none");
                    //TODO-добавить открытие покерного стола
                }
                else {
                    var userToDel = data['user'];
                    var p = $("#waiting p");
                    for (var i = 0; i < p.length; i++) {
                        console.log($(p).eq(i).attr('id'));
                        if($(p).eq(i).attr('id') == userToDel) {
                            $("#waiting p").eq(i).remove();
                        }
                    }

                }
            }
        });
    }
    else {
        $("#screen").css("display", "block");
    }
};



function sendInvitation() {
    var data = {};
    data['user'] = $("#user").attr('data-user');
    var checkbox = $('#select input[type="checkbox"]');
    var check = 0; //this var checks if user checked somebody
    for(var i = 0; i < checkbox.length; i++) {
        if($(checkbox[i]).prop('checked') == true) {
            data[i] = $(checkbox[i]).attr('data-name');
            check = 1;
        }
    }
    data = JSON.stringify(data);
    if(1 == check) {
        conn.send(data);
    }
}


function sendAdmission() {
    var data = {};
    data['user'] = $("#user").attr('data-user');
    data['answer'] = 1;
    data = JSON.stringify(data);
    conn.send(data);
    $("#screen").css("display", "none");
    $("#waiting").css("display", "block");
}


function sendRefusal() {
    var data = {};
    data['user'] = $("#user").attr('data-user');
    data['answer'] = 0;
    data = JSON.stringify(data);
    conn.send(data);
}