$(document).ready(function () {
    var date = new Date().getTime();
    $("#time").val(date);
    $('#online').val(1);
    var data = $("#onlineForm").serialize();

//Отправляет ajax запрос, что пользователь только зашел. Надо будет удалить потом. И ввести чтобы данные не аяксом отправлялись.
    $.ajax({
        type: "POST",
        url: "/online",
        data: data,
    });


    setInterval(function () {
        currentT = new Date().getTime();
        t = (currentT - $("#time").val())/60000;
        var online = 1;
        if(t > 15) {
            online = 0;
        }
        else {
            online = 1;
        }
        $('#online').val(online);
        data = $("#onlineForm").serialize();

        if(t < 16) {

            $.ajax({
                type: "POST",
                url: "/online",
                data: data,
            });
            return false;
        }

    }, 60000);
});

$(document).on("keypress mousemove touchmove", function(event) {
    var date = new Date().getTime();
    $("#time").val(date);
});