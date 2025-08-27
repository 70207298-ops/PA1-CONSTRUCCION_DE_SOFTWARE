<?php
// index.php
$c = $_GET['c'] ?? 'pedido';
$a = $_GET['a'] ?? 'index';

$map = [
  'pedido' => __DIR__ . '/controllers/PedidoController.php',
  // Nota: más adelante podrás añadir ProductoController, ClienteController, etc.
];

if (!isset($map[$c])) { http_response_code(404); exit('Controlador no encontrado'); }
require_once $map[$c];

$cls = ucfirst($c) . 'Controller';
$ctl = new $cls();

if (!method_exists($ctl, $a)) { http_response_code(404); exit('Acción no encontrada'); }
$ctl->$a();
