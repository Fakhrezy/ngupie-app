<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Coffee Shop Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #8B4513 0%, #D2691E 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            width: 100%;
            max-width: 400px;
        }
        .login-header {
            background: linear-gradient(135deg, #8B4513 0%, #D2691E 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .login-body {
            padding: 2rem;
        }
        .form-control {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 0.75rem 1rem;
            margin-bottom: 1rem;
        }
        .form-control:focus {
            border-color: #8B4513;
            box-shadow: 0 0 0 0.2rem rgba(139, 69, 19, 0.25);
        }
        .btn-login {
            background: linear-gradient(135deg, #8B4513 0%, #D2691E 100%);
            border: none;
            border-radius: 10px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            width: 100%;
            color: white;
            transition: all 0.3s;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(139, 69, 19, 0.3);
            color: white;
        }
        .demo-accounts {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1rem;
            margin-top: 1rem;
        }
        .demo-account {
            background: white;
            border-radius: 8px;
            padding: 0.5rem;
            margin: 0.25rem 0;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        .demo-account:hover {
            background: #e9ecef;
        }
        .coffee-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <div class="coffee-icon">
                <i class="fas fa-coffee"></i>
            </div>
            <h2 class="mb-1">Coffee Shop</h2>
            <p class="mb-0">Management System</p>
        </div>

        <div class="login-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->has('login'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ $errors->first('login') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}">
                @csrf
                <div class="mb-3">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0" style="border-radius: 10px 0 0 10px;">
                            <i class="fas fa-envelope text-muted"></i>
                        </span>
                        <input type="email"
                               class="form-control border-start-0 @error('email') is-invalid @enderror"
                               name="email"
                               placeholder="Email"
                               value="{{ old('email') }}"
                               style="border-radius: 0 10px 10px 0;"
                               required>
                    </div>
                    @error('email')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0" style="border-radius: 10px 0 0 10px;">
                            <i class="fas fa-lock text-muted"></i>
                        </span>
                        <input type="password"
                               class="form-control border-start-0 @error('password') is-invalid @enderror"
                               name="password"
                               placeholder="Password"
                               style="border-radius: 0 10px 10px 0;"
                               required>
                    </div>
                    @error('password')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-login">
                    <i class="fas fa-sign-in-alt me-2"></i>Login
                </button>
            </form>

            <div class="demo-accounts">
                <h6 class="text-center text-muted mb-2">Demo Accounts:</h6>

                <div class="demo-account" onclick="fillLogin('admin@coffeshop.com', 'admin123')">
                    <div class="d-flex justify-content-between align-items-center">
                        <span><strong>Administrator</strong></span>
                        <span class="badge bg-danger">Admin</span>
                    </div>
                    <small class="text-muted">admin@coffeshop.com / admin123</small>
                </div>

                <div class="demo-account" onclick="fillLogin('manager@coffeshop.com', 'manager123')">
                    <div class="d-flex justify-content-between align-items-center">
                        <span><strong>Manager</strong></span>
                        <span class="badge bg-warning">Manager</span>
                    </div>
                    <small class="text-muted">manager@coffeshop.com / manager123</small>
                </div>

                <div class="demo-account" onclick="fillLogin('staff@coffeshop.com', 'staff123')">
                    <div class="d-flex justify-content-between align-items-center">
                        <span><strong>Staff</strong></span>
                        <span class="badge bg-info">Staff</span>
                    </div>
                    <small class="text-muted">staff@coffeshop.com / staff123</small>
                </div>

                <div class="demo-account" onclick="fillLogin('barista@coffeshop.com', 'barista123')">
                    <div class="d-flex justify-content-between align-items-center">
                        <span><strong>Barista</strong></span>
                        <span class="badge bg-success">Barista</span>
                    </div>
                    <small class="text-muted">barista@coffeshop.com / barista123</small>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function fillLogin(email, password) {
            document.querySelector('input[name="email"]').value = email;
            document.querySelector('input[name="password"]').value = password;
        }
    </script>
</body>
</html>
