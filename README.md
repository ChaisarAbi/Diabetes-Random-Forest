# Sistem Prediksi Diabetes - CodeIgniter 4 + AdminLTE + Python Random Forest

Sistem web untuk prediksi diabetes menggunakan Machine Learning Random Forest dengan integrasi Python dan CodeIgniter 4.

## Fitur Utama

### 1. **Dashboard Admin/Petugas**
- Statistik jumlah pasien, prediksi, petugas, dan hasil positif
- Grafik prediksi bulanan menggunakan Chart.js
- Prediksi terbaru dengan status warna

### 2. **Manajemen Data Pasien**
- CRUD lengkap data pasien
- Tabel dengan DataTables
- Validasi form input
- Field: nama, umur, jenis kelamin, berat, tinggi, alamat

### 3. **Prediksi Diabetes**
- Form input 8 fitur sesuai dataset PIMA:
  - Pregnancies, Glucose, BloodPressure, SkinThickness
  - Insulin, BMI, DiabetesPedigreeFunction, Age
- Integrasi Python Random Forest untuk prediksi
- Hasil: 0 (Tidak Diabetes) atau 1 (Diabetes)
- Tabel hasil prediksi dengan warna status

### 4. **Manajemen Petugas/Admin**
- Role-based access control (admin/petugas)
- Admin: full access
- Petugas: tidak bisa CRUD petugas
- Password hashing dengan password_hash()

### 5. **Laporan Hasil**
- Statistik prediksi per bulan
- Filter berdasarkan tanggal, pasien, hasil
- Export PDF/Excel
- Grafik tren menggunakan Chart.js

### 6. **Keamanan**
- Authentication dengan session
- CSRF protection
- Role-based authorization
- Password hashing
- Input validation

## Teknologi yang Digunakan

### Backend
- **CodeIgniter 4** - PHP Framework
- **PHP 8+** - Bahasa pemrograman
- **MySQL/SQLite** - Database

### Frontend
- **AdminLTE 3** - Admin dashboard template
- **Bootstrap 4** - CSS Framework
- **jQuery** - JavaScript library
- **DataTables** - Tabel interaktif
- **Chart.js** - Visualisasi data
- **SweetAlert2** - Notifikasi

### Machine Learning
- **Python 3.10+** - Bahasa pemrograman
- **scikit-learn** - Library ML
- **Random Forest Classifier** - Algoritma prediksi
- **joblib/pickle** - Serialisasi model

## Struktur Folder

```
/app
    /Controllers        # Controller MVC
    /Models             # Model database
    /Views              # Template view
        /layouts        # Layout template
        /auth           # Halaman login
        /dashboard      # Dashboard
        /pasien         # CRUD pasien
        /prediksi       # Form prediksi
    /Python             # Script ML
        predict.py      # Prediksi diabetes
        train_model.py  # Training model
    /Filters            # Filter auth
    /Database           # Migrations & Seeds
/public                 # Assets public
/writable               # Session, cache, uploads
```

## Instalasi

### 1. Prasyarat
- PHP 8.0+
- Composer
- Python 3.10+
- MySQL/SQLite

### 2. Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install Python dependencies
pip install numpy pandas scikit-learn joblib
```

### 3. Konfigurasi Database
Edit file `.env`:
```env
# Untuk SQLite (development)
database.default.DBDriver = SQLite3
database.default.database = writable/diabetes.db

# Untuk MySQL (production)
# database.default.hostname = localhost
# database.default.database = diabetes_prediction
# database.default.username = root
# database.default.password = password
# database.default.DBDriver = MySQLi
```

### 4. Setup Database
```bash
# Run migrations
php spark migrate

# Run seeders (data dummy)
php spark db:seed PetugasSeeder
php spark db:seed PasienSeeder
```

### 5. Train ML Model
```bash
# Train Random Forest model
python app/Python/train_model.py
```

### 6. Jalankan Aplikasi
```bash
# Start development server
php spark serve

# Akses di browser
# http://localhost:8080
```

## Login Default

### Admin
- Username: `admin`
- Password: `admin123`

### Petugas
- Username: `petugas1`
- Password: `petugas123`

## Flow Prediksi

1. Petugas login ke sistem
2. Pilih menu "Prediksi Diabetes"
3. Pilih pasien dari dropdown
4. Isi 8 fitur input sesuai dataset PIMA
5. Sistem mengirim data ke Python script
6. Random Forest model memprediksi (0/1)
7. Hasil disimpan dan ditampilkan dengan warna:
   - Hijau: Tidak Diabetes (0)
   - Merah: Diabetes (1)

## Metodologi Agile

Proyek dikembangkan menggunakan metodologi Agile dengan 6 sprint:

### Sprint 1: Analisis Kebutuhan
- Analisis kebutuhan sistem
- Studi dataset PIMA Indians Diabetes
- Perancangan arsitektur

### Sprint 2: Desain Arsitektur & UI
- Desain database
- Wireframe UI dengan AdminLTE
- Desain integrasi CI4-Python

### Sprint 3: Implementasi Backend
- Setup CodeIgniter 4
- Implementasi MVC
- Authentication system
- Database migrations

### Sprint 4: Integrasi Python + ML
- Training Random Forest model
- Python prediction script
- Integrasi CI4 ↔ Python
- Testing prediksi

### Sprint 5: Testing
- Unit testing
- Integration testing
- User acceptance testing
- Bug fixing

### Sprint 6: Deployment & Dokumentasi
- Deployment preparation
- User documentation
- System documentation
- Final testing

## Dataset

Menggunakan dataset **Pima Indians Diabetes** dengan 8 fitur:
1. Pregnancies
2. Glucose
3. BloodPressure
4. SkinThickness
5. Insulin
6. BMI
7. DiabetesPedigreeFunction
8. Age

Model Random Forest dilatih dengan semua fitur (Option A).

## Kontribusi

1. Fork repository
2. Create feature branch
3. Commit changes
4. Push to branch
5. Create Pull Request

## Lisensi

Proyek ini dikembangkan untuk tujuan akademik dan penelitian.

## Kontak

Untuk pertanyaan atau masalah, silakan buka issue di repository.

---

**Sistem Prediksi Diabetes** © 2025 - Dibangun dengan CodeIgniter 4, AdminLTE, dan Python Random Forest
