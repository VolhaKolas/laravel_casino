
var conn = new WebSocket("ws://localhost:8080");

conn.onmessage = function (e) {
    var data = JSON.parse(e.data);
    if(data['game'] != undefined) {
        setTimeout(function () {
        $.ajax({
            type: "POST",
            url: "/socketMessage",
            success: function (getData) {
                getData = JSON.parse(getData);
                if (1 == getData[0].form) {
                    $('#bet').css('display', 'block');
                    $('#bet').children().eq(0).children().eq(1).children().eq(0).children().eq(0).children().eq(1).empty();
                    $('#bet').children().eq(0).children().eq(1).children().eq(0).children().eq(0).children().eq(1).html('Принять ставку ' + getData[0].currentBet + '$');

                    if(getData[0].checkMoney < 0) {
                        $('#raiseBet').css('display', 'none');
                    }
                    else {
                        $('#raiseBet').children().eq(0).css('display', 'block');
                        $('#raiseBet').children().eq(0).children().eq(0).children().eq(0).children().eq(1).empty();
                        $('#raiseBet').children().eq(0).children().eq(0).children().eq(0).children().eq(1).html('Повысить ставку на ' + getData[0].bet + '$');
                    }

                    $('#betDone').css('display', 'none');
                    $('#playerWaiting').css('display', 'none');
                    $('#nextGame').css('display', 'none');
                }
                else if (2 == getData[0].form) {
                    $('#bet').css('display', 'none');
                    $('#betDone').css('display', 'block');
                    if(getData[0].checkMoney < 0) {
                        $('#bet').children().eq(0).children().eq(0).css('display', 'none');
                    }
                    else {
                        $('#bet').children().eq(0).children().eq(0).css('display', 'block');
                        $('#bet').children().eq(0).children().eq(0).children().eq(0).children().eq(0).children().eq(1).empty();
                        $('#bet').children().eq(0).children().eq(0).children().eq(0).children().eq(0).children().eq(1).html('Повысить ставку на ' + getData[0].bet + '$');
                    }

                    $('#playerWaiting').css('display', 'none');
                    $('#nextGame').css('display', 'none');

                }
                else if (3 == getData[0].form) {
                    $('#bet').css('display', 'none');
                    $('#betDone').css('display', 'none');
                    $('#playerWaiting').css('display', 'block');
                    $('#playerWaiting').empty();
                    $('#playerWaiting').html(' Ожидание игрока: ' + getData[0].currentBetter);

                    $('#nextGame').css('display', 'none');
                }
                else {
                    $('#bet').css('display', 'none');
                    $('#betDone').css('display', 'none');
                    $('#playerWaiting').css('display', 'none');
                    $('#nextGame').css('display', 'block');
                }
                var betForm = document.getElementById('formWrapper');


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
                    var fold = '';
                    if(1 == getData[i].fold) {
                        fold = '<p>fold</p>';
                    }
                    var play = '<div class="player"><b>' + getData[i].login + '</b><p>' + getData[i].u_money + '$' + fold +'</div>';
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
                $('#table').append(betForm);
            }
        });
    }, 1000);
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
    var checkbox2 = $('#nextBet input[type="checkbox"]');
    var sendMessage = 0;
    var sendMessage2 = 0;
    for(var i = 0; i < checkbox.length; i++) {
        if($(checkbox[i]).prop('checked') == true) {
            sendMessage = 1;
        }
    }
    for(var i = 0; i < checkbox2.length; i++) {
        if($(checkbox2[i]).prop('checked') == true) {
            sendMessage2 = 1;
        }
    }
    if(1 == sendMessage || 1 == sendMessage2) {
        $.ajax({
            type: "POST",
            url: "/socketGame",
            success: function (getData) {
                conn.send(getData);
                if(1 == sendMessage) {
                    $("#makeBet").unbind('submit').submit();
                }
                else if(1 == sendMessage2) {
                    $("#nextBet").unbind('submit').submit();
                }
            }
        })
    }
}


$("#break").on("submit", function () {
    return false;
});

$("#offer").on("submit", function () {
    return false;
});