
$(document).ready(function () {
    var date = new Date().getTime();
    $("#online").val(date);


    var currentT = new Date().getTime();
    var t = currentT - $("#online").val();
    var data = $("#onlineForm").serialize();

//Отправляет ajax запрос, что пользователь только зашел. Надо будет удалить потом. И ввести чтобы данные не аяксом отправлялись.
        $.ajax({
            type: "POST",
            url: "/online",
            data: data,
            success: function (data) {
                console.log(data);
            }
        });


    setInterval(function () {
        currentT = new Date().getTime();
        t = currentT - $("#online").val();
        data = $("#onlineForm").serialize();

        if(t/60000 < 16) {

            $.ajax({
                type: "POST",
                url: "/online",
                data: data,
                success: function (data) {
                    console.log(data);
                }
            });
            return false;
        }

    }, 60000);
});
$('form').on("submit", function (e) {
    e.preventDefault();
});

$(document).on("keypress click mousemove touchmove", function(event) {
    var date = new Date().getTime();
    $("#online").val(date);
});