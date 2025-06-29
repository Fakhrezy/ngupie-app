<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barista - Coffee Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .barista-header {
            background: linear-gradient(135deg, #8B4513 0%, #D2691E 100%);
            color: white;
            padding: 1rem 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .order-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: all 0.3s;
            margin-bottom: 1rem;
        }
        .order-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        }
        .order-card.priority-high {
            border-left: 5px solid #dc3545;
        }
        .order-card.status-pending {
            border-top: 3px solid #ffc107;
        }
        .order-card.status-in_progress {
            border-top: 3px solid #0dcaf0;
        }
        .order-card.status-completed {
            border-top: 3px solid #198754;
            opacity: 0.8;
        }
        .status-badge {
            border-radius: 20px;
            font-size: 0.75rem;
            padding: 0.25rem 0.75rem;
        }
        .priority-badge {
            border-radius: 15px;
            font-size: 0.7rem;
            padding: 0.2rem 0.5rem;
        }
        .order-items {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 0.75rem;
            margin: 0.5rem 0;
        }
        .recipe-btn {
            background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%);
            border: none;
            border-radius: 6px;
            color: white;
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            transition: all 0.3s;
        }
        .recipe-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 3px 10px rgba(23, 162, 184, 0.3);
            color: white;
        }
        .status-buttons {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
        }
        .btn-status {
            border-radius: 20px;
            font-size: 0.75rem;
            padding: 0.5rem 1rem;
            border: none;
            transition: all 0.3s;
        }
        .btn-pending {
            background: #ffc107;
            color: #000;
        }
        .btn-progress {
            background: #0dcaf0;
            color: #000;
        }
        .btn-complete {
            background: #198754;
            color: white;
        }
        .time-info {
            font-size: 0.8rem;
            color: #6c757d;
        }
        .recipe-modal .modal-body {
            max-height: 70vh;
            overflow-y: auto;
        }
        .recipe-section {
            margin-bottom: 1.5rem;
        }
        .recipe-section h6 {
            color: #8B4513;
            font-weight: 600;
            margin-bottom: 0.75rem;
        }
        .ingredient-item, .step-item, .tip-item {
            background: #f8f9fa;
            border-radius: 6px;
            padding: 0.5rem;
            margin-bottom: 0.5rem;
            border-left: 3px solid #8B4513;
        }
        .step-number {
            background: #8B4513;
            color: white;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            margin-right: 0.5rem;
        }
        .stats-cards {
            margin-bottom: 2rem;
        }
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border: none;
        }
        .stat-value {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        .stat-label {
            color: #6c757d;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="barista-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h3 class="mb-0">
                        <i class="fas fa-coffee me-2"></i>
                        Coffee Shop - Barista Station
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
                                    onclick="return confirm('Logout dari barista station?')">
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
        <!-- Statistics Cards -->
        <div class="row stats-cards">
            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="stat-value text-warning" id="pending-count">
                        {{ collect($orders)->where('status', 'pending')->count() }}
                    </div>
                    <div class="stat-label">Pesanan Menunggu</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="stat-value text-info" id="progress-count">
                        {{ collect($orders)->where('status', 'in_progress')->count() }}
                    </div>
                    <div class="stat-label">Sedang Dikerjakan</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="stat-value text-success" id="completed-count">
                        {{ collect($orders)->where('status', 'completed')->count() }}
                    </div>
                    <div class="stat-label">Pesanan Selesai</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="stat-value text-danger" id="priority-count">
                        {{ collect($orders)->where('priority', 'high')->count() }}
                    </div>
                    <div class="stat-label">Prioritas Tinggi</div>
                </div>
            </div>
        </div>

        <!-- Filter Buttons -->
        <div class="mb-3">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-secondary active" onclick="filterOrders('all')">
                    <i class="fas fa-list me-1"></i> Semua
                </button>
                <button type="button" class="btn btn-outline-warning" onclick="filterOrders('pending')">
                    <i class="fas fa-clock me-1"></i> Menunggu
                </button>
                <button type="button" class="btn btn-outline-info" onclick="filterOrders('in_progress')">
                    <i class="fas fa-spinner me-1"></i> Dikerjakan
                </button>
                <button type="button" class="btn btn-outline-success" onclick="filterOrders('completed')">
                    <i class="fas fa-check me-1"></i> Selesai
                </button>
                <button type="button" class="btn btn-outline-danger" onclick="filterOrders('priority')">
                    <i class="fas fa-exclamation me-1"></i> Prioritas
                </button>
            </div>
        </div>

        <!-- Orders List -->
        <div class="row">
            <div class="col-12">
                <div id="orders-container">
                    @foreach($orders as $order)
                    <div class="order-card card {{ $order['priority'] === 'high' ? 'priority-high' : '' }} status-{{ $order['status'] }}" 
                         data-status="{{ $order['status'] }}" 
                         data-priority="{{ $order['priority'] }}"
                         data-order-id="{{ $order['id'] }}">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <!-- Order Header -->
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <h5 class="card-title mb-1">{{ $order['id'] }}</h5>
                                            <p class="card-text text-muted mb-0">
                                                <i class="fas fa-user me-1"></i>
                                                {{ $order['customer_name'] }}
                                            </p>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge status-badge 
                                                @if($order['status'] === 'pending') bg-warning text-dark
                                                @elseif($order['status'] === 'in_progress') bg-info text-dark
                                                @else bg-success
                                                @endif">
                                                @if($order['status'] === 'pending') Menunggu
                                                @elseif($order['status'] === 'in_progress') Dikerjakan
                                                @else Selesai
                                                @endif
                                            </span>
                                            @if($order['priority'] === 'high')
                                            <span class="badge priority-badge bg-danger ms-1">Prioritas</span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Time Info -->
                                    <div class="time-info mb-2">
                                        <i class="fas fa-clock me-1"></i>
                                        Pesanan masuk: {{ date('H:i', strtotime($order['order_time'])) }}
                                        <span class="ms-3">
                                            <i class="fas fa-hourglass-half me-1"></i>
                                            Estimasi: {{ $order['estimated_time'] }} menit
                                        </span>
                                    </div>

                                    <!-- Order Items -->
                                    <div class="order-items">
                                        <h6 class="mb-2">
                                            <i class="fas fa-list me-1"></i>
                                            Item Pesanan:
                                        </h6>
                                        @foreach($order['items'] as $item)
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div class="flex-grow-1">
                                                <strong>{{ $item['quantity'] }}x {{ $item['name'] }}</strong>
                                                @if($item['notes'])
                                                <br><small class="text-muted">Catatan: {{ $item['notes'] }}</small>
                                                @endif
                                            </div>
                                            <button class="btn recipe-btn btn-sm" 
                                                    onclick="showRecipe('{{ $item['name'] }}')">
                                                <i class="fas fa-book me-1"></i>
                                                Resep
                                            </button>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <!-- Status Control -->
                                    <div class="status-buttons">
                                        <button class="btn btn-status btn-pending btn-sm" 
                                                onclick="updateOrderStatus('{{ $order['id'] }}', 'pending')"
                                                {{ $order['status'] === 'pending' ? 'disabled' : '' }}>
                                            <i class="fas fa-clock me-1"></i>
                                            Pending
                                        </button>
                                        <button class="btn btn-status btn-progress btn-sm" 
                                                onclick="updateOrderStatus('{{ $order['id'] }}', 'in_progress')"
                                                {{ $order['status'] === 'in_progress' ? 'disabled' : '' }}>
                                            <i class="fas fa-spinner me-1"></i>
                                            Kerjakan
                                        </button>
                                        <button class="btn btn-status btn-complete btn-sm" 
                                                onclick="updateOrderStatus('{{ $order['id'] }}', 'completed')"
                                                {{ $order['status'] === 'completed' ? 'disabled' : '' }}>
                                            <i class="fas fa-check me-1"></i>
                                            Selesai
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Recipe Modal -->
    <div class="modal fade recipe-modal" id="recipeModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-book me-2"></i>
                        <span id="recipe-title">Resep</span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="recipe-content">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Memuat resep...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" onclick="printRecipe()">
                        <i class="fas fa-print me-1"></i>
                        Cetak Resep
                    </button>
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
                        Berhasil
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p id="success-message">Status pesanan berhasil diperbarui!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Filter orders
        function filterOrders(filter) {
            const orders = document.querySelectorAll('.order-card');
            const buttons = document.querySelectorAll('.btn-group .btn');
            
            // Reset button states
            buttons.forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');

            orders.forEach(order => {
                const status = order.dataset.status;
                const priority = order.dataset.priority;
                
                let show = false;
                
                switch(filter) {
                    case 'all':
                        show = true;
                        break;
                    case 'pending':
                        show = status === 'pending';
                        break;
                    case 'in_progress':
                        show = status === 'in_progress';
                        break;
                    case 'completed':
                        show = status === 'completed';
                        break;
                    case 'priority':
                        show = priority === 'high';
                        break;
                }
                
                order.style.display = show ? 'block' : 'none';
            });
        }

        // Update order status
        async function updateOrderStatus(orderId, newStatus) {
            try {
                const response = await fetch('{{ route("barista.update-status") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        order_id: orderId,
                        status: newStatus
                    })
                });

                const result = await response.json();
                
                if (result.success) {
                    // Update UI
                    const orderCard = document.querySelector(`[data-order-id="${orderId}"]`);
                    if (orderCard) {
                        // Update card classes
                        orderCard.className = orderCard.className.replace(/status-\w+/, `status-${newStatus}`);
                        
                        // Update status badge
                        const badge = orderCard.querySelector('.status-badge');
                        badge.className = 'badge status-badge';
                        
                        if (newStatus === 'pending') {
                            badge.classList.add('bg-warning', 'text-dark');
                            badge.textContent = 'Menunggu';
                        } else if (newStatus === 'in_progress') {
                            badge.classList.add('bg-info', 'text-dark');
                            badge.textContent = 'Dikerjakan';
                        } else {
                            badge.classList.add('bg-success');
                            badge.textContent = 'Selesai';
                        }
                        
                        // Update buttons
                        const buttons = orderCard.querySelectorAll('.btn-status');
                        buttons.forEach(btn => btn.disabled = false);
                        orderCard.querySelector(`.btn-${newStatus === 'in_progress' ? 'progress' : newStatus}`).disabled = true;
                    }
                    
                    // Update statistics
                    updateStatistics();
                    
                    // Show success message
                    document.getElementById('success-message').textContent = result.message;
                    new bootstrap.Modal(document.getElementById('successModal')).show();
                }
            } catch (error) {
                alert('Terjadi kesalahan saat memperbarui status');
            }
        }

        // Show recipe
        async function showRecipe(itemName) {
            const modal = new bootstrap.Modal(document.getElementById('recipeModal'));
            const title = document.getElementById('recipe-title');
            const content = document.getElementById('recipe-content');
            
            title.textContent = `Resep ${itemName}`;
            content.innerHTML = `
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Memuat resep...</p>
                </div>
            `;
            
            modal.show();
            
            try {
                const response = await fetch(`{{ url('/barista/recipe') }}/${encodeURIComponent(itemName)}`);
                const result = await response.json();
                
                if (result.success) {
                    const recipe = result.recipe;
                    content.innerHTML = `
                        <div class="recipe-section">
                            <p class="text-muted">${recipe.description}</p>
                            <div class="alert alert-info">
                                <i class="fas fa-clock me-2"></i>
                                <strong>Waktu Persiapan:</strong> ${recipe.time}
                            </div>
                        </div>
                        
                        <div class="recipe-section">
                            <h6><i class="fas fa-list-ul me-1"></i> Bahan-bahan:</h6>
                            ${recipe.ingredients.map(ingredient => `
                                <div class="ingredient-item">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    ${ingredient}
                                </div>
                            `).join('')}
                        </div>
                        
                        <div class="recipe-section">
                            <h6><i class="fas fa-tasks me-1"></i> Langkah-langkah:</h6>
                            ${recipe.steps.map((step, index) => `
                                <div class="step-item">
                                    <span class="step-number">${index + 1}</span>
                                    ${step}
                                </div>
                            `).join('')}
                        </div>
                        
                        <div class="recipe-section">
                            <h6><i class="fas fa-lightbulb me-1"></i> Tips:</h6>
                            ${recipe.tips.map(tip => `
                                <div class="tip-item">
                                    <i class="fas fa-star text-warning me-2"></i>
                                    ${tip}
                                </div>
                            `).join('')}
                        </div>
                    `;
                } else {
                    content.innerHTML = `
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Resep untuk ${itemName} belum tersedia.
                        </div>
                    `;
                }
            } catch (error) {
                content.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        Terjadi kesalahan saat memuat resep.
                    </div>
                `;
            }
        }

        // Update statistics
        function updateStatistics() {
            const orders = document.querySelectorAll('.order-card');
            let pending = 0, progress = 0, completed = 0, priority = 0;
            
            orders.forEach(order => {
                const status = order.dataset.status;
                const priorityLevel = order.dataset.priority;
                
                if (status === 'pending') pending++;
                else if (status === 'in_progress') progress++;
                else if (status === 'completed') completed++;
                
                if (priorityLevel === 'high') priority++;
            });
            
            document.getElementById('pending-count').textContent = pending;
            document.getElementById('progress-count').textContent = progress;
            document.getElementById('completed-count').textContent = completed;
            document.getElementById('priority-count').textContent = priority;
        }

        // Print recipe
        function printRecipe() {
            const content = document.getElementById('recipe-content').innerHTML;
            const title = document.getElementById('recipe-title').textContent;
            
            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <html>
                    <head>
                        <title>${title}</title>
                        <style>
                            body { font-family: Arial, sans-serif; margin: 20px; }
                            .recipe-section { margin-bottom: 20px; }
                            .ingredient-item, .step-item, .tip-item { 
                                margin: 10px 0; padding: 8px; background: #f8f9fa; border-radius: 4px; 
                            }
                            h6 { color: #8B4513; font-weight: bold; }
                            .step-number { 
                                background: #8B4513; color: white; border-radius: 50%; 
                                width: 24px; height: 24px; display: inline-flex; 
                                align-items: center; justify-content: center; margin-right: 8px; 
                            }
                        </style>
                    </head>
                    <body>
                        <h1>${title}</h1>
                        ${content}
                    </body>
                </html>
            `);
            printWindow.document.close();
            printWindow.print();
        }

        // Auto refresh every 30 seconds
        setInterval(() => {
            location.reload();
        }, 30000);
    </script>
</body>
</html>
