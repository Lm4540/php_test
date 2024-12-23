<?php $this->extend('base') ?>
<?php $this->section('css') ?>
<style>
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
            color: #198754 !important;
      }
      
      .text-warning {
            color:rgb(218, 123, 16) !important;

      }

      #filterDiv {
            display: none;
      }

      /* Mostrar los filtros en pantallas más grandes */
      @media (min-width: 768px) {
            #filterDiv {
                  display: flex !important;
            }

            #toggleFilterBtn {
                  display: none;
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

<div class="container">
      <h1 class="my-4"><?php echo $name ?></h1>
      <button id="toggleFilterBtn" class="btn btn-primary mb-3">Mostrar Filtros</button>
      <div id="filterDiv" class="row mb-4">
            <?php if (count($categories) > 1) { ?>
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

            <?php }
            else { ?>
                  <div class="col-md-12 form-group">
                        <label for="searchInput" class="form-label">Buscar Producto</label>
                        <input type="text" class="form-control" id="searchInput" placeholder="Buscar...">
                        <input type="hidden" id="categoryFilter" value="<?= $lock_categorie ?>">
                  </div>
            <?php } ?>
      </div>
      <div id="productGrid" class="row gx-5">

      </div>
      <div id="endOfList" class="text-center my-4" style="display: none;">
            <p>Fin de la lista</p>
      </div>

      <button id="scrollTopBtn" class="btn btn-primary btn-circle">↑</button>


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
                  products = [];
                  var response = await fetch('<?= $lock_categorie !== null ? "/vip/products?cat=" . $lock_categorie : "/vip/products" ?>'); // Cambia esta URL por la URL real de tu API
                  response = await response.json();
                  if (response.status == "success") {
                        sellerName = response.sellerName;
                        sellerNumber = response.sellerNumber;
                        products = response.data;

                  }

                  displayProducts(currentIndex, currentIndex + productsPerPage);
                  try {
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
            <img src="/image?img=${product.image}" class="card-img-top"
                  alt="Producto">
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
                                    ct += `<p class="text-success">En Stock</p>`;

                              } else {

                                    ct += `<p class="text-warning">Últimas Existencias Disponibles</p>`;
                              }



                              let text = encodeURI(`Hola, por favor reserveme este producto: \nSKU: ${product.sku} no catalogo`);
                              ct += `<a href="https://wa.me/503${sellerNumber}?text=${text}" class="btn btn-outline-success mt-1">
                                          <i class="fab fa-whatsapp fa-lg fa-fw"></i>Solicitar
                                    </a>
                                    <button class="btn btn-outline-secondary mt-1" onclick="captura(${product.product}, '${product.sku}')">
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