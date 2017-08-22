var conn = new WebSocket("ws://localhost:8080");


conn.onmessage = function (e) {
    data = JSON.parse(e.data);
    $("#screen").css("display", "block");
};




function sendInvitation() {
    var data = {};
    data['user'] = $("#user").attr('data-user');
    var checkbox = $('#select input[type="checkbox"]');
    for(var i = 0; i < checkbox.length; i++) {
        if($(checkbox[i]).prop('checked') == true) {
            data[i] = $(checkbox[i]).attr('data-name');
        }
    }
    data = JSON.stringify(data);
    conn.send(data);
}


function sendAdmission() {

}

function sendRefusal() {
    
}