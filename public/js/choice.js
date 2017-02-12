(function () {
    var check = document.querySelectorAll("[type='checkbox']");
    if(check[0] != null) {
        var sum1 = check[0].parentNode.childNodes[5].innerText;
        var sum2 = check[1].parentNode.childNodes[5].innerText;

        for (var j = 0; j < check.length; j++) {
            check[j].onclick = function () {
                var sum = 0;
                if (this.parentNode.childNodes[5] == null) {
                    sum = 0;
                }
                else {
                    sum = Number(this.parentNode.childNodes[5].innerText);
                }
                if (this.id == 'raise') {
                    sum = Number(sum1) + Number(sum2);
                }
                var answer = document.querySelector('#answer');
                answer.value = sum;
                for (var i = 0; i < check.length; i++) {
                    if (check[i] == this) {
                        check[i].checked = 'checked';
                        check[i].setAttribute('checked', 'checked');
                    }
                    else {
                        check[i].checked = '';
                        check[i].setAttribute('checked', 'false');
                    }
                }
            }
        }
    }


    var conn = new WebSocket("ws://localhost:8080");

    conn.onmessage = function (e) {
        window.location.href = "/texas";
    }

    $('#button').on('click', function () {
        if(open < 4) {
            $.ajax({
                type: "POST",
                url: "/choice",
                data: $("#choice").serialize(),
                success: function (data) {
                    window.location.href = "/texas";
                }
            });
        }

        setTimeout(function () {
            conn.send('hello');
        }, 100);

    });

    $('form').on("submit", function (e) {
        e.preventDefault();
    });
}());