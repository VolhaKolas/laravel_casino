(function () {
    var time;
    if(open < 5) {
        time = 10000;
    }
    else {
        time = 1000;
    }

    setTimeout(function () {
        $.ajax({
            type: "GET",
            url: "/new-deal",
            success: function (data) {
                console.log(data);
                window.location.href = "/texas";
            }
        });
    }, time);
}());
