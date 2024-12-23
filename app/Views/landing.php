<?php $this->extend('base') ?>
<?php $this->section('content') ?>


<div id="hero-carousel" class="carousel slide" data-bs-ride="carousel">
  <ol class="carousel-indicators">
    <li data-bs-target="#hero-carousel" data-bs-slide-to="0" class="active"></li>
    <li data-bs-target="#hero-carousel" data-bs-slide-to="1"></li>
    <li data-bs-target="#hero-carousel" data-bs-slide-to="2"></li>
  </ol>
  <div class="carousel-inner">
    <div class="carousel-item active">
      <div class="container">
        <div class="row p-5">
          <div class="mx-auto col-md-8 col-lg-6 order-lg-last">
            <img class="img-fluid" src="/img/banner_img_01.jpg" alt="">
          </div>
          <div class="col-lg-6 mb-0 d-flex align-items-center">
            <div class="text-align-left align-self-center">
              <h1 class="h1">Carteras y Bolsos</h1>
              <h3 class="h2">Variedad para Todos los Gustos</h3>
              <p>
                Explora una gama de colores, tamaños y estilos que se adaptan a todas tus necesidades y preferencias. Ya
                sea que busques un bolso clásico en tonos neutros o una pieza vibrante que destaque, nuestra colección
                tiene algo especial para cada persona.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="carousel-item">
      <div class="container">
        <div class="row p-5">
          <div class="mx-auto col-md-8 col-lg-6 order-lg-last">
            <img class="img-fluid" src="/img/banner_img_02.jpg" alt="">
          </div>
          <div class="col-lg-6 mb-0 d-flex align-items-center">
            <div class="text-align-left">
              <h1 class="h1">Productos para Niños</h1>
              <h3 class="h2">Explora nuestra colección de artículos para niños:</h3>
              <ul>
                <li>
                  Baby Walkers: Ayuda a tu pequeño en sus primeros pasos con seguridad y confianza.
                </li>
                <li>
                  Triciclos y Scooters: Perfectos para aventuras al aire libre, promoviendo la diversión y el ejercicio.
                </li>
                <li>
                  Sábanas y Cobertores para Niños: Diseños adorables y confortables para asegurar un buen descanso.
                </li>
                <li>
                  Mochilas Escolares: Funcionales y con personajes de moda, ideales para acompañar a tus hijos en su día
                  a día escolar.
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="carousel-item">
      <div class="container">
        <div class="row p-5">
          <div class="mx-auto col-md-8 col-lg-6 order-lg-last">
            <img class="img-fluid" src="/img/banner_img_03.jpg" alt="">
          </div>
          <div class="col-lg-6 mb-0 d-flex align-items-center">
            <div class="text-align-left">
              <h1 class="h1">Electrodomésticos y productos para el Hogar</h1>
              <h3 class="h2">Encontrarás una amplia gama de productos para el hogar a precios asequibles</h3>
              <p>
                "Nuestro catálogo de electrodomésticos incluye desde cafeteras, licuadoras, tostadoras hasta freidoras
                de aire.
              </p>
              <p>
                En nuestra sección de productos para el hogar encontrarás cortinas, sábanas, edredones, cobertores para
                sofá, organizadores, depósitos y otra gran variedad de artículos.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <a class="carousel-control-prev text-decoration-none w-auto ps-3" href="#hero-carousel" role="button"
    data-bs-slide="prev">
    <i class="fas fa-chevron-left"></i>
  </a>
  <a class="carousel-control-next text-decoration-none w-auto pe-3" href="#hero-carousel" role="button"
    data-bs-slide="next">
    <i class="fas fa-chevron-right"></i>
  </a>
</div>

<section class="container-fluid py-5 bg-light">
  <div class="row text-center pt-3">
    <div class="col-lg-6 m-auto">
      <h1 class="h1">Categorias del Mes</h1>
      <p>
        Descubre nuestra selección destacada de este mes y encuentra los productos que harán más especial el día a día.
      </p>
    </div>
  </div>
  <div class="row">
    <div class="col-12 col-md-4 p-5 mt-3">
      <a href="#"><img src="/img/category_img_01.jpg" class="rounded-circle img-fluid border"></a>
      <h5 class="text-center mt-3 mb-3">Triciclos</h5>
      <p class="text-center"><a href="/catalog" class="btn btn-primary">Ir al Catálogo</a></p>
    </div>
    <div class="col-12 col-md-4 p-5 mt-3">
      <a href="#"><img src="/img/category_img_02.jpg" class="rounded-circle img-fluid border"></a>
      <h2 class="h5 text-center mt-3 mb-3">Forros para Sofá</h2>
      <p class="text-center"><a href="/catalog" class="btn btn-primary">Ir al Catálogo</a></p>
    </div>
    <div class="col-12 col-md-4 p-5 mt-3">
      <a href="#"><img src="/img/category_img_03.jpg" class="rounded-circle img-fluid border"></a>
      <h2 class="h5 text-center mt-3 mb-3">Smart Watch</h2>
      <p class="text-center"><a href="/catalog" class="btn btn-primary">Ir al Catálogo</a></p>
    </div>
  </div>
</section>


<?php $this->endSection() ?>