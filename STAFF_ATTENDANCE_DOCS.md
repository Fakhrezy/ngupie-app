# Halaman Staff untuk Kehadiran Karyawan - Coffee Shop Dashboard

## Overvie
w
Halaman staff telah dibuat untuk mengelola kehadiran karyawan yang terhubung langsung dengan halaman manager. Sistem ini menggunakan data sharing service untuk memastikan data absensi antara staff dan manager selalu sinkron.

## Fitur Utama Halaman Staff

### 1. Personal Attendance Da
sh b oard
- **Real-time Clock**: Menampilkan waktu dan tanggal sa a t ini
- **Check-in/Check-out**: Fitur absensi personal dengan validas  i waktu
- **Status Tracking**: Melacak status kehadiran (belum check-in, sudah check-in, ch  eck-out)
- **Break Management**: Sistem manajemen istirahat dengan tracking durasi

### 2. Team Attenda
n  ce Overview
- **Team Status**: Melihat status kehadiran semua karya  wan hari ini
- **Real-time Updates**: Data yang selalu update dengan si  stem manager
- **Performance Indicators**: Indikator keterlambatan dan lembur

### 3. P
er  sonal Statistics
- **Daily Stats**: Ja  m kerja hari ini
- **Weekly Summary**: Total jam kerja, keterlambatan, dan   lembur minggu ini
- **Historical Data**: Riwayat absensi minggu berjalan


###   4. Leave Management
- **Request Leave**: Fo r m pengajuan cuti/izin
- **Status Tracking**: Melacak status permohonan cuti

## Koneksi dengan Halaman Manager

### Data Sharing Serv
ice (`AttendanceDataServic
e  `)
Service ini menyediakan:
- **Shared Data**: Data absensi yang sam  a digunakan staff dan manager
- **Real-time Sync**: Update dari staff   langsung terlihat di manager
- **Centralized Management**: Satu sumber data untuk 
semua role

### Fitur Integrasi:
1. **Attendance Updates**: Check-in/out staff langsung terupdate di dashboard manager
2. **Status Management**: Manager dapat mengubah status absensi yang dilihat staff
3. **Leave Approval**: Manager dapat approve/reject cuti yang diajukan staff
4. **Reporting**: Data laporan shared antara staff dan manager

  
## File dan Struktur

### Controllers
- `StaffContro  ller.php`: Mengelola logika halaman staff
- `ManagerControl  ler.php`: Mengelola logika halaman manager
- `AttendanceDataServic
e.  php`: Service untuk data sharing

### Views
- `staff/i  ndex.blade.php`: Halaman utama staff dashboard
- `manager/index.blade.
php`: Halaman utama manager dashboard

### Routes
```php
// Staff routes
Route::middleware(['staff.access'])->group(function () {
    Route::get('/staff', [StaffController::class, 'index']);
    Route::post('/staff/checkin', [StaffController::class, 'checkIn']);
    Route::post('/staff/checkout', [StaffController::class, 'checkOut']);
    Route::post('/staff/update-status', [StaffController::class, 'updateStatus']);
    Route::get('/staff/report', [StaffController::class, 'report']);
    Route::post('/staff/request-leave', [StaffController::class, 'requestLeave']);
    Route::post('/staff/break', [StaffController::class, 'break']);
});

// Manager routes
Route::middleware(['manager.access'])->group(function () {
    Route::get('/manager', [ManagerController::class, 'index']);
    Route::post('/manager/update-status', [ManagerController::class, 'updateStatus']);
    Route::post('/manager/bulk-update', [ManagerController::class, 'bulkUpdate']);
    Route::post('/manager/export', [ManagerController::class, 'export']);
    Route::post('/manager/approve-leave', [ManagerController::class, 'a
pproveLeave'];

} );
```

## Cara Login dan Test 

### Kredensial Login:
1. **Staff**:

 
   - Email: `staff@coffeshop.com`
    - Password: `staff123`

2. **Manager**:  
   - Email: `manager@coffeshop.com`
   - Password:  `manager123`

3. **Lainnya**:
   - Barista: `barista@coffe
shop.com` / `barista123`
   - Kasir: `kasir@coffeshop.com` / `kasir123`

### Test Flow:
1. Login sebagai staff → Lakukan check-in/check-out
2. Login sebagai manager → Lihat update data dari staff
3. Manager ubah status absensi → Staff akan melihat perubahan
4.
 Staff ajukan cuti → Manager dapat approve/reject

## Technical Implementation

### Data Flow:
```
Staff Action (Check-in) → AttendanceDataService
 →   Manager View
Manager Action (Approve) → AttendanceDataService →   Staff View
```

### Key Features:
- **Session-based   Authentication**: Multi-role dengan middleware
- **AJAX Operations*  *: Update data tanpa refresh halaman
- **Responsive Design**:   Bootstrap 5 untuk tampilan mobile-friendly
- **Real-time Clock**: JavaScript untuk up
date waktu real-time
- **Data Validation**: Server-side validation untuk semua input

## Future Enhancements
1. **Database Integration**: Ganti dummy data dengan database MySQL/PostgreSQL
2. **Real-time Notifications**: WebSocket untuk notifikasi real-time
3. **Advanced Reporting**: Chart dan grafik untuk analisis kehadiran
4. **Mobile App Integration*
*: API untuk aplikasi mobile
5. **Photo Verification**: Upload foto saat check-in/check-out

## Status Implementasi
✅ Halaman staff lengkap dengan fitur check-in/check-out
✅ Koneksi data dengan halaman manager
✅ Service data sharing
✅ Leave management system
✅ Real-time clock dan statistics
✅ Responsive design
✅ Multi-role authentication
✅ AJAX operations

Sistem sudah siap untuk testing dan development lanjutan!
