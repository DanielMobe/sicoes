$( document ).ready(function() {
    console.log( "ready!" );

    $("#genera_kardex").click(function() {
        $(".KB-container").hide();
        $("#kardex-pid-div").show();
    });

    $("#genera_boleta").click(function() {
        $(".KB-container").hide();
        $("#boleta-pid-div").show();
    });

    $(".atras").click(function() {
        $(".KB-container").show();
        $("#boleta-pid-div").hide();
        $("#kardex-pid-div").hide();
    });

    $("#kardex").submit(function(e){
        e.preventDefault();
        $("#loader").show();
        var matricula_value = $("#kardex-pid").val();
        var SendInfo= { matricula:matricula_value};
        console.log('kardex previene default');
        $.ajax({
            method: "POST",
            url : 'https://controlescolar.tesi.org.mx/api/alumnos/kardex',
            contentType: 'application/json',
            data : JSON.stringify(SendInfo),
            dataType : 'json',
            // Añade un header:
            headers: {'Authorization': 'Bearer kctCEcdeXp7CsFaMGFKDNzquAdJdC7jTGguaodWHbeHwz8mWNDNDMB7lQXZk'},
            // El resto del código
            error: function (json) {
                alert(json.menssage);
                $("#loader").hide();
            },
            success : function(json) {
                console.log(json);
                //var json = JSON.parse(json);

                if (json.codigo==200) {
                    window.location.href = json.download;
                }else{
                    alert(json.menssage);
                }
                $("#loader").hide();
            }
        });
        
    });

    $("#boleta").submit(function(e){
        e.preventDefault();
        $("#loader").show();
        var matricula_value = $("#boleta-pid").val();
        var SendInfo= { matricula:matricula_value};
        console.log('boleta previene default');
        $.ajax({
            method: "POST",
            url : 'https://controlescolar.tesi.org.mx/api/alumnos/boleta',
            contentType: 'application/json',
            data : JSON.stringify(SendInfo),
            dataType : 'json',
            // Añade un header:
            headers: {'Authorization': 'Bearer kctCEcdeXp7CsFaMGFKDNzquAdJdC7jTGguaodWHbeHwz8mWNDNDMB7lQXZk'},
            // El resto del código
            error: function (json) {
                alert(json.menssage);
                $("#loader").hide();
            },
            success : function(json) {
                console.log(json);
                //var json = JSON.parse(json);

                if (json.codigo==200) {
                    window.location.href = json.download;
                }else{
                    alert(json.menssage);
                }
            $("#loader").hide();
            }
        });
    });







});