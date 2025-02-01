<!DOCTYPE html>
<html lang="es-MX">

<head>
      <title><?php echo $title ?></title>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="manifest" href="/manifest.json">
      <link rel="apple-touch-icon" href="/img/apple-icon.png">
      <link rel="shortcut icon" type="image/x-icon" href="/img/favicon.ico">
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
            rel="stylesheet">
      <link rel="stylesheet" href="/css/fontawesome.min.css">
      <link rel="stylesheet" href="/css/main.css">
      <?php echo $this->renderSection('css') ?>
</head>


<body class="main">
      <!-- Header -->
      <nav class="navbar navbar-expand-lg bg-dark shadow">
            <div class="container-fluid d-flex justify-content-between align-items-center">

                  <a class="navbar-brand text-success logo h1 align-self-center" href="/">
                        <img src="/img/logo.png" alt="logo" class="img-fluid logo-image">
                  </a>

                  <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
                        data-bs-target="#main_nav" aria-controls="navbarSupportedContent" aria-expanded="false"
                        aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                  </button>

                  <div class="align-self-center collapse navbar-collapse flex-fill  d-lg-flex justify-content-lg-between"
                        id="main_nav">
                        <div class="flex-fill">
                              <ul class="nav navbar-nav d-flex justify-content-between mx-lg-auto">
                                    <li class="nav-item">
                                          <a class="nav-link" href="/">Inicio</a>
                                    </li>
                                    <li class="nav-item">
                                          <a class="nav-link" href="/about">Sobre Nosotros</a>
                                    </li>
                                    <li class="nav-item">
                                          <a class="nav-link" href="/catalog">Catálogo</a>
                                    </li>
                                    <li class="nav-item">
                                          <a class="nav-link" href="/contact">Contacto</a>
                                    </li>
                                    <li class="nav-item">
                                          <a class="nav-link" href="/sucursal">Sucursales</a>
                                    </li>
                              </ul>
                        </div>
                        <div class="navbar align-self-center d-flex">
                              <?php if (session('client') !== null and session('client') !== ""): ?>
                                    <a class="nav-link" href="/logout">Cerrar Sesión</a>
                              <?php else: ?>
                                    <a class="nav-link" href="/login">Iniciar Sesión</a>
                              <?php endif; ?>
                        </div>
                  </div>

            </div>
      </nav>
      <?php echo $this->renderSection('content') ?>
      <?php echo $this->include('footer') ?>
</body>

<?php echo $this->renderSection('scripts') ?>



</html>