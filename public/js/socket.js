
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
                    else if (1 == getData) {
                        var userToDel = data['user'];
                        var p = $("#waiting p");
                        for (var i = 0; i < p.length; i++) {
                            if ($(p).eq(i).attr('id') == userToDel) {
                                $("#waiting p").eq(i).remove();
                            }
                        }
                    }
                    else {
                        getData = JSON.parse(getData);
                        var contin = document.getElementById('continue');
                        var exit = document.getElementById('exit');
                        $('#table').empty();
                        for (var i = 0; i < getData.length; i++) {
                            var photo = getData[i].photo;
                            var addPhoto;
                            var card1;
                            var card2 = '<div class="card2"></div>';
                            if(photo != null) {
                                var url = "photos/" +  getData[i].id + "/" + getData[i].photo;
                                addPhoto = '<div class="photo" id="photo' + getData[i].place + '" style="background-image: url(' + url + ')"></div>';
                            }
                            else {
                                addPhoto = '<div class="photo" id="photo' + getData[i].place + '"></div>';
                            }
                            if(getData[i].id == getData[i].user || getData[i].id == getData[i].dealer) {
                                card1 = '<div class="card1" style="background-position:' + 100/12 * (getData[i].card % 100 - 2) + '%' + 100/4 * Math.round(getData[i].card/100) + '%;"></div>';
                            }
                            else {
                                card1 = '<div class="card1"></div>';
                            }
                            var cards = '<div class="card">' + card1 + card2 + '</div>';
                            var play = '<div class="player"><b>' + getData[i].login + '</b><p>' + getData[i].money + '$</p></div>';
                            var player = '<div id="player' + getData[i].place + '" data-id="' + getData[i].id + '">' + cards + play + '</div>';

                            if(getData[i].id == getData[i].dealer) {
                                var dealer = '<div id="dealer' + getData[i].place + '"></div>';
                                $('#table').append(dealer);
                            }

                            $('#table').append(addPhoto);
                            $('#table').append(player);
                        }
                        $('#table').append(contin);
                        $('#table').append(exit);
                        $("#waiting").css("display", "none");
                        $("#game").css("display", "block");
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


function sendSocket() {
}


$("#break").on("submit", function () {
    return false;
});

$("#offer").on("submit", function () {
    return false;
});