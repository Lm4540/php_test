<?php $this->extend('base') ?>
<?php $this->section('css') ?>
<style>
  .maps {
    width: 100%;
    height: 300px;
    border-radius: 15px;
  }

  .map_image {
    border-radius: 2em!important;
  }

  .a_ {
    text-decoration: none;
  }
</style>
<?php $this->endSection() ?>


<?php $this->section('content') ?>
<section class="container py-5">
  <div class="row">
    <div class="mx-auto col-md-8 col-lg-6 order-lg-last text-right">
      <img class="img-fluid p-3 map_image" src="/img/maps1.jpg" alt="map image">
    </div>
    <div class="col-lg-6 mb-0 d-flex align-items-center">
      <div class="text-align-left align-self-center">
        <iframe
          src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d5481.853363751909!2d-89.22201556125947!3d13.702958097521472!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8f63312f06d4d391%3A0xe77f97f9dc94fabd!2sRiveras%20Group%20-%20Distribuidora%20Mayorista%20S.S!5e0!3m2!1ses-419!2ssv!4v1733438480549!5m2!1ses-419!2ssv"
          class="maps" style="border:0;" allowfullscreen="" loading="lazy"
          referrerpolicy="no-referrer-when-downgrade"></iframe>

        <h3>Sucursal San Salvador</h3>
        <p><b>Contacto:</b> <a href="https://wa.me/50372966452" class="a_">7296-6452</a></p>
        <p>
          <i class="fas fa-map-marker-alt fa-fw"></i>
          <b>Dirección: </b>
          Av. Rossevelt y 55 Av. Sur, #2827 Edificio Carolina Local 4, San
          Salvador, San Salvador
        </p>
      </div>
    </div>
  </div>
</section>

<section class="bg-light">
  <div class="container py-5">
    <div class="row">
      <div class="mx-auto col-md-8 col-lg-6 text-right">
      <img class="img-fluid p-3 map_image" src="/img/maps2.jpg" alt="map image">
      </div>
      <div class="col-lg-6 mb-0 d-flex align-items-center">
        <div class="text-align-left align-self-center">
          <iframe
            src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d1935.8107562141925!2d-89.56541191032068!3d13.981120071905991!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8f62e92aebafd6a3%3A0x88d0bec914c261d!2sRiveras%20Group%20Santa%20Ana!5e0!3m2!1ses-419!2ssv!4v1733438268413!5m2!1ses-419!2ssv"
            class="maps" style="border:0;" allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>
          <h3>Sucursal Santa Ana</h3>
          <p><b>Contacto:</b> <a href="https://wa.me/50360226249" class="a_">6022-6249</a></p>
          <p>
            <i class="fas fa-map-marker-alt fa-fw"></i>
            <b>Dirección: </b>
            Esquina 1a Avenida Sur y 3a Calle Oriente, Santa Ana Edificio Molina, Santa Ana<br>
            Local #2, Frente a Cruz Roja
          </p>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="container py-5">
  <div class="row">
    <div class="mx-auto col-md-8 col-lg-6 order-lg-last text-right">
    <img class="img-fluid p-3 map_image" src="/img/maps3.jpg" alt="map image">
    </div>
    <div class="col-lg-6 mb-0 d-flex align-items-center">
      <div class="text-align-left align-self-center">
        <iframe
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3259.9249052208506!2d-89.291256525262!3d13.67442449565478!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8f632fcbdaeb9133%3A0xb7884de2abe74d20!2sRiveras%20Group%20Santa%20Tecla!5e0!3m2!1ses-419!2ssv!4v1733438324892!5m2!1ses-419!2ssv"
          class="maps" style="border:0;" allowfullscreen="" loading="lazy"
          referrerpolicy="no-referrer-when-downgrade"></iframe>

        <h3>Sucursal Santa Tecla</h3>
        <p><b>Contacto:</b> <a href="https://wa.me/50375693419" class="a_">7569-3419</a></p>
        <p>
          <i class="fas fa-map-marker-alt fa-fw"></i>
          <b>Dirección: </b>
          Calle Daniel Hernandez, Centro Comercial Daniel Hernández local 13, Santa Tecla
        </p>
      </div>
    </div>
  </div>
</section>
<?php $this->endSection() ?>