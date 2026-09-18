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
session_start();
if (!isset($_SESSION['cod_usuario'])) {
  header('Location: index.php');
}
?>
<html>
  <body>
    <!-- <form method="POST" id="form" action="http://127.0.0.1:8000/access_token"> -->
    <form method="POST" id="form" action="http://laravel.bayer-payroll.test/public/access_token">
      <input type="hidden" name="php_access_token" value="<?= $_SESSION['php_access_token'];?>">
    </form>
    <script>
      document.getElementById('form').submit();
    </script>
  </body>
</html>
