<?php $this->extend('base') ?>
<?php $this->section('css') ?>
<style>
      .card-product {
            max-width: 450px;
            margin-top: 20px;
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
            color: #198754 !important;
      }

      .text-warning {
            color: rgb(218, 123, 16) !important;

      }

      .badge {
            cursor: pointer;
            font-size: small !important;
      }

      a.badge {
            text-decoration: none;
      }

      .bg-success {
            background-color: #198754 !important;
      }



      @media print {

            #productGrid {
                  width: 100%;
                  margin: 0;
                  font-size: small !important;
            }

            .navbar,
            #filterDiv,
            #footer,
            .product_buttons,
            #toggleFilterBtn,
            #scrollTopBtn,
            #category_title {
                  display: none !important;
            }

            .card {
                  page-break-inside: avoid;
            }

      }
</style>
<?php $this->endSection() ?>
<?php $this->section('content') ?>

<div id="screen_div" class="row screen_div">
      <div class="row">
            <div class="col-12" id="capture_div"></div>
      </div>

</div>

<div class="container-fluid">
      <h1 class="my-4" id="category_title"><?php echo $name ?></h1>
      <span class="badge bg-success mt-4 mb-4" onclick="window.print()"><i class="fas fa-print"></i> Imprimir</span>



      <div id="filterDiv" class="row mb-4">
            <?php if (count($categories) > 1) { ?>
                  <div class="col-md-4 form-group">
                        <label for="categoryFilter" class="form-label">Categoría</label>
                        <select class="form-select" id="categoryFilter" disabled>
                              <option value="all">Todas</option>
                              <?php

                              foreach ($categories as $categorie) { ?>
                                    <option value="<?= $categorie['id'] ?>"><?= $categorie['name'] ?></option>
                              <?php } ?>

                        </select>
                  </div>

                  <div class="col-md-4 form-group">
                        <label for="searchInput" class="form-label">Buscar Producto</label>
                        <input type="text" class="form-control" id="searchInput" placeholder="Buscar..." disabled>
                  </div>

            <?php }
            else { ?>
                  <div class="col-md-12 form-group">
                        <label for="searchInput" class="form-label">Buscar Producto</label>
                        <input type="text" class="form-control" id="searchInput" placeholder="Buscar...">
                        <input type="hidden" id="categoryFilter" value="<?= $lock_categorie ?>">
                  </div>
            <?php } ?>
      </div>
      <div id="productGrid" class="row align-items-stretch">

      </div>
      <div id="endOfList" class="text-center my-4" style="display: none;">
            <p>Fin de la lista</p>
      </div>

      <button id="scrollTopBtn" class="btn btn-primary btn-circle">↑</button>


</div>

<div class="modal fade" id="ver_disponibilidad" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header">
                        <h1 class="modal-title fs-5" id="_modal_title">Modal title</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                        <table class="table table-sm table-hover">
                              <thead>
                                    <tr>
                                          <th>Sucrsal</th>
                                          <th>Existencias</th>
                                    </tr>
                              </thead>
                              <tbody id="existencias"></tbody>
                        </table>
                  </div>
                  <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                  </div>
            </div>
      </div>
</div>
<?php $this->endSection() ?>

<?php $this->section('scripts') ?>
<script src="/js/html2canvas.min.js"></script>
<script>
      const toggleFilterBtn = document.getElementById('toggleFilterBtn');
      const filterDiv = document.getElementById('filterDiv');
      const productGrid = document.getElementById("productGrid");
      const endOfList = document.getElementById("endOfList");
      const categoryFilter = document.getElementById("categoryFilter");
      const searchInput = document.getElementById("searchInput");
      const maxPrice = document.getElementById("maxPrice");

      let observer;
      let products = [];
      let displayedProducts = [];
      const productsPerPage = 10;
      let currentIndex = 0;
      var catalog = null;
      var sellerName = '',
            sellerNumber = "";
      var the_products_are_ready = false;
      var lastProduct = null;
      var screen_div = document.querySelector("#screen_div");

      const captura = (id, name) => {
            console.log(id, name)

            screen_div.querySelector("#capture_div").innerHTML = document.getElementById('div_product_' + id).outerHTML;

            html2canvas(screen_div).then(function (canvas) {
                  const imgData = canvas.toDataURL('image/png');
                  const link = document.createElement('a');
                  link.href = imgData;
                  link.download = name + '.png';
                  link.click();
                  canvas = null;


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
                  products = [];


                  try {
                        var response = await fetch('<?= $lock_categorie !== null ? "/vip/products?cat=" . $lock_categorie : "/vip/products" ?>', {
                              method: 'GET', // *GET, POST, PUT, DELETE, etc.
                              mode: 'cors', // no-cors, *cors, same-origin
                              cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
                              credentials: 'same-origin', // include, *same-origin, omit
                              headers: {
                                    'Content-Type': 'application/json'
                                    // 'Content-Type': 'application/x-www-form-urlencoded',
                              },
                              redirect: 'follow', // manual, *follow, error
                              referrerPolicy: 'no-referrer', // no-referrer, *no-referrer-when-downgrade, origin, origin-when-cross-origin, same-origin, strict-origin, strict-origin-when-cross-origin, unsafe-url
                        }); // Cambia esta URL por la URL real de tu API
                        response = await response.json();
                        if (response.status == "success") {
                              sellerName = response.sellerName;
                              sellerNumber = response.sellerNumber;
                              products = response.data;
                              the_products_are_ready = true;
                              categoryFilter.disabled = false;
                              searchInput.disabled = false;
                              displayProducts(currentIndex, currentIndex + productsPerPage);

                        } else {
                              console.log(response);
                              return errorMessage("La página esta saturada en este momento " + response.status);
                        }
                  } catch (error) {
                        return errorMessage("La página esta saturada en este momento, por favor inténtalo nuevamente en 5 minutos " + error);
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
                              productCard.className = "col col-md-6 col-lg-4 d-flex ";
                              let ct = `<div class="card card-product flex-fill" id="div_product_${product.id}">
                              <img src="/img/products/${product.image}" onerror="error_img(this, '/image?img=${product.image}')" alt="${product.name}" class="card-img-top" loading="lazy">
            
            <div class="card-body">
            <a href="/vip/product/${product.id}" class="card_link"> 
                  <h5 class="card-title">${product.name}</h5>
            </a>
                  <p class="card-text">SKU: ${product.sku}</p>`;


                              ct += `<p class="sugerido">Detalle: $ US ${product.price}</p>
            <p class="discount-price">Mayoreo: $ US <span>${product.major}</span></p> `;


                              if (product.cant == 0) {
                                    ct += `<p class="text-danger">AGOTADO</p>`;
                              } else if (product.cant > 3) {
                                    ct += `<p class="text-success">En Stock</p>
                                    <p><button class="btn btn-outline-danger mt-1" onclick="show_d(${product.id})">Ver Disponibilidad</button></p>`;

                              } else {

                                    ct += `<p class="text-warning">Últimas Existencias Disponibles</p>
                                    <p><button class="btn btn-outline-danger mt-1" onclick="show_d(${product.id})">Ver Disponibilidad</button></p>`;
                              }



                              let text = encodeURI(`Hola, por favor reserveme este producto: \nSKU: ${product.sku} no catalogo`);
                              ct += `<div class="product_buttons">
                              <a href="https://wa.me/503${sellerNumber}?text=${text}" class="btn btn-outline-success mt-1">
                                          <i class="fab fa-whatsapp fa-lg fa-fw"></i>Solicitar
                                    </a>
                                    <button class="btn btn-outline-secondary mt-1" onclick="captura(${product.id}, '${product.sku}')">
                                          <i class="fas fa-crop fa-lg fa-fw "></i>Captura
                                    </button>
                                    <a href="/image?img=${product.image}" download="${product.sku}.jpg" class="btn btn-outline-primary mt-1"> 
                                         <i class="fas fa-download fa-lg fa-fw "></i> Descargar Imagen
                                    </a>
                  </div>                        
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
                  const selectedCategory = `${categoryFilter.value}`;
                  const searchQuery = searchInput.value.toLowerCase();

                  // console.log(selectedCategory)

                  return products.filter(product => {
                        const matchesCategory = (selectedCategory === "all" || product.classification == selectedCategory);

                        const matchesSearch = (searchQuery.length < 3 || product.name.toLowerCase().includes(searchQuery) || product.sku.toLowerCase().includes(searchQuery));

                        return matchesCategory && matchesSearch;
                  });
            };



            categoryFilter.addEventListener("change", () => {
                  if (the_products_are_ready) {
                        currentIndex = 0;
                        endOfList.style.display = "none";
                        productGrid.innerHTML = ""; // Clear existing products
                        displayedProducts = []; // Clear existing product references
                        displayProducts(currentIndex, currentIndex + productsPerPage);
                  }
            });

            searchInput.addEventListener("input", () => {
                  if (the_products_are_ready) {
                        currentIndex = 0;
                        endOfList.style.display = "none";
                        productGrid.innerHTML = ""; // Clear existing products
                        displayedProducts = []; // Clear existing product references
                        displayProducts(currentIndex, currentIndex + productsPerPage);
                  }

            });

            fetchProducts();
      });

      ModalDisponibilidad = new bootstrap.Modal(document.getElementById('ver_disponibilidad'), {
            keyboard: true
      });
      const show_d = async id => {
            try {
                  var response = await fetch(`/data_product/${id}`, {
                        method: 'GET', // *GET, POST, PUT, DELETE, etc.
                        mode: 'cors', // no-cors, *cors, same-origin
                        cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
                        credentials: 'same-origin', // include, *same-origin, omit
                        headers: {
                              'Content-Type': 'application/json'
                              // 'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        redirect: 'follow', // manual, *follow, error
                        referrerPolicy: 'no-referrer', // no-referrer, *no-referrer-when-downgrade, origin, origin-when-cross-origin, same-origin, strict-origin, strict-origin-when-cross-origin, unsafe-url
                  }); // Cambia esta URL por la URL real de tu API
                  response = await response.json();
                  if (response.status == "success") {
                        //establecerf titulo
                        let product = response.data;
                        document.querySelector("#_modal_title").innerHTML = product.name;

                        let ctn = ``;
                        product.stocks.forEach(stock => {
                              ctn += `<tr><td>${stock.name}</td><td>${stock.cant}</td></tr>`
                        });
                        document.querySelector("#existencias").innerHTML = ctn;
                        ModalDisponibilidad.toggle();

                  } else {
                        console.log(response);
                        return errorMessage("La página esta saturada en este momento " + response.status);
                  }
            } catch (error) {
                  return errorMessage("La página esta saturada en este momento, por favor inténtalo nuevamente en 5 minutos " + error);
                  console.error('Error fetching products:', error);
            }
      }
</script>


<?php $this->endSection() ?>