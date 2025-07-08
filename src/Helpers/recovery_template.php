<?php
// App/Views/Emails/recuperacao_template.php

$token = $token ?? 'XXXXXX'; // Token padrão para segurança

ob_start();
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Recuperação de Senha</title>
    <style>
      body {
        font-family: Arial, sans-serif;
        background-color: #f7f7f7;
        color: #333;
        padding: 20px;
      }
      .container {
        background-color: #ffffff;
        padding: 20px;
        border-radius: 8px;
        max-width: 400px;
        margin: auto;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
      }
      .token {
        font-size: 20px;
        font-weight: bold;
        color: #007BFF;
        margin-top: 10px;
        margin-bottom: 20px;
      }
    </style>
  </head>
  <body>
    <div class="container">
      <h2>Recuperação de Senha</h2>
      <p>Você solicitou a recuperação de sua senha.</p>
      <p>Use o seguinte token para redefinir sua senha:</p>
      <div class="token"><?= htmlspecialchars($token) ?></div>
      <p>Se você não solicitou isso, ignore este e-mail.</p>
    </div>
  </body>
</html>

<?php
$html = ob_get_clean();
