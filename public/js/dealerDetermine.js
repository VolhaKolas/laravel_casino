setTimeout(function () {
    $("#dealer").css('display', 'block');
}, 500);

setTimeout(function () {
    $("#smallblind").css('display', 'block');
    $("#bigblind").css('display', 'block');
}, 800);


setTimeout(function () {
    $.ajax({
        type: "GET",
        url: "/cards",
        success: function (data) {
            window.location.href = "/texas";
        }
    });
    return false;
}, 5000 + 20 * userPlace);
