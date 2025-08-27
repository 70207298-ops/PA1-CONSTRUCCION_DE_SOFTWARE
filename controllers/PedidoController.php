<?php
// controllers/PedidoController.php
require_once __DIR__ . '/../models/Pedido.php';

class PedidoController {
  private Pedido $model;
  public function __construct() { $this->model = new Pedido(); }

  public function index() {
    $data = $this->model->listar();
    include __DIR__ . '/../views/pedido/index.php';
  }

  public function registrar() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $cab = [
        'id_cliente'      => (int)$_POST['id_cliente'],
        'id_local_salida' => (int)$_POST['id_local_salida'],
        'canal_venta'     => $_POST['canal_venta'],
        'observacion'     => $_POST['observacion'] ?? null,
        'total_bruto'     => (float)$_POST['total_bruto'],
        'total_descuento' => (float)$_POST['total_descuento'],
        'total_neto'      => (float)$_POST['total_neto'],
      ];
      $items = json_decode($_POST['items_json'] ?? '[]', true) ?: [];
      $id = $this->model->crear($cab, $items);
      header("Location: index.php?c=pedido&a=ver&id=$id");
      exit;
    } else {
      $clientes  = $this->model->listarClientes();
      $locales   = $this->model->listarLocales();
      $productos = $this->model->listarProductos();
      include __DIR__ . '/../views/pedido/registrar.php';
    }
  }

  public function ver() {
    // En esta primera versión reusamos index, o aquí podrías cargar cabecera + detalle
    $this->index();
  }
}
