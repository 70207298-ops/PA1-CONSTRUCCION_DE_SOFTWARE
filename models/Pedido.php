<?php
// models/Pedido.php
require_once __DIR__ . '/../config/db.php';

class Pedido {
  private PDO $db;
  public function __construct() { $this->db = DB::conn(); }

  // Listado general de pedidos
  public function listar(): array {
    $sql = "SELECT p.id_pedido, p.fecha_pedido, p.canal_venta, p.estado,
                   p.total_neto,
                   COALESCE(
                    IF(c.tipo='NATURAL', CONCAT(c.nombres,' ',c.apellidos), c.razon_social),
                    '—'
                   ) AS cliente,
                   l.nombre AS local_salida
            FROM pedido p
            JOIN cliente c ON c.id_cliente = p.id_cliente
            JOIN local   l ON l.id_local   = p.id_local_salida
            ORDER BY p.id_pedido DESC";
    return $this->db->query($sql)->fetchAll();
  }

  // Para el formulario: clientes activos
  public function listarClientes(): array {
    $sql = "SELECT id_cliente,
                   IF(tipo='NATURAL', CONCAT(nombres,' ',apellidos), razon_social) AS nombre
            FROM cliente
            WHERE activo = 1
            ORDER BY nombre";
    return $this->db->query($sql)->fetchAll();
  }

  // Para el formulario: locales activos
  public function listarLocales(): array {
    $sql = "SELECT id_local, nombre FROM local WHERE activo = 1 ORDER BY nombre";
    return $this->db->query($sql)->fetchAll();
  }

  // Para el formulario: catálogo de productos activos
  public function listarProductos(): array {
    $sql = "SELECT id_producto, sku, nombre, precio_mayorista
            FROM producto
            WHERE activo = 1
            ORDER BY nombre";
    return $this->db->query($sql)->fetchAll();
  }

  // Registrar pedido + detalle (transacción)
  public function crear(array $cab, array $items): int {
    $this->db->beginTransaction();
    try {
      $stmt = $this->db->prepare(
        "INSERT INTO pedido (id_cliente, id_local_salida, canal_venta, estado, observacion,
                             total_bruto, total_descuento, total_neto)
         VALUES (:id_cliente, :id_local, :canal, 'REGISTRADO', :obs,
                 :tbruto, :tdesc, :tneto)"
      );
      $stmt->execute([
        ':id_cliente' => $cab['id_cliente'],
        ':id_local'   => $cab['id_local_salida'],
        ':canal'      => $cab['canal_venta'],
        ':obs'        => $cab['observacion'] ?? null,
        ':tbruto'     => $cab['total_bruto'],
        ':tdesc'      => $cab['total_descuento'],
        ':tneto'      => $cab['total_neto'],
      ]);
      $id_pedido = (int)$this->db->lastInsertId();

      $det = $this->db->prepare(
        "INSERT INTO pedido_detalle (id_pedido, id_producto, cantidad, precio_unit, descuento, subtotal)
         VALUES (:id_pedido, :id_producto, :cantidad, :precio_unit, :descuento, :subtotal)"
      );

      foreach ($items as $it) {
        $det->execute([
          ':id_pedido'   => $id_pedido,
          ':id_producto' => $it['id_producto'],
          ':cantidad'    => $it['cantidad'],
          ':precio_unit' => $it['precio_unit'],
          ':descuento'   => $it['descuento'] ?? 0,
          ':subtotal'    => $it['subtotal'],
        ]);
      }

      // (Siguiente iteración: actualizar stock_local y registrar en kardex)
      $this->db->commit();
      return $id_pedido;
    } catch (Throwable $e) {
      $this->db->rollBack();
      throw $e;
    }
  }
}
