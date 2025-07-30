<!DOCTYPE html>
<html lang="pt_br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Recuperar senha</title>
</head>

<body>
    <p>Olá {{ $user->name }},</p>

    <p>Você solicitou a redefinição de sua senha.</p>

    <p><strong>Código:</strong> {{ $code }}</p>

    <p>Este código expira em {{ $formattedDate }} às {{ $formattedTime }}.</p>

    <p>Se você não solicitou isso, ignore este e-mail.</p>

    <p>Atenciosamente,<br>Sua equipe</p>
</body>

</html>
