<?php /* views/layout/header.php */ ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Pet Happy Store</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.php">PetHappy</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mainNav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Pedidos</a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="index.php?c=pedido&a=index">Listado</a></li>
            <li><a class="dropdown-item" href="index.php?c=pedido&a=registrar">Nuevo pedido</a></li>
          </ul>
        </li>
        <li class="nav-item"><a class="nav-link" href="index.php?c=producto&a=index">Productos</a></li>
        <li class="nav-item"><a class="nav-link" href="index.php?c=cliente&a=index">Clientes</a></li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Almacén</a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="index.php?c=stock&a=index">Stock por local</a></li>
            <li><a class="dropdown-item" href="index.php?c=kardex&a=index">Kardex</a></li>
            <li><a class="dropdown-item" href="index.php?c=transferencia&a=index">Transferencias</a></li>
          </ul>
        </li>
        <li class="nav-item"><a class="nav-link" href="index.php?c=reportes&a=index">Reportes</a></li>
      </ul>
      <span class="navbar-text small">MVC + MySQL</span>
    </div>
  </div>
</nav>
<div class="container py-4">
