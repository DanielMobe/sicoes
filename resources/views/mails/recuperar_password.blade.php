<!doctype html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">
        <title>Recuperar Contraseña</title>
    </head>
    <body>
        <p>Hola {{ $recuperarPassInfo->name }} haz solicitado la recuperación de tu contraseña para el sitio: </p>
        <p><a href="{{ $recuperarPassInfo->sicoesUrl }}">{{ $recuperarPassInfo->sicoesUrl }}</a></p>
        <p>Da click al siguiente enlace y sigue las instrucciones para restablecerla:</p>
        <p><a href="{{ $recuperarPassInfo->recuUrl }}">Click Aqui</a></p>
        <p>Si no fuiste tu quien solicito la recuperación de contraseña contacta a Control Escolar.</p>
        <p>***Cuenta no monitoreada favor de no responder a este correo***</p>
    </body>
</html>