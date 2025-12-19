const CONFIG = {
    // Detectar entorno automáticamente
    API_BASE_URL: (() => {
        const hostname = window.location.hostname;
        
        if (hostname === 'localhost' || hostname === '127.0.0.1') {
            return 'http://localhost/student008/shop';
        }
        
        return 'https://remotehost.es/student008/shop';
    })(),
    
    ENDPOINTS: {
        GET_PRODUCTS: '/endpoints/get_products.php',
        GET_PRODUCT: '/endpoints/get_product.php',
        GET_CART: '/endpoints/get_cart.php',
        UPDATE_CART: '/endpoints/update_cart.php',
        ADD_TO_CART: '/backend/cart_insert.php'
    },
    
    PATHS: {
        LOGIN: '/backend/forms/form_login.php'
    }
};

function getApiUrl(endpoint) {
    return CONFIG.API_BASE_URL + endpoint;
}

function getPath(path) {
    return CONFIG.API_BASE_URL + path;
}