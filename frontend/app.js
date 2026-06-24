/**
 * API Service
 * Handle all API calls to backend
 */

const API_BASE_URL = 'http://localhost/PetSupply_eCommerce/backend/api';

class APIService {
    static getAuthToken() {
        return localStorage.getItem('token');
    }
    
    static setAuthToken(token) {
        localStorage.setItem('token', token);
    }
    
    static removeAuthToken() {
        localStorage.removeItem('token');
    }
    
    static getCurrentUser() {
        const userStr = localStorage.getItem('user');
        return userStr ? JSON.parse(userStr) : null;
    }
    
    static setCurrentUser(user) {
        localStorage.setItem('user', JSON.stringify(user));
    }
    
    static removeCurrentUser() {
        localStorage.removeItem('user');
    }
    
    static async request(endpoint, method = 'GET', data = null) {
        const options = {
            method: method,
            headers: {
                'Content-Type': 'application/json',
            }
        };
        
        const token = this.getAuthToken();
        if (token) {
            options.headers['Authorization'] = `Bearer ${token}`;
        }
        
        if (data) {
            options.body = JSON.stringify(data);
        }
        
        // Try clean URL first, fallback to query parameter if it fails
        const cleanUrl = `${API_BASE_URL}${endpoint}`;
        const fallbackUrl = `${API_BASE_URL}/index.php?endpoint=${endpoint.substring(1)}`;
        
        const urls = [cleanUrl, fallbackUrl];
        let lastError;
        
        for (const url of urls) {
            try {
                console.log(`[API] ${method} ${url}`);
                const response = await fetch(url, options);
                let result = {};
                
                try {
                    result = await response.json();
                } catch (parseError) {
                    console.warn(`Could not parse response from ${url}:`, parseError);
                    // If this URL failed, try the next one
                    if (url === cleanUrl) {
                        lastError = parseError;
                        continue;
                    }
                    result = { error: 'Invalid response format' };
                }
                
                if (response.status === 404 && url === cleanUrl) {
                    console.log(`[API] Clean URL returned 404, trying fallback...`);
                    lastError = '404 Not Found';
                    continue;
                }
                
                if (!response.ok && response.status !== 200) {
                    console.error(`[API Error] ${response.status}:`, result);
                }
                
                return {
                    status: response.status,
                    data: result
                };
            } catch (error) {
                lastError = error;
                if (url === cleanUrl) {
                    console.log(`[API] Clean URL failed, trying fallback...`, error.message);
                    continue;
                }
                console.error(`[API Error] All URLs failed for ${method} ${endpoint}:`, error);
                return {
                    status: 500,
                    data: { error: 'Network error', details: error.message }
                };
            }
        }
        
        // If we get here, all URLs failed
        console.error(`[API Error] ${method} ${endpoint} - all attempts failed`);
        return {
            status: 500,
            data: { error: 'Network error', details: lastError?.message || 'Unknown error' }
        };
    }
    
    // Auth endpoints
    static async register(username, email, password, full_name = '') {
        return this.request('/auth/register', 'POST', {
            username, email, password, full_name
        });
    }
    
    static async login(email, password) {
        return this.request('/auth/login', 'POST', { email, password });
    }
    
    static async logout() {
        this.removeAuthToken();
        this.removeCurrentUser();
    }
    
    static async getProfile() {
        return this.request('/auth/profile', 'GET');
    }
    
    static async updateProfile(data) {
        return this.request('/auth/profile', 'PUT', data);
    }
    
    static async verifyToken() {
        return this.request('/auth/verify-token', 'POST');
    }
    
    // Products endpoints
    static async getProducts(page = 1, limit = 20, category_id = null) {
        let url = `/products?page=${page}&limit=${limit}`;
        if (category_id) {
            url += `&category_id=${category_id}`;
        }
        return this.request(url, 'GET');
    }
    
    static async getProduct(product_id) {
        return this.request(`/products/${product_id}`, 'GET');
    }
    
    static async searchProducts(search_term) {
        return this.request(`/products?search=${encodeURIComponent(search_term)}`, 'GET');
    }
    
    static async addProduct(data) {
        return this.request('/products', 'POST', data);
    }
    
    static async updateProduct(product_id, data) {
        return this.request(`/products/${product_id}`, 'PUT', data);
    }
    
    static async deleteProduct(product_id) {
        return this.request(`/products/${product_id}`, 'DELETE');
    }
    
    // Categories endpoints
    static async getCategories() {
        return this.request('/categories', 'GET');
    }
    
    static async getCategory(category_id) {
        return this.request(`/categories/${category_id}`, 'GET');
    }
    
    static async addCategory(data) {
        return this.request('/categories', 'POST', data);
    }
    
    static async updateCategory(category_id, data) {
        return this.request(`/categories/${category_id}`, 'PUT', data);
    }
    
    static async deleteCategory(category_id) {
        return this.request(`/categories/${category_id}`, 'DELETE');
    }
    
    // Cart endpoints
    static async getCart() {
        return this.request('/cart', 'GET');
    }
    
    static async addToCart(product_id, quantity = 1) {
        const user = this.getCurrentUser();
        if (user && (user.role === 'admin' || user.user_type === 'admin' || user.is_admin == 1)) {
            return {
                status: 403,
                data: { error: 'Admins have view-only access and cannot purchase or add items to cart.' }
            };
        }
        return this.request('/cart', 'POST', { product_id, quantity });
    }
    
    static async updateCartItem(product_id, quantity) {
        return this.request('/cart/update', 'PUT', { product_id, quantity });
    }
    
    static async removeFromCart(product_id) {
        return this.request(`/cart/${product_id}`, 'DELETE');
    }
    
    static async clearCart() {
        return this.request('/cart/clear', 'DELETE');
    }
    
    // Orders endpoints
    static async getOrders(page = 1, limit = 10) {
        return this.request(`/orders?page=${page}&limit=${limit}`, 'GET');
    }
    
    static async getOrder(order_id) {
        return this.request(`/orders/${order_id}`, 'GET');
    }
    
    static async createOrder(data) {
        return this.request('/orders', 'POST', data);
    }
    
    // Payment endpoints
    static async initiatePayment(order_id, amount) {
        const user = this.getCurrentUser();
        return this.request('/payment/initiate', 'POST', {
            order_id,
            amount,
            name: user?.full_name || user?.name || '',
            email: user?.email || ''
        });
    }
    
    static async paymentCallback(data) {
        return this.request('/payment/callback', 'POST', data);
    }
    
    static async verifyPayment(transaction_id) {
        return this.request('/payment/verify', 'POST', { transaction_id });
    }
    
    // Admin endpoints
    static async getUsers(page = 1, limit = 10) {
        return this.request(`/admin/users?page=${page}&limit=${limit}`, 'GET');
    }
    
    static async deleteUser(user_id) {
        return this.request(`/admin/users/${user_id}`, 'DELETE');
    }
    
    static async getAdminOrders(page = 1, limit = 10) {
        return this.request(`/admin/orders?page=${page}&limit=${limit}`, 'GET');
    }
    
    static async updateOrderStatus(order_id, status) {
        return this.request(`/admin/orders/${order_id}`, 'PUT', { status });
    }
    
    static async getAdminProducts(page = 1, limit = 20) {
        return this.request(`/admin/products?page=${page}&limit=${limit}`, 'GET');
    }
    
    // Dashboard endpoints
    static async getDashboardStats() {
        return this.request('/dashboard', 'GET');
    }
}

/**
 * UI Helper Functions
 */

class UIHelper {
    static showAlert(message, type = 'success') {
        // ensure a stable "top" container so the user always sees the message
        let container = document.getElementById('ui-alerts');
        if (!container) {
            container = document.createElement('div');
            container.id = 'ui-alerts';
            container.style.position = 'fixed';
            container.style.top = '16px';
            container.style.left = '50%';
            container.style.transform = 'translateX(-50%)';
            container.style.zIndex = '30000';
            container.style.width = 'min(900px, calc(100% - 24px))';
            container.style.pointerEvents = 'none';
            container.style.display = 'block';
            document.body.appendChild(container);
        }

        // avoid stacking alerts and pushing content down
        container.innerHTML = '';

        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type}`;
        alertDiv.textContent = message;
        alertDiv.style.pointerEvents = 'auto';

        container.appendChild(alertDiv);

        // Always visible at top; do not rely on scroll
        container.style.opacity = '1';

        setTimeout(() => {
            if (alertDiv && alertDiv.parentNode) alertDiv.parentNode.removeChild(alertDiv);
        }, 5000);
    }
    
    static showError(message) {
        this.showAlert(message, 'error');
    }
    
    static formatCurrency(amount) {
        const value = parseFloat(amount);
        if (Number.isNaN(value)) {
            return 'Rs. 0.00';
        }
        return `Rs. ${value.toFixed(2)}`;
    }
    
    static scrollToHash(hash) {
        if (!hash) return;
        const id = hash.startsWith('#') ? hash.slice(1) : hash;
        const el = document.getElementById(id);
        if (el) {
            el.scrollIntoView({ behavior: 'smooth' });
        }
    }
    
    static initNavigation() {
        if (window.location.hash) {
            setTimeout(() => UIHelper.scrollToHash(window.location.hash), 150);
        }

        document.querySelectorAll('.nav-links a[href^="#"]').forEach(link => {
            link.addEventListener('click', (e) => {
                const hash = link.getAttribute('href');
                const target = document.getElementById(hash.slice(1));
                if (target) {
                    e.preventDefault();
                    UIHelper.scrollToHash(hash);
                    history.pushState(null, '', hash);
                }
            });
        });
    }
    
    static formatDate(dateString) {
        return new Date(dateString).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }
    
    static isLoggedIn() {
        return !!APIService.getAuthToken();
    }
    
    static redirectToLogin() {
        window.location.href = 'login.html';
    }
    
    static redirectToHome() {
        window.location.href = 'index.html';
    }
    
    static updateHeader() {
    const user = APIService.getCurrentUser();
    const authButtons = document.querySelector('.auth-buttons');

    if (!authButtons) return;

    if (user) {

        let adminButton = '';

        // Show Dashboard button only for admins
        if (
            user.role === 'admin' ||
            user.user_type === 'admin' ||
            user.is_admin == 1
        ) {
            adminButton = `
                <a href="admin-dashboard.html" class="btn btn-primary btn-small">
                    Dashboard
                </a>
            `;
        }

        authButtons.innerHTML = `
            <span>${user.username}</span>
            ${adminButton}
            <a href="profile.html" class="btn btn-secondary btn-small">Profile</a>
            <button onclick="logout()" class="btn btn-outline btn-small">Logout</button>
        `;
    } else {
        authButtons.innerHTML = `
            <a href="login.html" class="btn btn-primary">Login</a>
            <a href="register.html" class="btn btn-secondary">Register</a>
        `;
    }
}
    
    static updateCartCount() {
        if (!UIHelper.isLoggedIn()) {
            return;
        }
        
        APIService.getCart().then(response => {
            if (response.status === 200) {
                const count = response.data.count || 0;
                const cartCount = document.querySelector('.cart-count');
                if (cartCount) {
                    cartCount.textContent = count > 0 ? count : '';
                }
            }
        });
    }
}

/**
 * Cart Local Storage Manager
 */

class CartManager {
    static getLocalCart() {
        const cart = localStorage.getItem('cart');
        return cart ? JSON.parse(cart) : [];
    }
    
    static saveLocalCart(cart) {
        localStorage.setItem('cart', JSON.stringify(cart));
    }
    
    static addItem(product_id, quantity = 1) {
        const cart = this.getLocalCart();
        const existing = cart.find(item => item.product_id === product_id);
        
        if (existing) {
            existing.quantity += quantity;
        } else {
            cart.push({ product_id, quantity });
        }
        
        this.saveLocalCart(cart);
        return cart;
    }
    
    static removeItem(product_id) {
        let cart = this.getLocalCart();
        cart = cart.filter(item => item.product_id !== product_id);
        this.saveLocalCart(cart);
        return cart;
    }
    
    static updateQuantity(product_id, quantity) {
        const cart = this.getLocalCart();
        const item = cart.find(item => item.product_id === product_id);
        
        if (item) {
            if (quantity <= 0) {
                return this.removeItem(product_id);
            }
            item.quantity = quantity;
        }
        
        this.saveLocalCart(cart);
        return cart;
    }
    
    static clear() {
        localStorage.removeItem('cart');
        return [];
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    UIHelper.updateHeader();
    UIHelper.updateCartCount();
    UIHelper.initNavigation();
});

// Global logout function
function logout() {
    APIService.logout();
    UIHelper.showAlert('Logged out successfully');
    setTimeout(() => {
        UIHelper.redirectToHome();
    }, 1000);
}
