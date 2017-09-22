
var conn = new WebSocket("ws://localhost:8080");

conn.onmessage = function (e) {
    var data = JSON.parse(e.data);
    if(data['game'] != undefined) {
        $.ajax({
            type: "POST",
            url: "/socketMessage",
            success: function (getData) {
                getData = JSON.parse(getData);
                console.log(getData);
                /*
                var bet;
                var div;
                var tok = "{{ csrf_field() }}";
                var inp;
                var inp2;
                var inp3;
                var inp4 = '<input type="submit" value="Выбрать" onclick="sendSocket();" class="btn btn-success">';
                if (1 == getData[0].form) {
                    div = '<div id="bet"><form enctype="multipart/form-data" method="POST" action="{{  route(\'next\')  }}">';
                    inp2 = '<div class="container"><div class="row"><div class="checkbox checkbox-info"><input id="call" name="call" type="checkbox"><label for="call">Принять ставку ' + getData[0].currentBet + '$</label></div></div></div>';
                    inp3 ='<div class="container"><div class="row"><div class="checkbox checkbox-info"><input id="fold" name="fold" type="checkbox"><label for="fold">Сбросить карты</label></div></div></div>';
                    if(getData[0].checkMoney >= 0) {
                        inp = '<div class="container"><div class="row"><div class="checkbox checkbox-info"><input id="raise" name="raise" type="checkbox"><label for="raise">Повысить ставку на ' + getData[0].bet + '$</label></div></div></div>';
                        bet = div + inp + inp2 + inp3 + inp4 + '</form></div>';
                    }
                    else {
                        bet = div + inp2 + inp4 + '</form></div>';
                    }
                }
                else if (2 == getData[0].form) {
                    div = '<div id="bet"><form enctype="multipart/form-data" method="POST" action="{{  route(\'next\')  }}">';
                    inp2 = '<div class="container"><div class="row"><div class="checkbox checkbox-info"><input id="next" name="next" type="checkbox"><label for="next">Продолжить</label></div> </div></div><input type="submit" value="Выбрать" onclick="sendSocket();" class="btn btn-success">';
                    if(getData[0].checkMoney >= 0) {
                        inp = '<div class="container"><div class="row"><div class="checkbox checkbox-info"><input id="raise" name="raise" type="checkbox"><label for="raise">Повысить ставку на ' + getData[0].bet + '$</label></div></div></div>';
                        bet = div + inp + inp2 + '</form></div>';
                    }
                    else {
                        bet = div + inp2 + '</form></div>';
                    }
                }
                else if (3 == getData[0].form) {
                    bet = "<div id='playerWaiting'>Ожидание игрока: " + getData[0].currentBetter + "</div>";
                }
                else {
                    div = '<div id="bet"><form enctype="multipart/form-data" method="POST" action="{{  route(\'nextGame\')  }}">';
                    inp = '<input type="submit" value="Играть далее" onclick="sendSocket();" class="btn btn-success">';
                    bet = div + tok + inp + "</form></div>";
                }


                var exit = document.getElementById('exit');
                $('#table').empty();
                for (var i = 0; i < getData.length; i++) {
                    var photo = getData[i].photo;
                    var addPhoto;
                    var card1;
                    var card2;
                    if(photo != null) {
                        var url = "photos/" +  getData[i].id + "/" + getData[i].photo;
                        addPhoto = '<div class="photo" id="photo' + getData[i].place + '" style="background-image: url(' + url + ')"></div>';
                    }
                    else {
                        addPhoto = '<div class="photo" id="photo' + getData[i].place + '"></div>';
                    }
                    if(getData[i].id == getData[i].user || 4 == getData[i].open || 1 == getData[i].fold) {
                        card1 = '<div class="card1" style="background-position:' + 100/12 * (getData[i].card1 % 100 - 2) + '%' + 100/4 * Math.round(getData[i].card1/100) + '%;"></div>';
                        card2 = '<div class="card1" style="background-position:' + 100/12 * (getData[i].card2 % 100 - 2) + '%' + 100/4 * Math.round(getData[i].card2/100) + '%;"></div>';
                    }
                    else {
                        card1 = '<div class="card1"></div>';
                        card2 = '<div class="card2"></div>';
                    }
                    var cards = '<div class="card">' + card1 + card2 + '</div>';
                    var play = '<div class="player"><b>' + getData[i].login + '</b><p>' + getData[i].u_money + '$</p><p>fold</p></div>';
                    var player = '<div id="player' + getData[i].place + '" data-id="' + getData[i].id + '">' + cards + play + '</div>';

                    if(getData[i].id == getData[i].dealer) {
                        var dealer = '<div id="dealer' + getData[i].place + '"></div>';
                        $('#table').append(dealer);
                    }

                    $('#table').append(addPhoto);
                    $('#table').append(player);
                }

                var pot = '<div id="pot">POT: ' + getData[0].t_money + '$</div>';

                var flop = '';
                var turn = '';
                var river = '';
                if(getData[0].open >= 1) {
                    var flop1 = '<div id="flop1" style="background-position:' + 100/12 * (getData[0].flop1 % 100 - 2) + '%' + 100/4 * Math.round(getData[0].flop1/100) + '%;"></div>';
                    var flop2 = '<div id="flop2" style="background-position:' + 100/12 * (getData[0].flop2 % 100 - 2) + '%' + 100/4 * Math.round(getData[0].flop2/100) + '%;"></div>';
                    var flop3 = '<div id="flop3" style="background-position:' + 100/12 * (getData[0].flop3 % 100 - 2) + '%' + 100/4 * Math.round(getData[0].flop3/100) + '%;"></div>';
                    flop = flop1 + flop2 + flop3;
                }
                if(getData[0].open >= 2) {
                    turn = '<div id="turn" style="background-position:' + 100/12 * (getData[0].turn % 100 - 2) + '%' + 100/4 * Math.round(getData[0].turn/100) + '%;"></div>';
                }
                if(getData[0].open >= 3) {
                    river = '<div id="river" style="background-position:' + 100/12 * (getData[0].river % 100 - 2) + '%' + 100/4 * Math.round(getData[0].river/100) + '%;"></div>';
                }
                flop = flop + turn + river;

                $('#table').append(exit);
                $('#table').append(pot);
                $('#table').append(flop);
                $('#table').append(bet);
                */
            }
        });
    }
    else if(data['connection'] != undefined) {
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
    var checkbox = $('#bet input[type="checkbox"]');
    var sendMessage = 0;
    for(var i = 0; i < checkbox.length; i++) {
        if($(checkbox[i]).prop('checked') == true) {
            sendMessage = 1;
        }
    }
    if(1 == sendMessage) {
        $.ajax({
            type: "POST",
            url: "/socketGame",
            success: function (getData) {
                conn.send(getData);
                $("#makeBet").unbind('submit').submit();
                $("#nextBet").unbind('submit').submit();
            }
        })
    }
}

$("#makeBet").on("submit", function () {
    return false;
});

$("#nextBet").on("submit", function () {
    return false;
});


$("#break").on("submit", function () {
    return false;
});

$("#offer").on("submit", function () {
    return false;
});