document.addEventListener('DOMContentLoaded', function () {
    // Add search result brand logo styles
    const style = document.createElement('style');
    style.textContent = `
        .search-result-brand-logo {
            width: 16px;
            height: 16px;
            object-fit: contain;
            border-radius: 3px;
            margin-right: 6px;
            vertical-align: middle;
        }
        
        .search-result-description {
            font-size: 12px;
            color: #666;
            margin-top: 2px;
        }
    `;
    document.head.appendChild(style);
    
    // Main search functionality (with category select)
    setupSearch('main-search-input', 'search-results');

    // Header v1 search functionality (without category select)
    setupSearch('header-v1-search-input', 'header-v1-search-results');

    // Header search functionality (without category select)
    setupSearch('header-search-input', 'search-results');

    // Header v2 search functionality (without category select)
    setupSearch('search-input', 'search-results');

    // Mobile search functionality
    setupSearch('mobile-search-input', 'mobile-search-results');

    // Mobile search functionality
    setupSearch('mobile-search-input-1', 'mobile-search-results-1');

    function setupSearch(inputId, resultsId) {
        const searchInput = document.getElementById(inputId);
        const searchResults = document.getElementById(resultsId);
        let searchTimeout;

        if (!searchInput || !searchResults) {
            return;
        }

        // Add clear icon functionality for both desktop and mobile search
        const clearIconId = inputId === 'mobile-search-input' ? 'mobile-clear-search' : 'desktop-clear-search';
        const clearIcon = document.getElementById(clearIconId);

        if (clearIcon) {
            // Show/hide clear icon based on input content
            searchInput.addEventListener('input', function () {
                if (this.value.length > 0) {
                    clearIcon.classList.add('visible');
                } else {
                    clearIcon.classList.remove('visible');
                }
            });

            // Clear search when icon is clicked
            clearIcon.addEventListener('click', function () {
                searchInput.value = '';
                searchResults.style.display = 'none';
                clearIcon.classList.remove('visible');
                searchInput.focus();
            });
        }

        // Function to format price
        function formatPrice(price) {
            return '৳ ' + parseFloat(price).toFixed(2);
        }

        // Function to create slug from title
        function createSlug(text) {
            return text
                .toLowerCase()
                .replace(/[^\w ]+/g, '')
                .replace(/ +/g, '-');
        }

        // Function to show search results
        function showResults(data) {
            searchResults.innerHTML = '';

            // Check if we have any results
            const hasProducts = data.products && data.products.length > 0;
            const hasCategories = data.categories && data.categories.length > 0;
            const hasSubcategories = data.subcategories && data.subcategories.length > 0;
            const hasPages = data.pages && data.pages.length > 0;

            if (!hasProducts && !hasCategories && !hasSubcategories && !hasPages) {
                searchResults.innerHTML = '<div class="search-no-results">No results found</div>';
                searchResults.style.display = 'block';
                return;
            }

            // Products section
            if (hasProducts) {
                const productsSection = document.createElement('div');
                productsSection.className = 'search-section';

                if (data.products.length > 0) {
                    productsSection.innerHTML = '<h3 class="search-section-title">Products</h3>';

                    data.products.forEach(product => {
                        const slug = createSlug(product.title);
                        const productItem = document.createElement('a');
                        productItem.className = 'search-result-item';
                        productItem.href = `/product/${product.id}/${slug}`;

                        // Calculate price for variable products
                        let price = 0;
                        let oldPrice = 0;
                        
                        if (product.product_type === 'variable' && product.variation_combinations && product.variation_combinations.length > 0) {
                            const offerPrices = [];
                            const regularPrices = [];
                            
                            product.variation_combinations.forEach(combination => {
                                const offerPrice = combination.offer_price || combination.regular_price || combination.price || 0;
                                const regularPrice = combination.regular_price || combination.price || 0;
                                
                                if (offerPrice > 0) {
                                    offerPrices.push(offerPrice);
                                }
                                if (regularPrice > 0) {
                                    regularPrices.push(regularPrice);
                                }
                            });
                            
                            if (offerPrices.length > 0) {
                                const minOfferPrice = Math.min(...offerPrices);
                                const maxOfferPrice = Math.max(...offerPrices);
                                price = minOfferPrice === maxOfferPrice ? minOfferPrice : `${minOfferPrice} - ${maxOfferPrice}`;
                                
                                if (regularPrices.length > 0) {
                                    const minRegularPrice = Math.min(...regularPrices);
                                    const maxRegularPrice = Math.max(...regularPrices);
                                    if (minRegularPrice > minOfferPrice) {
                                        oldPrice = minRegularPrice === maxRegularPrice ? minRegularPrice : `${minRegularPrice} - ${maxRegularPrice}`;
                                    }
                                }
                            }
                        } else {
                            // Simple product or fallback
                            price = product.offer || product.old_price || 0;
                            if (product.old_price && product.old_price > (product.offer || 0)) {
                                oldPrice = product.old_price;
                            }
                        }

                        const priceDisplay = price ? formatPrice(price) : '৳ 0';
                        const oldPriceDisplay = oldPrice ? `<div class="search-result-old-price">${formatPrice(oldPrice)}</div>` : '';

                        productItem.innerHTML = `
                            <img src="/storage/${product.thumb_image}" alt="${product.title}" class="search-result-image">
                            <div class="search-result-info">
                                <div class="search-result-title">${product.title}</div>
                                <div class="search-result-price">${priceDisplay}</div>
                                ${oldPriceDisplay}
                            </div>
                        `;

                        productsSection.appendChild(productItem);
                    });

                    searchResults.appendChild(productsSection);
                }
            }

            // Categories section
            if (hasCategories) {
                const categoriesSection = document.createElement('div');
                categoriesSection.className = 'search-section';

                if (data.categories.length > 0) {
                    categoriesSection.innerHTML = '<h3 class="search-section-title">Categories</h3>';

                    data.categories.forEach(category => {
                        const categoryItem = document.createElement('a');
                        categoryItem.className = 'search-result-item';
                        categoryItem.href = `/shop/${category.slug}`;

                        categoryItem.innerHTML = `
                        <div class="search-result-info">
                            <div class="search-result-title">${category.name}</div>
                        </div>
                    `;

                        categoriesSection.appendChild(categoryItem);
                    });

                    searchResults.appendChild(categoriesSection);
                }
            }

            // Subcategories section
            if (hasSubcategories) {
                const subcategoriesSection = document.createElement('div');
                subcategoriesSection.className = 'search-section';

                if (data.subcategories.length > 0) {
                    subcategoriesSection.innerHTML = '<h3 class="search-section-title">Subcategories</h3>';

                    data.subcategories.forEach(subcategory => {
                        const subcategoryItem = document.createElement('a');
                        subcategoryItem.className = 'search-result-item';
                        subcategoryItem.href = `/shop/${subcategory.product_category.slug}/${subcategory.slug}`;

                        subcategoryItem.innerHTML = `
                        <div class="search-result-info">
                            <div class="search-result-title">${subcategory.name}</div>
                            <div class="search-result-category">in ${subcategory.product_category.name}</div>
                        </div>
                    `;

                        subcategoriesSection.appendChild(subcategoryItem);
                    });

                    searchResults.appendChild(subcategoriesSection);
                }
            }

            // Brands section
            if (data.brands && data.brands.length > 0) {
                const brandsSection = document.createElement('div');
                brandsSection.className = 'search-section';

                brandsSection.innerHTML = '<h3 class="search-section-title">Brands</h3>';

                data.brands.forEach(brand => {
                    const brandItem = document.createElement('a');
                    brandItem.className = 'search-result-item';
                    brandItem.href = `/shop?brand[]=${brand.id}`;

                    brandItem.innerHTML = `
                        <div class="search-result-info">
                            <div class="search-result-title">
                                ${brand.logo ? `<img src="/storage/${brand.logo}" alt="${brand.name}" class="search-result-brand-logo">` : ''}
                                ${brand.name}
                            </div>
                            ${brand.description ? `<div class="search-result-description">${brand.description}</div>` : ''}
                        </div>
                    `;

                    brandsSection.appendChild(brandItem);
                });

                searchResults.appendChild(brandsSection);
            }

            // Pages section
            if (hasPages) {
                const pagesSection = document.createElement('div');
                pagesSection.className = 'search-section';

                if (data.pages.length > 0) {
                    pagesSection.innerHTML = '<h3 class="search-section-title">Pages</h3>';

                    data.pages.forEach(page => {
                        const pageItem = document.createElement('a');
                        pageItem.className = 'search-result-item';
                        pageItem.href = `/${page.slug}`;

                        pageItem.innerHTML = `
                        <div class="search-result-info">
                            <div class="search-result-title">${page.title}</div>
                        </div>
                    `;

                        pagesSection.appendChild(pageItem);
                    });

                    searchResults.appendChild(pagesSection);
                }
            }


            // View all results link
            const viewAllLink = document.createElement('a');
            viewAllLink.className = 'search-all-results';
            
            // Build view all URL with optional category filter
            let viewAllUrl = `/shop?search=${encodeURIComponent(searchInput.value)}`;
            const searchBar = searchInput.closest('.search-bar');
            if (searchBar) {
                const categorySelect = searchBar.querySelector('.category-select');
                if (categorySelect && categorySelect.value) {
                    viewAllUrl += `&category=${encodeURIComponent(categorySelect.value)}`;
                }
            }
            
            viewAllLink.href = viewAllUrl;
            viewAllLink.textContent = 'View all results';
            searchResults.appendChild(viewAllLink);

            searchResults.style.display = 'block';
        }

        // Function to perform search
        function performSearch() {
            const query = searchInput.value.trim();

            if (query.length < 2) {
                searchResults.style.display = 'none';
                return;
            }

            // Build search URL with optional category filter
            let searchUrl = `/search/ajax?query=${encodeURIComponent(query)}`;
            
            // Check if there's a category select in the same search bar
            const searchBar = searchInput.closest('.search-bar');
            if (searchBar) {
                const categorySelect = searchBar.querySelector('.category-select');
                if (categorySelect && categorySelect.value) {
                    searchUrl += `&category_id=${encodeURIComponent(categorySelect.value)}`;
                }
            }

            fetch(searchUrl)
                .then(response => response.json())
                .then(data => {
                    showResults(data);
                })
                .catch(error => {
                    console.error('Search error:', error);
                });
        }

        // Event listener for input changes
        searchInput.addEventListener('input', function () {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(performSearch, 300);
        });

        // Event listener for focus
        searchInput.addEventListener('focus', function () {
            if (searchInput.value.trim().length >= 2) {
                performSearch();
            }
        });

        // Event listener for Enter key
        searchInput.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const query = searchInput.value.trim();
                if (query.length >= 2) {
                    // Navigate to shop page with search query
                    let searchUrl = `/shop?search=${encodeURIComponent(query)}`;
                    
                    // Add category filter if exists
                    const searchBar = searchInput.closest('.search-bar') || searchInput.closest('.search-bar-mobile');
                    if (searchBar) {
                        const categorySelect = searchBar.querySelector('.category-select');
                        if (categorySelect && categorySelect.value) {
                            searchUrl += `&category=${encodeURIComponent(categorySelect.value)}`;
                        }
                    }
                    
                    window.location.href = searchUrl;
                }
            }
        });
        
        // Add category change listener if category select exists
        const searchBar = searchInput.closest('.search-bar') || searchInput.closest('.search-bar-mobile');
        if (searchBar) {
            const categorySelect = searchBar.querySelector('.category-select');
            if (categorySelect) {
                categorySelect.addEventListener('change', function() {
                    // If there's already a search query, perform search with new category
                    if (searchInput.value.trim().length >= 2) {
                        performSearch();
                    }
                });
            }
            
            // Add search button click listener if search button exists
            const searchButton = searchBar.querySelector('.search-btn');
            if (searchButton) {
                searchButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    const query = searchInput.value.trim();
                    if (query.length >= 2) {
                        // Navigate to shop page with search query
                        let searchUrl = `/shop?search=${encodeURIComponent(query)}`;
                        
                        // Add category filter if exists
                        const categorySelect = searchBar.querySelector('.category-select');
                        if (categorySelect && categorySelect.value) {
                            searchUrl += `&category=${encodeURIComponent(categorySelect.value)}`;
                        }
                        
                        window.location.href = searchUrl;
                    }
                });
            }
            
            // Add search icon click listener if search icon exists (for header v1)
            const searchIcon = searchBar.querySelector('.search-icon');
            if (searchIcon) {
                searchIcon.addEventListener('click', function(e) {
                    e.preventDefault();
                    const query = searchInput.value.trim();
                    if (query.length >= 2) {
                        // Navigate to shop page with search query
                        let searchUrl = `/shop?search=${encodeURIComponent(query)}`;
                        
                        // Add category filter if exists
                        const categorySelect = searchBar.querySelector('.category-select');
                        if (categorySelect && categorySelect.value) {
                            searchUrl += `&category=${encodeURIComponent(categorySelect.value)}`;
                        }
                        
                        window.location.href = searchUrl;
                    }
                });
            }
        }

        // Close search results when clicking outside
        document.addEventListener('click', function (event) {
            if (!searchInput.contains(event.target) && !searchResults.contains(event.target)) {
                searchResults.style.display = 'none';
            }
        });
    }
});