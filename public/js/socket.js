
var conn = new WebSocket("ws://localhost:8080");

conn.onmessage = function (e) {
    var data = JSON.parse(e.data);
    if(data['connection'] != undefined) {
        $.ajax({
            type: "POST",
            url: "/socket",
            data: data
        });
    }
    else {
        $.ajax({
            type: "POST",
            url: "/setinput",
            success: function (setData) {
                var admission = $("#admission");
                if(admission != undefined) {
                    $("#admission").val(setData);
                    $("#refusal").val(setData);
                }
            }
        });

        if (data['answer'] != undefined) {
            $.ajax({
                type: "POST",
                url: "/answer",
                data: data,
                success: function (getData) {
                    if (0 == getData) {
                        $("#waiting").css("display", "none");
                        window.location.href = "/play";
                    }
                    else if (2 == getData) {
                        $("#waiting").css("display", "none");
                        $("#game").css("display", "block");
                    }
                    else {
                        var userToDel = data['user'];
                        var p = $("#waiting p");
                        for (var i = 0; i < p.length; i++) {
                            if ($(p).eq(i).attr('id') == userToDel) {
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
    }
};




function sendInvitation() {
    var data = {};
    data['user'] = $("#user").attr('data-user');
    var count = 1;
    var ajaxData = {};
    ajaxData[count] = $("#user").attr('data-user');
    var checkbox = $('#select input[type="checkbox"]');
    var check = 0; //this var checks if user checked somebody
    for(var i = 0; i < checkbox.length; i++) {
        if($(checkbox[i]).prop('checked') == true) {
            count++;
            ajaxData[count] = $(checkbox[i]).attr('data-name');
            check = 1;
        }
    }
    ajaxData = JSON.stringify(ajaxData);
    $.ajax({
        type: "POST",
        url: "/invitation",
        data: ajaxData,
        success: function (getData) {
            var users = JSON.parse(getData);
            for(var i = 0; i < users.length; i++) {
                data[i + 1] = users[i];
            }
            if(1 == check) {
                data = JSON.stringify(data);
                conn.send(data);
            }
        }
    });
}




function sendAdmission() {
    var data = {};
    data['user'] = $("#user").attr('data-user');
    data['answer'] = 1;
    var ajaxData = {};
    var input = $("#admission").val();
    input = JSON.parse(input);
    for(var i = 0; i < input.length; i++) {
        ajaxData[i + 1] = input[i];
    }
    ajaxData = JSON.stringify(ajaxData);
    $.ajax({
        type: "POST",
        url: "/invitation",
        data: ajaxData,
        success: function (getData) {
            var users = JSON.parse(getData);
            for(var i = 0; i < users.length; i++) {
                data[i + 1] = users[i];
            }
            data = JSON.stringify(data);
            conn.send(data);
            $("#offer").unbind('submit').submit();
        }
    });
    $("#screen").css("display", "none");
    $("#waiting").css("display", "block");
}


function sendRefusal() {
    var data = {};
    data['user'] = $("#user").attr('data-user');
    data['answer'] = 0;
    var ajaxData = {};
    var input = $("#refusal").val();
    input = JSON.parse(input);
    for(var i = 0; i < input.length; i++) {
        ajaxData[i + 1] = input[i];
    }
    ajaxData = JSON.stringify(ajaxData);
    $.ajax({
        type: "POST",
        url: "/invitation",
        data: ajaxData,
        success: function (getData) {
            var users = JSON.parse(getData);
            for(var i = 0; i < users.length; i++) {
                data[i + 1] = users[i];
            }
            data = JSON.stringify(data);
            conn.send(data);
            $("#break").unbind('submit').submit();
        }
    });
}

$("#break").on("submit", function () {
    return false;
});

$("#offer").on("submit", function () {
    return false;
});