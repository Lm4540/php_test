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
      <div class="row g-3 text-center">

            <?php if (count($categories) > 0) {
                  foreach ($categories as $cate) { ?>
                        <div class="col-md-4 mt-5">
                              <div class="card image-container" onclick="cate('<?= $cate['id'] ?>?name=<?= $cate['name'] ?>')">
                                    <img src="/image?img=<?= $cate['image'] ?>" alt="categorie_image" class="img-fluid">
                                    <div class="card-body">
                                          <h3 class="text-center"><?= $cate['name'] ?></h3>

                                    </div>
                              </div>
                        </div>

                  <?php }
            }
            else { ?>
                  <div class="col">
                        <h3>Lo sentimos, no hay catálogos que mostrar</h3>
                        <?php if (session('access_to_all_products')) { ?>
                              <a href="/vip">Volver</a>
                        <?php } ?>
                  </div>
            <?php } ?>

      </div>
</div>
<div class="mt-5"></div>


<?php $this->endSection() ?>
<?php $this->section('scripts') ?>
<script>
      const cate = id => window.location.href = '/vip/catalogs/' + id
</script>
<?php $this->endSection() ?>