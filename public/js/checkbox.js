$("#select input[type='checkbox']").on("click", function () {
    var check = document.querySelectorAll("#select input[type='checkbox']");
    count = 0;
    for(var j = 0; j < check.length; j++) {
        if($(check[j]).prop('checked') == true) {
            count++;
        }
    }
    if(count == 6) {
        $(this).prop("checked", false);
        $("#message p").empty();
        $("#message p").append("Количество выбранных игроков не должно быть больше 5");
        $('#message').css('display', "block");
        setTimeout(function () {
            $('#message').css('display', "none");
        }, 3000);
    }
    else {
        if($(this).prop('checked') == true) {
            /*
            var clone = $(this).parent().parent().parent().clone();
            var input = $(clone).children().eq(0).children().eq(0).children().eq(0);
            $(input).removeAttr('name');
            $(input).removeAttr('id');
            var label = $(clone).children().eq(0).children().eq(0).children().eq(1);
            $(label).removeAttr('for');
            $("#selected").append(clone);
            */
        }
        else {

        }
    }
});

$("#message .close").on("click", function () {
    $("#message").css("display", "none");
});
