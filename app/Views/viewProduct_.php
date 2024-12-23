<?php $this->extend('base') ?>


<?php $this->section('css') ?>
<style>
    .product-image {
        width: 80%;
        height: auto;
    }

    .thumbnail {
        cursor: pointer;
        margin: 5px;
        width: 100px;
    }

    .details {
        padding: 20px;
    }



    #mainImage {
        border-radius: 15px;
    }

    .card-product {
        max-width: 300px;
        margin: 20px;
    }

    .price {
        color: #999;
    }

    .discount-price {
        font-weight: bold;
    }

    .discount-price span {
        font-size: 150%;
    }


    .btn-secondary:hover {
        background-color: rgb(0, 4, 26);
    }

    .btn-circle {
        position: fixed;
        bottom: 20px;
        right: 20px;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: none;
        /* Ocultar por defecto */
        justify-content: center;
        align-items: center;
        font-size: 24px;
    }

    .btn-success {
        background-color: #198754 !important;
        border-color: #198754 !important;
    }

    .card-body {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
        background-color: white !important;
    }

    .screen_div {

        background-color: white;
        width: 400px;
    }

    .card-product a.card_link {
        text-decoration: none;
        color: black;
        font-weight: bold
    }

    .text-success {
        color: rgb(5, 87, 48) !important;
        font-weight: bolder;
    }

    .text-warning {
        color: rgb(218, 123, 16) !important;

    }
</style>
<?php $this->endSection() ?>
<?php $this->section('content') ?>


<div class="container mt-5">
    <div class="row" id="ProductDiv">
        <div class="col-md-6">
            <img id="mainImage" src="/image?img=<?php echo $product['image'] ?>" alt="<?php echo $product['name'] ?>"
                class="img-fluid product-image">
            <div class="text-center">

                <button class="btn btn-outline-primary mt-5" onclick="down()"><i class="fas fa-arrow-down"></i>
                    Descargar</button>
            </div>
            <div class="d-flex justify-content-center mt-3">
                <img src="/image?img=<?php echo $product['image'] ?>" class="thumbnail"
                    alt="<?php echo $product['name'] ?>" onclick="changeImage(this)">

                <?php
                if (count($images) > 0) {
                    for ($i = 0; $i < count($images); $i++) { ?>
                        <img src="/img/extra/<?= $images[$i]['image'] ?>" class="thumbnail" alt="<?= $images[$i]['image'] ?>"
                            onclick="changeImage(this)">

                    <?php }
                }
                ?>

            </div>
        </div>
        <div class="col-md-6 details">
            <h2 class="card-title"><?= $product['name'] ?></h2>
            <p class="card-text"> SKU: <?= $product['sku'] ?></p>
            <p class="sugerido">Detalle: $ US <?= $product['price'] ?></p>

            <?php if (session('client') !== null and session('client') !== ""): ?>
                <p class="discount-price">Mayoreo: $ US <span><?= $product['major'] ?></span></p>

                <p class="card-text"> <?= $product['description'] ?></p>
                <?= $product['cant'] == 0 ? '<p class="text-danger">AGOTADO</p>' : ($product['cant'] > 3 ? '<p class="text-success">En Stock</p>' : '<p class="text-warning">Últimas Existencias Disponibles</p>') ?>


                <?php $text = "Hola, por favor reserveme este producto: \nSKU: " . $product['sku'] . " N/C";

                ?>
                <a href="https://wa.me/503<?= session('user')['seller_number'] ?>?text=<?= $text ?>"
                    class="btn btn-outline-success mt-1">
                    <i class="fab fa-whatsapp fa-lg fa-fw"></i>Solicitar
                </a>
            <?php else: ?>

                <p class="discount-price"><a href="/login" class="btn btn-outline-secondary"> Ingrese para ver precio
                        Mayoreo </a></p>

                <p class="card-text"> <?= $product['description'] ?></p>
                <?= $product['cant'] == 0 ? '<p class="text-danger">AGOTADO</p>' : ($product['cant'] > 3 ? '<p class="text-success">En Stock</p>' : '<p class="text-warning">Últimas Existencias Disponibles</p>') ?>


                <?php $text = "Hola, por favor reserveme este producto: \nSKU: " . $product['sku'] . " N/C";

                ?>
                <a href="https://wa.me/50374891187?text=<?= $text ?>" class="btn btn-outline-success mt-1">
                    <i class="fab fa-whatsapp fa-lg fa-fw"></i>Solicitar
                </a>

            <?php endif; ?>


            <button class="btn btn-outline-secondary mt-1" onclick="captura()">
                <i class="fas fa-crop fa-lg fa-fw "></i>Captura
            </button>


            <button class="btn btn-outline-primary mt-1 " onclick="javascript:history.back()"><i
                    class="fas fa-arrow-left"></i>
                Atrás</button>
        </div>


    </div>
</div>

<?php $this->endSection() ?>
<?php $this->section('scripts') ?>
<script src="/js/html2canvas.min.js"></script>
<script>
    const mainImage = document.getElementById('mainImage');
    function changeImage(element) {
        if (mainImage.src != element.src) {
            mainImage.src = element.src;
            mainImage.alt = element.alt;
        }
    }


    function down() {
        const link = document.createElement('a');
        link.href = mainImage.src;
        link.download = mainImage.alt + '.jpg' ?? '<?php echo "Imagen " . ".jpg" ?>';
        link.click();
    }

    const captura = () => {
        html2canvas(document.querySelector("#ProductDiv")).then(function (canvas) {
            const imgData = canvas.toDataURL('image/png');
            const link = document.createElement('a');
            link.href = imgData;
            link.download = '<?php echo $product['name'] ?>' + '.png';
            link.click();
        });
    }


</script>
<?php $this->endSection() ?>