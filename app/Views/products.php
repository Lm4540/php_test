<?php $this->extend('base') ?>
<?php $this->section('css') ?>
<style>
      .product-card {
            border: 1px solid #ddd;
            border-radius: 5px;
            text-align: center;
            padding: 10px;
            margin-top: 10px;
            margin-bottom: 10px;
            margin-left: 0;
            margin-right: 0;
            border-radius: 10px;
      }

      .product-image {
            width: 100%;
            height: auto;
            display: block;
            margin-bottom: 10px;
            border-radius: 10px;
      }

      /* Ocultar los filtros por defecto en móviles */
      #productGrid .row .col {
            padding-left: 5px;
            padding-right: 5px;
      }

      /* Mostrar los filtros en pantallas más grandes */


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

      .discount-price {
            font-weight: bold;
      }

      .discount-price span {
            font-size: 150%;
      }

      a.card_link {
            text-decoration: none;
            color: black;
            font-weight: bold
      }
</style>
<?php $this->endSection() ?>
<?php $this->section('content') ?>



<div class="container">
      <h1 class="my-4">Catálogo de Productos</h1>
      <!-- <button id="toggleFilterBtn" class="btn btn-primary mb-3">Mostrar Filtros</button> -->
      <div id="filterDiv" class="row mb-4">
            <div class="col-md-4 form-group">
                  <label for="categoryFilter" class="form-label">Categoría</label>
                  <select class="form-select" id="categoryFilter">
                        <option value="all">Todas</option>
                        <?php

                        foreach ($categories as $categorie) { ?>
                              <option value="<?= $categorie['id'] ?>"><?= $categorie['name'] ?></option>
                        <?php } ?>

                  </select>
            </div>

            <div class="col-md-4 form-group">
                  <label for="searchInput" class="form-label">Buscar Producto</label>
                  <input type="text" class="form-control" id="searchInput" placeholder="Buscar...">
            </div>
      </div>
      <div id="productGrid" class="row">

            <div id="endOfList" class="text-center my-4" style="display: none;">
                  <p>Fin de la lista</p>
            </div>
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
<script>
      document.addEventListener("DOMContentLoaded", () => {
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
            var lastProduct = null;

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
                        const response = await fetch('/catalog/products'); // Cambia esta URL por la URL real de tu API
                        products = await response.json();
                        products = products.status == "success" ? products.data : [];
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

                        if (product.price > 0) {

                              const productCard = document.createElement("div");
                              productCard.className = "col-6 col-sm-6 col-md-4 ";
                              productCard.innerHTML = `<div class="product-card">
                              <img src="/img/products/${product.image}" onerror="error_img(this, '/image?img=${product.image}')" alt="${product.name}" class="product-image" loading="lazy">
                              <a href="/product/${product.product}" class="card_link"> 
                                          <h5 class="card-title">${product.name}</h5></a>
                              <p>SKU: # ${product.sku}</p>
                              <p class="discount-price">Detalle $ US <span>${product.price}</span></p>
                              <p><button class="btn btn-outline-secondary mt-1" onclick="show_d(${product.product})">Ver Disponibilidad</button></p>
                              <p><a href="/login" class="btn btn-outline-secondary"> Ver Precio Mayoreo </a></p>
                              </div>
                              </div>
                              `;
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

                  console.log(selectedCategory)

                  return products.filter(product => {
                        const matchesCategory = (selectedCategory === "all" || product.classification == selectedCategory);

                        const matchesSearch = (searchQuery.length < 3 || product.name.toLowerCase().includes(searchQuery) || product.sku.toLowerCase().includes(searchQuery));

                        return matchesCategory && matchesSearch;
                  });
            };



            categoryFilter.addEventListener("change", () => {
                  currentIndex = 0;
                  endOfList.style.display = "none";
                  productGrid.innerHTML = ""; // Clear existing products
                  displayedProducts = []; // Clear existing product references
                  displayProducts(currentIndex, currentIndex + productsPerPage);
            });

            searchInput.addEventListener("input", () => {
                  currentIndex = 0;
                  endOfList.style.display = "none";
                  productGrid.innerHTML = ""; // Clear existing products
                  displayedProducts = []; // Clear existing product references
                  displayProducts(currentIndex, currentIndex + productsPerPage);
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