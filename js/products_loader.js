const localizacion = window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1'

const API_BASE = localizacion ? '/student008/shop/backend/endpoints/' : 'https://remotehost.es/student008/shop/backend/endpoints/';

const ProductLoader = {

    



    // Configuración
    apiUrl: `${API_BASE}get_products.php`,
    
    /**
     * Cargar productos desde la API
     */
    async loadProducts(options = {}) {
        const { limit = null, category = null, featured = false } = options;
        
        try {
            // Construir URL con parámetros
            let url = this.apiUrl;
            const params = new URLSearchParams();
            
            if (limit) params.append('limit', limit);
            if (category) params.append('category', category);
            if (featured) params.append('featured', 'true');
            
            if (params.toString()) {
                url += '?' + params.toString();
            }
            
            // Realizar petición
            const response = await fetch(url);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            console.log("Datos recibidos del API:", data);
            if (data.success) {
                return data.products;
            } else {
                throw new Error(data.message || 'Error al cargar productos');
            }
            
        } catch (error) {
            console.error('Error al cargar productos:', error);
            this.showError('No se pudieron cargar los productos. Por favor, intenta más tarde.');
            return [];
        }
    },
    
    /**
     * Renderizar productos en el grid
     */
    renderProducts(products, containerId = 'products-grid') {
        const container = document.getElementById(containerId);
        
        if (!container) {
            console.error(`Contenedor ${containerId} no encontrado`);
            return;
        }
        
        // Limpiar contenedor
        container.innerHTML = '';
        
        if (products.length === 0) {
            container.innerHTML = '<p class="no-products">No hay productos disponibles en este momento.</p>';
            return;
        }
        
        // Crear cards de productos
        products.forEach(product => {
            const productCard = this.createProductCard(product);
            container.appendChild(productCard);
        });
    },
    
    /**
     * Crear card HTML de producto
     */
    createProductCard(product) {
        const article = document.createElement('article');
        article.className = 'product-card';
        
        article.innerHTML = `
            <div class="product-image">
                <img src="${product.image}" alt="${product.name}">
            </div>
            <div class="product-info">
                <h3>${product.name}</h3>
                <p class="product-description">${product.description || 'Producto artesanal de paracord'}</p>
                <div class="product-footer">
                    <span class="price">${product.price.toFixed(2)} €</span>
                    <button class="btn btn-secondary btn-small" onclick="ProductLoader.viewProduct(${product.id})">
                        Ver producto
                    </button>
                </div>
            </div>
        `;
        
        return article;
    },
    
    /**
     * Ver detalle de producto
     */
    viewProduct(productId) {
        // Redirigir a página de detalle
        window.location.href = `/student008/shop/views/product-detail.html?id=${productId}`;
    },
    
    /**
     * Mostrar mensaje de error
     */
    showError(message) {
        const container = document.getElementById('products-grid');
        if (container) {
            container.innerHTML = `
                <div class="error-message">
                    <p>${message}</p>
                </div>
            `;
        }
    },
    
    /**
     * Inicializar cargador de productos
     */
    async init() {
        // Esperar a que el DOM esté listo
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.loadAndRender());
        } else {
            await this.loadAndRender();
        }
    },
    
    /**
     * Cargar y renderizar productos
     */
    async loadAndRender() {
        // Mostrar indicador de carga
        const container = document.getElementById('products-grid');
        if (container) {
            container.innerHTML = '<p class="loading">Cargando productos...</p>';
        }
        
        // Cargar productos (máximo 6 para la página principal)
        const products = await this.loadProducts({ limit: 6 });
        
        // Renderizar productos
        this.renderProducts(products);
    }
};

// Inicializar automáticamente cuando se carga el script
ProductLoader.init();