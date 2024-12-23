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
      <div class="row g-3">
            <?php foreach ($categories as $cate) { ?>
                  <div class="col-md-4 mt-5">
                        <div class="card image-container" onclick="cate(<?= $cate['id'] ?>)">
                              <img src="/image?img=<?= $cate['image'] ?>" alt="categorie_image" class="img-fluid">
                              <div class="card-body">
                                    <h3 class="text-center"><?= $cate['name'] ?></h3>

                              </div>
                        </div>
                  </div>
                  
                  <?php } ?>

      </div>
</div>
<div class="mt-5"></div>


<?php $this->endSection() ?>
<?php $this->section('scripts') ?>
<script>
      const cate = id => {
            window.location.href ="/vip/categories/"+id
      }
</script>
<?php $this->endSection() ?>