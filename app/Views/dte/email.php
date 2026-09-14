<!DOCTYPE html>
<html lang="ES-MX">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura Electronica</title>
</head>
<body
    style="font-family:Inter,sans-serif;margin:0;padding:16px;background-color:#f3f4f6;display:flex;justify-content:center;align-items:center;min-height:100vh">
    <div
        style="max-width:768px;width:100%;background-color:#fff;border-radius:8px;box-shadow:0 10px 15px -3px rgba(0,0,0,.1),0 4px 6px -2px rgba(0,0,0,.05);overflow:hidden">
        <div style="padding:24px;background-color:#f9fafb;color:#1f2937;line-height:1.625;font-size:16px">
            <h1 style="text-align: center; font-weight: bolder;">Riveras Group</h1>
            <h3 style="text-align: center;">
                DOCUMENTO TRIBUTARIO ELECTRONICO
            </h3>
            <p style="margin-bottom: 16px">
                Estimado/a <?= $client_name ?>,
            </p>
            <p style="margin-bottom: 16px">
                Por este Medio enviamos su DTE:
            </p>
            <ul style="list-style-type: disc;
            list-style-position: inside;
            margin-bottom: 24px;
            padding-left: 16px;
            line-height: 1.5">
                <li style="margin-bottom:8px"><strong style=" color: #02091a">Fecha de Emision:</strong> <?= $date ?>
                </li>
                <li style="margin-bottom:8px"><strong style=" color: #02091a">Código de Generación:</strong>
                    <?= $code ?></li>
            </ul>
            <p style="margin-bottom: 16px">
                Si tienes alguna pregunta o necesitas asistencia adicional, no dudes en contactar a nuestro equipo de
                soporte. Estamos aquí para ayudarte.
            </p>
            
        </div>
        <div style="padding:40px; background-color: #1f2937;
            padding: 24px;
            color: #d1d5db;
            font-size: 12px;
            text-align: center;
            border-bottom-left-radius: 8px;
            border-bottom-right-radius: 8px">
            <p style="color:#fff">
                <strong style="color:white;">Nota de Confidencialidad:</strong> Este mensaje y sus anexos son
                confidenciales y pueden contener información privilegiada. Si usted no es el destinatario previsto, por
                favor, notifique al remitente y elimine este correo. La distribución, copia o uso de esta información
                está estrictamente prohibida.
            </p>
            
            <p style="color:#9ca3af">&copy; <?= date('Y') ?> Riveras Group.</p>
        </div>
    </div>
</body>
</html>