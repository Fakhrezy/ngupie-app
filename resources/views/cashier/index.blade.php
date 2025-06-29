<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir - Coffee Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .cashier-header {
            background: linear-gradient(135deg, #8B4513 0%, #D2691E 100%);
            color: white;
            padding: 1rem 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .product-card {
            transition: all 0.3s;
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            cursor: pointer;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
        }
        .category-btn {
            margin: 0.25rem;
            border-radius: 20px;
            padding: 0.5rem 1rem;
        }
        .cart-section {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
            position: sticky;
            top: 20px;
        }
        .cart-item {
            border-bottom: 1px solid #eee;
            padding: 0.75rem 0;
        }
        .cart-item:last-child {
            border-bottom: none;
        }
        .price-display {
            font-size: 1.1rem;
            font-weight: bold;
            color: #8B4513;
        }
        .total-section {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1rem;
            margin-top: 1rem;
        }
        .btn-add-cart {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            border-radius: 6px;
            color: white;
            transition: all 0.3s;
        }
        .btn-add-cart:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
            color: white;
        }
        .btn-checkout {
            background: linear-gradient(135deg, #8B4513 0%, #D2691E 100%);
            border: none;
            border-radius: 8px;
            color: white;
            font-weight: 600;
            padding: 0.75rem;
        }
        .quantity-control {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .quantity-btn {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            border: 1px solid #ddd;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
        }
        .quantity-btn:hover {
            background: #f8f9fa;
            border-color: #8B4513;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="cashier-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h3 class="mb-0">
                        <i class="fas fa-coffee me-2"></i>
                        Coffee Shop - Kasir
                    </h3>
                </div>
                <div class="col-md-6 text-end">
                    <div class="d-flex align-items-center justify-content-end">
                        @if(Session::has('user'))
                        <div class="me-3">
                            <i class="fas fa-user-circle me-2"></i>
                            <span>{{ Session::get('user')['name'] }}</span>
                            <small class="opacity-75 ms-2">({{ Session::get('user')['role'] }})</small>
                        </div>
                        @endif
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-light btn-sm"
                                    onclick="return confirm('Logout dari sistem kasir?')">
                                <i class="fas fa-sign-out-alt me-1"></i>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container-fluid mt-3">
        <div class="row">
            <!-- Menu Section -->
            <div class="col-md-8">
                <!-- Category Filter -->
                <div class="mb-3">
                    <h5>Kategori Menu</h5>
                    <div class="category-filters">
                        @foreach($categories as $category)
                        <button class="btn btn-outline-primary category-btn {{ $loop->first ? 'active' : '' }}"
                                data-category="{{ $category }}">
                            {{ $category }}
                        </button>
                        @endforeach
                    </div>
                </div>

                <!-- Menu Grid -->
                <div class="row" id="menu-grid">
                    @foreach($menus as $menu)
                    <div class="col-md-4 col-lg-3 mb-3 menu-item" data-category="{{ $menu['category'] }}">
                        <div class="card product-card h-100">
                            <img src="{{ $menu['image'] }}" class="card-img-top" alt="{{ $menu['name'] }}" style="height: 150px; object-fit: cover;">
                            <div class="card-body d-flex flex-column">
                                <h6 class="card-title">{{ $menu['name'] }}</h6>
                                <p class="card-text text-muted small">{{ $menu['category'] }}</p>
                                <div class="price-display mb-2">Rp {{ number_format($menu['price'], 0, ',', '.') }}</div>
                                <div class="text-muted small mb-2">
                                    <i class="fas fa-box me-1"></i>
                                    Stok: {{ $menu['stock'] }}
                                </div>
                                <div class="mt-auto">
                                    <button class="btn btn-add-cart btn-sm w-100"
                                            onclick="addToCart({{ $menu['id'] }}, '{{ $menu['name'] }}', {{ $menu['price'] }})"
                                            {{ $menu['stock'] == 0 ? 'disabled' : '' }}>
                                        <i class="fas fa-plus me-1"></i>
                                        {{ $menu['stock'] == 0 ? 'Stok Habis' : 'Tambah' }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Cart Section -->
            <div class="col-md-4">
                <div class="cart-section p-3">
                    <h5 class="mb-3">
                        <i class="fas fa-shopping-cart me-2"></i>
                        Keranjang Belanja
                        <span class="badge bg-primary ms-2" id="cart-count">0</span>
                    </h5>

                    <!-- Customer Info -->
                    <div class="mb-3">
                        <label class="form-label">Nama Pelanggan</label>
                        <input type="text" class="form-control" id="customer-name" placeholder="Masukkan nama pelanggan">
                    </div>

                    <!-- Cart Items -->
                    <div id="cart-items" class="mb-3">
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-shopping-cart fa-2x mb-2 opacity-50"></i>
                            <p>Keranjang masih kosong</p>
                        </div>
                    </div>

                    <!-- Total Section -->
                    <div class="total-section">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span id="subtotal">Rp 0</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Pajak (10%):</span>
                            <span id="tax">Rp 0</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Total:</strong>
                            <strong id="total">Rp 0</strong>
                        </div>

                        <!-- Payment Method -->
                        <div class="mb-3">
                            <label class="form-label">Metode Pembayaran</label>
                            <select class="form-select" id="payment-method">
                                <option value="cash">Tunai</option>
                                <option value="card">Kartu Kredit/Debit</option>
                                <option value="digital">E-Wallet</option>
                            </select>
                        </div>

                        <button class="btn btn-checkout w-100" onclick="processPayment()" id="checkout-btn" disabled>
                            <i class="fas fa-credit-card me-2"></i>
                            Proses Pembayaran
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-check-circle me-2"></i>
                        Pembayaran Berhasil
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-receipt fa-3x text-success mb-3"></i>
                        <h5>Transaksi Berhasil!</h5>
                        <p class="text-muted">Order ID: <span id="modal-order-id"></span></p>
                        <p class="h4 text-success">Total: <span id="modal-total"></span></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="newTransaction()">
                        <i class="fas fa-plus me-2"></i>
                        Transaksi Baru
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="printReceipt()">
                        <i class="fas fa-print me-2"></i>
                        Cetak Struk
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let cart = [];
        let cartTotal = 0;

        // Setup CSRF token for fetch requests
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Category filter
        document.querySelectorAll('.category-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                // Remove active from all buttons
                document.querySelectorAll('.category-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                const category = this.dataset.category;
                filterMenu(category);
            });
        });

        function filterMenu(category) {
            const menuItems = document.querySelectorAll('.menu-item');
            menuItems.forEach(item => {
                if (category === 'All' || item.dataset.category === category) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        function addToCart(id, name, price) {
            const existingItem = cart.find(item => item.id === id);

            if (existingItem) {
                existingItem.quantity += 1;
            } else {
                cart.push({
                    id: id,
                    name: name,
                    price: price,
                    quantity: 1
                });
            }

            updateCartDisplay();
        }

        function removeFromCart(id) {
            cart = cart.filter(item => item.id !== id);
            updateCartDisplay();
        }

        function updateQuantity(id, newQuantity) {
            if (newQuantity <= 0) {
                removeFromCart(id);
                return;
            }

            const item = cart.find(item => item.id === id);
            if (item) {
                item.quantity = newQuantity;
                updateCartDisplay();
            }
        }

        function updateCartDisplay() {
            const cartItemsContainer = document.getElementById('cart-items');
            const cartCount = document.getElementById('cart-count');

            if (cart.length === 0) {
                cartItemsContainer.innerHTML = `
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-shopping-cart fa-2x mb-2 opacity-50"></i>
                        <p>Keranjang masih kosong</p>
                    </div>
                `;
                cartCount.textContent = '0';
                document.getElementById('checkout-btn').disabled = true;
            } else {
                let html = '';
                let subtotal = 0;
                let totalItems = 0;

                cart.forEach(item => {
                    const itemTotal = item.price * item.quantity;
                    subtotal += itemTotal;
                    totalItems += item.quantity;

                    html += `
                        <div class="cart-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">${item.name}</h6>
                                    <div class="text-muted small">Rp ${item.price.toLocaleString()}</div>
                                </div>
                                <button class="btn btn-sm btn-outline-danger" onclick="removeFromCart(${item.id})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <div class="quantity-control">
                                    <div class="quantity-btn" onclick="updateQuantity(${item.id}, ${item.quantity - 1})">
                                        <i class="fas fa-minus"></i>
                                    </div>
                                    <span class="px-2">${item.quantity}</span>
                                    <div class="quantity-btn" onclick="updateQuantity(${item.id}, ${item.quantity + 1})">
                                        <i class="fas fa-plus"></i>
                                    </div>
                                </div>
                                <strong>Rp ${itemTotal.toLocaleString()}</strong>
                            </div>
                        </div>
                    `;
                });

                cartItemsContainer.innerHTML = html;
                cartCount.textContent = totalItems;

                const tax = subtotal * 0.1;
                const total = subtotal + tax;

                document.getElementById('subtotal').textContent = `Rp ${subtotal.toLocaleString()}`;
                document.getElementById('tax').textContent = `Rp ${tax.toLocaleString()}`;
                document.getElementById('total').textContent = `Rp ${total.toLocaleString()}`;

                cartTotal = total;
                document.getElementById('checkout-btn').disabled = false;
            }
        }

        function processPayment() {
            const customerName = document.getElementById('customer-name').value;
            const paymentMethod = document.getElementById('payment-method').value;

            if (!customerName.trim()) {
                alert('Mohon masukkan nama pelanggan');
                return;
            }

            if (cart.length === 0) {
                alert('Keranjang masih kosong');
                return;
            }

            // Simulate successful payment
            const orderId = 'ORD-' + new Date().toISOString().slice(0,10).replace(/-/g,'') + '-' + Math.floor(Math.random() * 9000 + 1000);

            document.getElementById('modal-order-id').textContent = orderId;
            document.getElementById('modal-total').textContent = `Rp ${cartTotal.toLocaleString()}`;

            const modal = new bootstrap.Modal(document.getElementById('successModal'));
            modal.show();
        }

        function newTransaction() {
            cart = [];
            cartTotal = 0;
            document.getElementById('customer-name').value = '';
            document.getElementById('payment-method').value = 'cash';
            updateCartDisplay();

            const modal = bootstrap.Modal.getInstance(document.getElementById('successModal'));
            modal.hide();
        }

        function printReceipt() {
            alert('Fitur cetak struk akan dikembangkan selanjutnya');
        }

        // Initialize display
        updateCartDisplay();
    </script>
</body>
</html>
