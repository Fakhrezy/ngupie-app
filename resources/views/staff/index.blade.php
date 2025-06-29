<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard - Coffee Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            overflow-x: hidden;
        }

        .container {
            max-width: 420px;
            margin: 0 auto;
            padding: 1rem;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .dashboard-card {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 24px;
            padding: 2rem;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            width: 100%;
            text-align: center;
            position: relative;
        }

        .header {
            margin-bottom: 2rem;
        }

        .title {
            color: #2d3748;
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
        }

        .subtitle {
            color: #718096;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .time-container {
            background: linear-gradient(135deg, #4c51bf 0%, #805ad5 100%);
            color: white;
            padding: 1.5rem;
            border-radius: 16px;
            margin-bottom: 1.5rem;
        }

        .time {
            font-family: 'Courier New', monospace;
            font-size: 2.5rem;
            font-weight: bold;
            line-height: 1;
            margin-bottom: 0.5rem;
        }

        .date {
            font-size: 1rem;
            opacity: 0.9;
            font-weight: 500;
        }

        .status-card {
            background: #f7fafc;
            border-radius: 12px;
            padding: 1.25rem;
            margin: 1.5rem 0;
            border: 1px solid #e2e8f0;
        }

        .status-label {
            font-size: 0.875rem;
            color: #4a5568;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .status-value {
            font-size: 1.1rem;
            font-weight: 700;
            color: #2d3748;
        }

        .check-time {
            font-size: 0.9rem;
            color: #718096;
            margin-top: 0.5rem;
        }

        .buttons {
            display: flex;
            gap: 0.75rem;
            margin-top: 1.5rem;
        }

        .btn {
            flex: 1;
            padding: 0.875rem 1rem;
            border: none;
            border-radius: 12px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-checkin {
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
            color: white;
        }

        .btn-checkin:hover:not(:disabled) {
            transform: translateY(-1px);
            box-shadow: 0 8px 25px rgba(72, 187, 120, 0.3);
        }

        .btn-checkout {
            background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%);
            color: white;
        }

        .btn-checkout:hover:not(:disabled) {
            transform: translateY(-1px);
            box-shadow: 0 8px 25px rgba(245, 101, 101, 0.3);
        }

        .btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none !important;
            box-shadow: none !important;
        }

        .footer {
            margin-top: 1.5rem;
            padding-top: 1rem;
            border-top: 1px solid #e2e8f0;
        }

        .footer-text {
            font-size: 0.8rem;
            color: #a0aec0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .user-info {
            position: absolute;
            top: -0.5rem;
            right: -0.5rem;
            background: rgba(255, 255, 255, 0.95);
            padding: 0.75rem;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .user-display {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.85rem;
            color: #4a5568;
        }

        .logout-btn {
            background: none;
            border: none;
            color: #e53e3e;
            cursor: pointer;
            padding: 0.25rem;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .logout-btn:hover {
            background: rgba(229, 62, 62, 0.1);
            transform: scale(1.1);
        }

        @media (max-width: 480px) {
            .container {
                padding: 0.5rem;
            }

            .dashboard-card {
                padding: 1.5rem;
                border-radius: 20px;
            }

            .time {
                font-size: 2rem;
            }

            .title {
                font-size: 1.5rem;
            }
        }

        .loading {
            opacity: 0.7;
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
            }
        }

        .pulse {
            animation: pulse 2s infinite;
        }
    </style>
</head>
<body>
    <!-- User Info -->
    @if(Session::has('user'))
    <div class="user-info">
        <div class="user-display">
            <i class="fas fa-user-circle"></i>
            <span>{{ Session::get('user')['name'] }}</span>
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="logout-btn" onclick="return confirm('Keluar dari sistem?')" title="Logout">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </form>
        </div>
    </div>
    @endif

    <div class="container">
        <div class="dashboard-card">
            <!-- Header -->
            <div class="header">
                <h1 class="title">
                    <i class="fas fa-coffee"></i>
                    Staff Dashboard
                </h1>
                <p class="subtitle">Coffee Shop Attendance</p>
            </div>

            <!-- Time Display -->
            <div class="time-container">
                <div class="time" id="current-time">00:00:00</div>
                <div class="date" id="current-date">Loading...</div>
            </div>

            <!-- Status Card -->
            <div class="status-card">
                <div class="status-label">Status Absensi</div>
                <div class="status-value" id="attendance-status">Belum Check In</div>
                <div class="check-time" id="check-in-time" style="display: none;">
                    Check In: <span></span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="buttons">
                <button class="btn btn-checkin" onclick="checkIn()" id="checkin-btn">
                    <i class="fas fa-sign-in-alt"></i>
                    Check In
                </button>
                <button class="btn btn-checkout" onclick="checkOut()" id="checkout-btn" disabled>
                    <i class="fas fa-sign-out-alt"></i>
                    Check Out
                </button>
            </div>

            <!-- Footer -->
            <div class="footer">
                <div class="footer-text">
                    <i class="fas fa-shield-alt"></i>
                    Secure Attendance System
                </div>
            </div>
        </div>
    </div>

    <script>
        // Update clock function
        function updateClock() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID', {
                hour12: false,
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
            const dateString = now.toLocaleDateString('id-ID', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            document.getElementById('current-time').textContent = timeString;
            document.getElementById('current-date').textContent = dateString;
        }

        // Check In function
        function checkIn() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID', {
                hour12: false,
                hour: '2-digit',
                minute: '2-digit'
            });

            // Update UI
            document.getElementById('attendance-status').textContent = 'Sudah Check In';
            document.getElementById('check-in-time').style.display = 'block';
            document.getElementById('check-in-time').querySelector('span').textContent = timeString;

            // Update buttons
            document.getElementById('checkin-btn').disabled = true;
            document.getElementById('checkout-btn').disabled = false;

            // Store in localStorage
            localStorage.setItem('checkInTime', timeString);
            localStorage.setItem('checkInStatus', 'checked_in');
            localStorage.setItem('checkInDate', new Date().toDateString());

            // Show success message
            showNotification('Check In berhasil pada ' + timeString, 'success');
        }

        // Check Out function
        function checkOut() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID', {
                hour12: false,
                hour: '2-digit',
                minute: '2-digit'
            });
            const checkInTime = localStorage.getItem('checkInTime');

            if (!checkInTime) {
                showNotification('Anda belum check in!', 'error');
                return;
            }

            // Update UI
            document.getElementById('attendance-status').textContent = 'Sudah Check Out';

            // Update buttons
            document.getElementById('checkout-btn').disabled = true;

            // Clear localStorage
            localStorage.removeItem('checkInTime');
            localStorage.removeItem('checkInStatus');
            localStorage.removeItem('checkInDate');

            // Show success message
            showNotification(`Check Out berhasil pada ${timeString}\nCheck In: ${checkInTime}`, 'success');

            // Reset after 3 seconds
            setTimeout(() => {
                location.reload();
            }, 3000);
        }

        // Initialize status on page load
        function initializeStatus() {
            const checkInTime = localStorage.getItem('checkInTime');
            const checkInStatus = localStorage.getItem('checkInStatus');
            const checkInDate = localStorage.getItem('checkInDate');
            const today = new Date().toDateString();

            // Check if check-in is from today
            if (checkInTime && checkInStatus === 'checked_in' && checkInDate === today) {
                document.getElementById('attendance-status').textContent = 'Sudah Check In';
                document.getElementById('check-in-time').style.display = 'block';
                document.getElementById('check-in-time').querySelector('span').textContent = checkInTime;
                document.getElementById('checkin-btn').disabled = true;
                document.getElementById('checkout-btn').disabled = false;
            } else {
                // Clear old data if from different day
                localStorage.removeItem('checkInTime');
                localStorage.removeItem('checkInStatus');
                localStorage.removeItem('checkInDate');
            }
        }

        // Show notification function
        function showNotification(message, type = 'info') {
            // Create notification element
            const notification = document.createElement('div');
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 1rem 1.5rem;
                border-radius: 12px;
                color: white;
                font-weight: 600;
                z-index: 1000;
                max-width: 300px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
                backdrop-filter: blur(10px);
                animation: slideIn 0.3s ease;
            `;

            // Set color based on type
            if (type === 'success') {
                notification.style.background = 'linear-gradient(135deg, #48bb78 0%, #38a169 100%)';
            } else if (type === 'error') {
                notification.style.background = 'linear-gradient(135deg, #f56565 0%, #e53e3e 100%)';
            } else {
                notification.style.background = 'linear-gradient(135deg, #4299e1 0%, #3182ce 100%)';
            }

            notification.textContent = message;
            document.body.appendChild(notification);

            // Remove notification after 3 seconds
            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 300);
            }, 3000);
        }

        // Add CSS animations
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideIn {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }

            @keyframes slideOut {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(100%);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);

        // Initialize everything
        updateClock();
        setInterval(updateClock, 1000);
        initializeStatus();

        // Add loading state management
        window.addEventListener('load', function() {
            document.body.classList.remove('loading');
        });
    </script>
</body>
</html>
