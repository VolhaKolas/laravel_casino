$(document).ready(function() {


    setInterval(function () {
        $.ajax({
            type: "GET",
            url: "/before",
            success: function (data) {
                $('.timer var').empty();
                //data[0] - time before game, data[1] - count of users
                var timeBefore = data[0];
                var users = data[1];
                if (timeBefore <= 0 & users >= 2 || users == 8) {
                    window.location.href = "/texas";
                }
                else if (timeBefore <= 0 & users == 1) {
                    window.location.href = "/userpage";
                }
                else {
                    $('.timer var').html(timeBefore);
                }
            }
        });
        return false;
    }, 1000);

});
