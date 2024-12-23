<?php $this->extend('base') ?>
<?php $this->section('css') ?>
<style>
      .image-container {
            overflow: hidden;
            text-align: center;
            border-radius: 15px;
            cursor: pointer;
      }

      .image-container img {
            transition: transform 0.3s ease;
            border-radius: 15px;
            max-width: 350px;
      }

      .image-container img:hover {
            transform: scale(1.1);
            border-radius: 15px;
      }
</style>
<?php $this->endSection() ?>
<?php $this->section('content') ?>
<div class="mt-5 mb-5">

</div>
<div class="container  justify-content-center">
      <div class="row text-center">
            <div class="col-12">
                  <h2>Elije una opción </h2>
            </div>
      </div>
      <div class="row g-3">
            
            <a href="/vip/categories" class="col-sm-12 col-md-4 image-container mt-5">
                  <img src="/img/categories.jpg" alt="Imagen 3" class="img-fluid">
            </a>
            <a href="/vip/product" class="col-md-4 image-container mt-5">
                  <img src="/img/products.jpg" alt="Imagen 2" class="img-fluid">
            </a>
            <a href="/vip/catalogs" class="col-md-4 image-container mt-5">
                  <img src="/img/catalogs.jpg" alt="Imagen 1" class="img-fluid">
            </a>
      </div>
</div>

<div class="mt-5 mb-5">

</div>

<?php $this->endSection() ?>
<?php $this->section('scripts') ?>
<?php $this->endSection() ?>