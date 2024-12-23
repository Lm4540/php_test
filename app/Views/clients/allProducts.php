<?php $this->extend('base') ?>
<?php $this->section('css') ?>
<style>
      .product-card {
            border: 1px solid #ddd;
            border-radius: 5px;
            text-align: center;
            padding: 10px;
            margin: 10px;
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
      #filterDiv {
            display: none;
      }

      /* Mostrar los filtros en pantallas más grandes */
      @media (min-width: 768px) {
            #filterDiv {
                  display: flex!important;
            }

            #toggleFilterBtn {
                  display: none;
            }
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
</style>
<?php $this->endSection() ?>
<?php $this->section('content') ?>



<div class="container">
      <h1 class="my-4">Catálogo de Productos</h1>
      <button id="toggleFilterBtn" class="btn btn-primary mb-3">Mostrar Filtros</button>
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
      <div id="productGrid" class="row gx-5">

            <div id="endOfList" class="text-center my-4" style="display: none;">
                  <p>Fin de la lista</p>
            </div>
      </div>

      <button id="scrollTopBtn" class="btn btn-primary btn-circle">↑</button>


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
                        const productCard = document.createElement("div");
                        productCard.className = "col-12 col-sm-6 col-md-4 ";
                        productCard.innerHTML = `<div class="product-card">
                  <img src="/image?img=${product.image}" alt="Producto 1" class="product-image" loading="lazy">
                <h4>${product.name} </h4>
                <p>SKU: # ${product.sku}</p>
                <p><a href="/login" class="btn btn-secondary"> Ingresa para ver precios</a></p>
                </div>
            </div>
            `;
                        productGrid.appendChild(productCard);
                        displayedProducts.push(productCard);
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

            toggleFilterBtn.addEventListener('click', () => {
                  if (filterDiv.style.display === 'none' || filterDiv.style.display === '') {
                        filterDiv.style.display = 'flex';
                        toggleFilterBtn.textContent = 'Ocultar Filtros';
                  } else {
                        filterDiv.style.display = 'none';
                        toggleFilterBtn.textContent = 'Mostrar Filtros';
                  }
            });

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
</script>


<?php $this->endSection() ?>