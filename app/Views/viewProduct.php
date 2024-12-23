<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vista de Producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .product-image {
            width: 100%;
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
        .price {
            color: #d9534f;
            font-size: 24px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <img id="mainImage" src="/img/banner_img_01.jpg" alt="Imagen Principal" class="img-fluid product-image">
                <div class="text-center">

                      <button class="btn btn-outline-primary" onclick="down()"><i class="fas fa-arrow-down"></i> Descargar</button>
                </div>
                <div class="d-flex justify-content-center mt-3">
                    <img src="/img/banner_img_01.jpg" class="thumbnail" alt="Imagen Principal" onclick="changeImage(this)">
                    <img src="/img/banner_img_02.jpg" class="thumbnail" alt="Image 2" onclick="changeImage(this)">
                    <img src="/img/banner_img_03.jpg" class="thumbnail" alt="Thumbnail 3" onclick="changeImage(this)">

                    <img src="/img/banner_img_01.jpg" class="thumbnail" alt="Imagen Principal" onclick="changeImage(this)">
                    <img src="/img/banner_img_02.jpg" class="thumbnail" alt="Image 2" onclick="changeImage(this)">
                    <img src="/img/banner_img_03.jpg" class="thumbnail" alt="Thumbnail 3" onclick="changeImage(this)">

                    <img src="/img/banner_img_01.jpg" class="thumbnail" alt="Imagen Principal" onclick="changeImage(this)">
                    <img src="/img/banner_img_02.jpg" class="thumbnail" alt="Image 2" onclick="changeImage(this)">
                    <img src="/img/banner_img_03.jpg" class="thumbnail" alt="Thumbnail 3" onclick="changeImage(this)">
                </div>
            </div>
            <div class="col-md-6 details">
                <h2>Nombre del Producto</h2>
                <p>SKU: 1234567890</p>
                <p class="price">$80.00 <span class="text-muted"><del>$100.00</del></span></p>
                <p>Descripción del producto. Este producto es de alta calidad y ofrece características excepcionales. Es perfecto para aquellos que buscan funcionalidad y estilo.</p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
      const mainImage = document.getElementById('mainImage');
        function changeImage(element) {
            if(mainImage.src != element.src){
                  mainImage.src = element.src;
                  mainImage.alt = element.alt;
            }
        }


        function down(){
            const link = document.createElement('a'); 
            link.href = mainImage.src; 
            link.download = mainImage.alt + '.jpg' ?? '<?php echo  "Imagen ".".jpg" ?>';
            link.click();
        }
    </script>
</body>
</html>
