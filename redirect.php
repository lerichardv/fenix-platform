<?php
/**
 * Este archivo tiene el único propósito de enviar el 
 * access_token a la payrollapp y redirigir hacia el mismo.
 * Esto es necesario para poder persistir la sesión iniciada 
 * en lmf php app hasta la otra app.
 * 
 * @author lerichard <ric.valladares@bluelionsoft.com>
 * @date 2024-09-17
 */
require_once __DIR__ . '/libs/funciones/func_env.php';

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
if (!isset($_SESSION['cod_usuario'])) {
  header('Location: index.php');
  exit;
}

$redirectUrl = env('PAYROLL_APP_URL', 'http://laravel.bayer-payroll.test/public/access_token');
if (strpos($redirectUrl, 'access_token') === false) {
  $redirectUrl = rtrim($redirectUrl, '/') . '/access_token';
}
?>
<html>
  <body>
    <form method="POST" id="form" action="<?= htmlspecialchars($redirectUrl, ENT_QUOTES, 'UTF-8'); ?>">
      <input type="hidden" name="php_access_token" value="<?= htmlspecialchars($_SESSION['php_access_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
    </form>
    <script>
      document.getElementById('form').submit();
    </script>
  </body>
</html>
