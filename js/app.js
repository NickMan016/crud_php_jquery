
$(read(""));

$("#search").on('keyup', function () {
    if($(this).val() !== ""){
        var query = $(this).val();
    }else{
        var query = "";
    }
    
    read(query);
});

function read(query) {
    $.ajax({
        url: 'php/server.php?query=read',
        type: 'post',
        data: { filter: query }
    })
    .done(function (res) {
        $(".response_table").html(res);
    })
    .fail(function () {
        alert('Hubo un error al conectar con el servidor');
    });
}

$("#send").on('click', function (e) {
    e.preventDefault();
    var form = $("#formulario").serialize();
    
    $.ajax({
        url: 'php/server.php?query=create',
        type: 'post',
        data: form
    })
    .done(function (res) {
        $("#formulario")[0].reset();
        $(".response").html(res);
        setTimeout(function () {
            $(".res").fadeOut(2000);
        }, 4000)
    })
    .fail(function () {
        alert('Hubo un error al conectar con el servidor');
    });
});

function delet(code) {
    $.ajax({
        url: 'php/server.php?query=delete',
        type: 'post',
        data: { code: code }
    })
    .done(function (res) {
        $(".response").html(res);
        setTimeout(function () {
            $(".res").fadeOut(2000);
        }, 4000);
        read("");
    })
    .fail(function () {
        alert('Hubo un error al conectar con el servidor');
    });
}