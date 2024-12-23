<!DOCTYPE html>
<html lang="es-MX">

<head>
      <title><?php echo $title ?></title>
      <base href="<?= base_url(); ?>" />
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="manifest" href="manifest.json">
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
      <link rel="stylesheet" href="/css/toastr.css">
      <style>
            .divider:after,
            .divider:before {
                  content: "";
                  flex: 1;
                  height: 1px;
                  background: #eee;
            }

            .h-custom {
                  height: calc(100% - 73px);
            }

            @media (max-width: 450px) {
                  .h-custom {
                        height: 100%;
                  }
            }
      </style>

</head>


<body class="main">
      <ul class="notifications"></ul>
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

                  </div>

            </div>
      </nav>

      <section class="vh-100">
            <div class="container-fluid h-custom">
                  <div class="row d-flex justify-content-center align-items-center h-100">
                        <div class="col-md-9 col-lg-6 col-xl-5">
                              <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-login-form/draw2.webp"
                                    class="img-fluid" alt="Sample image">
                        </div>
                        <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
                              <form id="login-form">
                                    <?= csrf_field(); ?>
                                    <div data-mdb-input-init class="form-outline mb-4">
                                          <input type="text" id="document" class="form-control form-control-lg"
                                                placeholder="xxxxxxxx-x" name="document">
                                          <label class="form-label" for="document">Numero de DUI</label>
                                    </div>

                                    <div data-mdb-input-init class="form-outline mb-3">
                                          <input type="password" id="pin" class="form-control form-control-lg"
                                                placeholder="****" name="pin">
                                          <label class="form-label" for="pin">PIN</label>
                                    </div>

                              </form>
                              <div class="text-center text-lg-start mt-4 pt-2">
                                    <button onclick="a()" class="btn btn-primary" style="background-color: #212934">
                                          Iniciar
                                    </button>
                              </div>
                        </div>
                  </div>
            </div>
            <div class="d-flex flex-column flex-md-row text-center text-md-start justify-content-between py-4 px-4 px-xl-5"
                  style="background-color: #212934">
                  <!-- Copyright -->
                  <div class="text-white mb-3 mb-md-0">
                        Riveras Group © <?php echo date("Y"); ?>. All rights reserved.
                  </div>

                  <div>
                        <a href="https://www.facebook.com/share/15fpPoUcC9/" target="_blank" class="text-white me-4">
                              <i class="fab fa-facebook-f fa-lg fa-fw"></i>
                        </a>
                        <a class="text-white me-4" target="_blank" href="https://www.instagram.com/riverasgroupsv">
                              <i class="fab fa-instagram fa-lg fa-fw"></i>
                        </a>
                        <a class="text-white me-4" target="_blank" href="https://www.tiktok.com/@riverasgroupmayoreo">
                              <i class="fab fa-tiktok fa-lg fa-fw"></i>
                        </a>
                        <a class="text-white" target="_blank" href="https://wa.link/5ioe1s">
                              <i class="fab fa-whatsapp fa-lg fa-fw"></i>
                        </a>
                  </div>
            </div>
      </section>


</body>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
      integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
      crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
      integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
      crossorigin="anonymous"></script>
<script src="/js/main.js"></script>
<h1><?php echo session('client') ?></h1>
<script>

      const a = async () => {
            let inputs = document.getElementById('login-form').querySelectorAll('input');
            const data = new FormData();
            inputs.forEach(input => data.append(input.name, input.value));

            let response = await fetch('login', {
                  method: 'POST',
                  body: data
            });
            response = await response.json();

            if (response.status == "success") {
                  window.location.href = "/vip"
            } else {
                  errorMessage(response.message);
            }
      }

</script>

</html>