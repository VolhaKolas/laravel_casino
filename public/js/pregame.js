$(document).ready(function() {
    var data = "<?= $table_id ?>";

    setInterval(function () {
        $.ajax({
            type: "GET",
            url: "/before",
            data: {table: data},
            success: function (data) {
                //data[0] - time before game, data[1] - count of users
                $('.timer var').html(data[0]);
                if (data[0] <= 0 & data[1] >= 2 || data[1] == 8) {
                    window.location.href = "/texas";
                }
                else if (data[0] <= 0 & data[1] == 1) {
                    window.location.href = "/userpage";
                }
            }
        });
        return false;
    }, 1000);

});
