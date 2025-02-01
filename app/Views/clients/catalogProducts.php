<?php $this->extend('base') ?>
<?php $this->section('css') ?>
<style>
      .card-product {
            max-width: 300px;
            margin: 20px;
      }

      .price {
            text-decoration: line-through;
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
            color: #198754 !important;
      }

      .text-warning {
            color: rgb(218, 123, 16) !important;

      }
</style>
<?php $this->endSection() ?>
<?php $this->section('content') ?>

<div id="screen_div" class="row screen_div">
      <div class="row">
            <div class="col-12" id="capture_div"></div>
      </div>

</div>

<div class="container">
      <h1 class="my-4">Catálogo: <?= $name ?></h1>
      <div id="filterDiv" class="row mb-4">
            <div class="col-12 form-group">
                  <label for="searchInput" class="form-label">Buscar Producto</label>
                  <input type="text" class="form-control" id="searchInput" placeholder="Buscar...">
            </div>
      </div>
      <div id="productGrid" class="row gx-5">

      </div>
      <div id="endOfList" class="text-center my-4" style="display: none;">
            <p>Fin de la lista</p>
      </div>

      <button id="scrollTopBtn" class="btn btn-primary btn-circle"><i class="fas fa-arrow-up"></i></button>




</div>


<?php $this->endSection() ?>

<?php $this->section('scripts') ?>
<script src="/js/html2canvas.min.js"></script>
<script>
      const filterDiv = document.getElementById('filterDiv');
      const productGrid = document.getElementById("productGrid");
      const endOfList = document.getElementById("endOfList");
      const searchInput = document.getElementById("searchInput");

      let observer;
      let products = [];
      let displayedProducts = [];
      const productsPerPage = 10;
      let currentIndex = 0;
      var catalog = null;
      var sellerName = '',
            sellerNumber = "";
      var screen_div = document.querySelector("#screen_div");

      const captura = (id, name) => {


            screen_div.querySelector("#capture_div").innerHTML = document.getElementById('div_product_' + id).outerHTML;

            html2canvas(screen_div).then(function (canvas) {
                  const imgData = canvas.toDataURL('image/png');
                  const link = document.createElement('a');
                  link.href = imgData;
                  link.download = name + '.png';
                  link.click();


            });
            screen_div.querySelector("#capture_div").innerHTML = '';

      }

      document.addEventListener("DOMContentLoaded", () => {

            // Mostrar el botón cuando se hace scroll hacia abajo
            window.addEventListener('scroll', () => {
                  const scrollTopBtn = document.getElementById('scrollTopBtn');
                  if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                        scrollTopBtn.style.display = 'flex';
                  } else {
                        scrollTopBtn.style.display = 'none';
                  }
            });

            // Función para hacer scroll hacia arriba cuando se hace clic en el botón
            document.getElementById('scrollTopBtn').addEventListener('click', () => {
                  window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                  });
            });


            const fetchProducts = async () => {
                  try {
                        var response = await fetch('/vip/catalogs/<?= $catalog ?>/products'); // Cambia esta URL por la URL real de tu API
                        response = await response.json();
                        if (response.status == "success") {
                              products = response.data.details;
                              catalog = response.data.catalog
                              sellerName = response.sellerName;
                              sellerNumber = response.sellerNumber;

                        }
                        displayProducts(currentIndex, currentIndex + productsPerPage);
                  } catch (error) {
                        console.error('Error fetching products:', error);
                  }
            };

            const displayProducts = (startIndex, endIndex) => {
                  const filteredProducts = applyFilters();
                  const productsToDisplay = filteredProducts.slice(startIndex, endIndex);

                  // Append new products to the existing ones
                  productsToDisplay.forEach(product => {
                        if (product.cant > 0) {
                              const productCard = document.createElement("div");
                              productCard.className = "col-12 col-xs-6 col-md-6 col-lg-4 ";
                              let ct = `<div class="card card-product" id="div_product_${product.product}"> 
                                    <img src="/img/products/${product.image}" onerror="error_img(this, '/image?img=${product.image}')" alt="${product.name}" class="card-img-top" loading="lazy">
                                    <div class="card-body">
                                    
                                          <h5 class="card-title">${product.name}</h5>
                                          <p class="card-text">SKU: ${product.sku}</p>`;
                              ct += `  <p class="sugerido">Detalle $${product.sugerido}</p> `;

                              if (product.price == product.discount_price) {
                                    ct += `<p class="discount-price">Promoción $ US <span>${product.discount_price}</span></p>`;
                              } else {
                                    ct += `<p class="price">Mayoreo $ US ${product.price}</p>
                  <p class="discount-price">Promoción $ US <span>${product.discount_price}</span></p> `;
                              }
                              if (product.cant == 0) {
                                    ct += `<p class="text-danger">AGOTADO</p>`;
                              } else if (product.cant > 3) {
                                    ct += `<p class="text-success">En Stock</p>`;

                              } else {

                                    ct += `<p class="text-warning">Últimas Existencias Disponibles</p>`;
                              }
                              let text = encodeURI(`Hola, por favor reserveme este producto: \nSKU: ${product.sku} \n Catalogo: ${catalog.name}`);
                              ct += `<a href="https://wa.me/503${sellerNumber}?text=${text}" class="btn btn-outline-success mt-1">
                                          <i class="fab fa-whatsapp fa-lg fa-fw"></i>Solicitar
                                    </a>
                                    <button class="btn btn-outline-secondary mt-1" onclick="captura(${product.product}, '${product.sku}_${catalog.name}')">
                                          <i class="fas fa-crop fa-lg fa-fw "></i>Captura
                                    </button>
                                    <a href="/image?img=${product.image}" download="${product.sku}.jpg" class="btn btn-outline-primary mt-1"> 
                                         <i class="fas fa-download fa-lg fa-fw "></i> Descargar Imagen
                                    </a>
                              </div>
                        </div>`;

                              productCard.innerHTML = ct;
                              productGrid.appendChild(productCard);
                              displayedProducts.push(productCard);
                        }
                  });

                  if (displayedProducts.length < filteredProducts.length) {
                        const lastProduct = displayedProducts[displayedProducts.length - 1];
                        if (lastProduct) {
                              setObserver(lastProduct);
                        }
                  } else {
                        observer.disconnect();
                        endOfList.style.display = "block";
                  }
            };

            const setObserver = (target) => {
                  if (observer) observer.disconnect();

                  observer = new IntersectionObserver(entries => {
                        if (entries[0].isIntersecting) {
                              currentIndex += productsPerPage;
                              displayProducts(currentIndex, currentIndex + productsPerPage);
                        }
                  });

                  if (target) {
                        observer.observe(target);
                  }
            };

            const applyFilters = () => {
                  const searchQuery = searchInput.value.toLowerCase();
                  return products.filter(product => {
                        return (searchQuery.length < 3 || product.name.toLowerCase().includes(searchQuery) || product.sku.toLowerCase().includes(searchQuery));

                  });
            };

            searchInput.addEventListener("input", () => {
                  currentIndex = 0;
                  endOfList.style.display = "none";
                  productGrid.innerHTML = ""; // Clear existing products
                  displayedProducts = []; // Clear existing product references
                  displayProducts(currentIndex, currentIndex + productsPerPage);
            });

            fetchProducts();
      });
</script>


<?php $this->endSection() ?>