<?php $this->extend('base') ?>
<?php $this->section('css') ?>
<style>
      .discount-price,a.card_link{font-weight:700}.product-card{border:1px solid #ddd;border-radius:10px;text-align:center;padding:10px;margin:10px 0}.product-image{width:100%;height:auto;display:block;margin-bottom:10px;border-radius:10px}#productGrid .row .col{padding-left:5px;padding-right:5px}.btn-secondary:hover{background-color:#00041a}.btn-circle{position:fixed;bottom:20px;right:20px;width:50px;height:50px;border-radius:50%;display:none;justify-content:center;align-items:center;font-size:24px}.discount-price span{font-size:150%}a.card_link{text-decoration:none;color:#000}#imageModal .modal-content{background:0 0!important}#imageModal.modal.fade.show{background-color:rgba(0,0,0,.85)}@media (max-width:576px){#imageModal .modal-dialog{margin:10px}#imageModal .btn-close{width:1.5em;height:1.5em}}
</style>
<?php $this->endSection() ?>
<?php $this->section('content') ?>
<div class="container">
      <h1 class="my-4">Catálogo de Productos</h1>
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
            <div class="col-md-4 form-group">
                  <label for="orderFilter" class="form-label">Ordenar por</label>
                  <select class="form-select" id="orderFilter">
                        <option value="random">Aleatorio</option>
                        <option value="newest">Lo Más nuevo</option>
                        <option value="oldest">Por defecto</option>
                  </select>
            </div>
      </div>
      <div id="loadingSpinner" class="text-center my-4" style="display: none;">
            <div class="spinner-border text-primary" role="status"></div>
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

<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-transparent border-0">
                  <div class="modal-body p-0 position-relative">
                        <button type="button"
                              class="btn-close btn-close-white position-absolute top-0 end-0 m-3 shadow-lg"
                              data-bs-dismiss="modal" aria-label="Close"
                              style="z-index: 1051; filter: drop-shadow(0px 0px 5px rgba(0,0,0,0.8));">
                        </button>

                        <img src="" id="fullsizeImage" class="img-fluid rounded"
                              style="max-height: 90vh; width: auto; display: block; margin: 0 auto; cursor: pointer;"
                              data-bs-dismiss="modal">
                  </div>
            </div>
      </div>
</div>

<?php $this->endSection() ?>

<?php $this->section('scripts') ?>
<script>

      const filterDiv = document.getElementById('filterDiv');
      const productGrid = document.getElementById("productGrid");
      const endOfList = document.getElementById("endOfList");
      const categoryFilter = document.getElementById("categoryFilter");
      const searchInput = document.getElementById("searchInput");
      const orderFilter = document.getElementById("orderFilter");
      const loadingSpinner = document.getElementById("loadingSpinner");

      let observer;
      let products = [];
      let displayedProducts = [];
      const productsPerPage = 10;
      let currentIndex = 0;

      const imageModalEl = document.getElementById('imageModal');
      const imageModal = new bootstrap.Modal(imageModalEl);
      const fullsizeImage = document.getElementById('fullsizeImage');

      const openImage = (src) => {
            fullsizeImage.src = src;
            imageModal.show();
      };

      const getLevenshteinDistance = (a, b) => {
            const matrix = Array.from({ length: b.length + 1 }, (_, i) => [i]);
            matrix[0] = Array.from({ length: a.length + 1 }, (_, i) => i);
            for (let i = 1; i <= b.length; i++) {
                  for (let j = 1; j <= a.length; j++) {
                        matrix[i][j] = b[i - 1] === a[j - 1]
                              ? matrix[i - 1][j - 1]
                              : Math.min(matrix[i - 1][j - 1], matrix[i][j - 1], matrix[i - 1][j]) + 1;
                  }
            }
            return matrix[b.length][a.length];
      };

      const fetchProducts = async () => {
            try {
                  const response = await fetch('/catalog/products');
                  let data = await response.json();
                  products = data.status == "success" ? data.data : [];
                  displayProducts(currentIndex, currentIndex + productsPerPage);
            } catch (error) {
                  console.error('Error fetching products:', error);
            }
      };

      const displayProducts = (startIndex, endIndex) => {
            const filteredProducts = applyFilters();
            const productsToDisplay = filteredProducts.slice(startIndex, endIndex);

            productsToDisplay.forEach(product => {
                  if (product.price > 0) {
                        const productCard = document.createElement("div");
                        productCard.className = "col-6 col-sm-6 col-md-4 ";

                        let text = encodeURI(`Hola, vi en su Web este producto: \nSKU: ${product.sku} y quisiera más información`);

                        productCard.innerHTML = `<div class="product-card">
                    <img src="/img/products/${product.image}" onclick="openImage(this.src)" onerror="error_img(this, '/image?img=${product.image}')" alt="${product.name}" class="product-image" loading="lazy">
                    <a href="/product/${product.product}" class="card_link">
                        <h5 class="card-title">${product.name}</h5></a>
                    <p>SKU: # ${product.sku}</p>
                    <p class="discount-price">Detalle $ US <span>${product.price}</span></p>
                    <p><button class="btn btn-outline-secondary mt-1" onclick="show_d(${product.product})">Ver Disponibilidad</button></p>
                    <p><a href="/login" class="btn btn-outline-secondary"> Ver Precio Mayoreo </a></p>
                    <a href="https://wa.me/50375693419?text=${text}" class="btn btn-outline-success mt-1">
                                          <i class="fab fa-whatsapp fa-lg fa-fw"></i>Solicitar
                                    </a>
                    </div>
                </div>`;
                        productGrid.appendChild(productCard);
                        displayedProducts.push(productCard);
                  }
            });

            if (displayedProducts.length < filteredProducts.length) {
                  const lastProduct = displayedProducts[displayedProducts.length - 1];
                  if (lastProduct) setObserver(lastProduct);
            } else {
                  if (observer) observer.disconnect();
                  if (filteredProducts.length > 0) endOfList.style.display = "block";
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
            if (target) observer.observe(target);
      };

      const normalizeText = (text) => {
            return text.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
      };

      const applyFilters = () => {
            const selectedCategory = `${categoryFilter.value}`;
            const rawSearch = searchInput.value;
            const searchQuery = normalizeText(rawSearch).trim();
            const orderValue = orderFilter ? orderFilter.value : 'newest';
            const stopWords = ["de", "con", "para", "un", "una", "el", "la", "color", "y", "-", "+"];

            let filtered = products.filter(product => {
                  const matchesCategory = (selectedCategory === "all" || product.classification == selectedCategory);
                  if (searchQuery.length < 3) return matchesCategory;

                  const productName = normalizeText(product.name);
                  const sku = normalizeText(product.sku);
                  if (sku === searchQuery || sku.includes(searchQuery)) return matchesCategory && true;
                  const searchTokens = searchQuery.split(" ").filter(word => !stopWords.includes(word));
                  const productWords = productName.split(" ");

                  const isMatch = searchTokens.every(sToken => {
                        if (productName.includes(sToken)) return true;
                        return productWords.some(pWord => {
                              if (Math.abs(pWord.length - sToken.length) > 2) return false;
                              const dist = getLevenshteinDistance(sToken, pWord);
                              const threshold = sToken.length > 5 ? 2 : 1;
                              return dist <= threshold;
                        });
                  });

                  return matchesCategory && isMatch;
            });

            let sorted = [...filtered];
            if (orderValue === "oldest") {
                  sorted.sort((a, b) => a.product - b.product);
            } else if (orderValue === "random") {
                  for (let i = sorted.length - 1; i > 0; i--) {
                        const j = Math.floor(Math.random() * (i + 1));
                        [sorted[i], sorted[j]] = [sorted[j], sorted[i]];
                  }
            } else {
                  sorted.sort((a, b) => b.product - a.product);
            }

            return sorted;
      };

      const updateURL = () => {
            const params = new URLSearchParams();

            if (searchInput.value.trim() !== "") params.set('q', searchInput.value.trim());
            if (categoryFilter.value !== "all") params.set('cat', categoryFilter.value);
            if (orderFilter && orderFilter.value !== "newest") params.set('ord', orderFilter.value);

            const newRelativePathQuery = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
            history.replaceState(null, '', newRelativePathQuery);
      };

      const loadFiltersFromURL = () => {
            const params = new URLSearchParams(window.location.search);

            if (params.has('q')) searchInput.value = params.get('q');
            if (params.has('cat')) categoryFilter.value = params.get('cat');
            if (params.has('ord') && orderFilter) orderFilter.value = params.get('ord');
      };

      let debounceTimer;
      const refreshGrid = () => {
            clearTimeout(debounceTimer);
            updateURL();
            productGrid.style.opacity = "0.3";
            if (loadingSpinner) loadingSpinner.style.display = "block";
            debounceTimer = setTimeout(() => {
                  currentIndex = 0;
                  endOfList.style.display = "none";
                  productGrid.innerHTML = "";
                  displayedProducts = [];

                  displayProducts(currentIndex, currentIndex + productsPerPage);

                  productGrid.style.opacity = "1";
                  if (loadingSpinner) loadingSpinner.style.display = "none";
            }, 250);
      };

      const init = async () => {
            loadFiltersFromURL();
            await fetchProducts();
      };


      document.addEventListener("DOMContentLoaded", () => {
            imageModalEl.addEventListener('hidden.bs.modal', () => {
                  fullsizeImage.src = '';
            });

            window.addEventListener('scroll', () => {
                  const scrollTopBtn = document.getElementById('scrollTopBtn');
                  if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                        scrollTopBtn.style.display = 'flex';
                  } else {
                        scrollTopBtn.style.display = 'none';
                  }
            });

            document.getElementById('scrollTopBtn').addEventListener('click', () => {
                  window.scrollTo({ top: 0, behavior: 'smooth' });
            });


            categoryFilter.addEventListener("change", refreshGrid);
            searchInput.addEventListener("input", refreshGrid);
            if (orderFilter) orderFilter.addEventListener("change", refreshGrid);
            init();
      });


      ModalDisponibilidad = new bootstrap.Modal(document.getElementById('ver_disponibilidad'), {
            keyboard: true
      });
      const show_d = async id => {
            try {
                  var response = await fetch(`/data_product/${id}`, {
                        method: 'GET', mode: 'cors', cache: 'no-cache',
                        credentials: 'same-origin', headers: { 'Content-Type': 'application/json' }, redirect: 'follow', referrerPolicy: 'no-referrer'
                  });
                  response = await response.json();
                  if (response.status == "success") {
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