# 🛍️ Website E-Commerce Laravel 10

Proyek ini merupakan sistem **E-Commerce lengkap berbasis Laravel 10**, dengan tampilan modern, fitur lengkap untuk pengguna dan admin, serta mendukung integrasi pembayaran online dan login sosial media.  
Website ini dirancang agar mudah digunakan, aman, dan efisien untuk pengelolaan toko online secara profesional.

---

## 🎯 Fitur Utama

### 🔹 Tampilan Pengguna (Frontend)
- Mendukung **Progressive Web App (PWA)**
- Desain **modern dan responsif**
- Fitur **keranjang belanja**, wishlist, dan pelacakan pesanan
- Login menggunakan **Google, Facebook, GitHub**
- Sistem **ulasan dan komentar produk**

### 🔹 Dashboard Admin
- Manajemen **Role & Permission**
- Statistik penjualan **real-time**
- Pengelolaan produk, kategori, pesanan, dan kupon
- Notifikasi dan pesan langsung
- Fitur blog dan manajemen banner

### 🔹 Dashboard Pengguna
- Melihat **riwayat dan status pesanan**
- Memberikan **ulasan & komentar**
- Mengubah profil dan preferensi akun

## 🛠️ Installation Guide

### 🔹 **Step 1: Clone the Repository**
```sh
git clone https://github.com/Prajwal100/Complete-Ecommerce-in-laravel-10.git
cd Complete-Ecommerce-in-laravel-10
```

### 🔹 **Step 2: Install Depensi**
```sh
composer install
npm install
```

### 🔹 **Step 3: Setup Environment**
```sh
cp .env.example .env
php artisan key:generate
```
Update `.env` with database credentials.

### 🔹 **Step 4: Database Configuration**
```sh
php artisan migrate --seed
```

### 🔹 **Step 5: Setup Storage**
```sh
php artisan storage:link
```

### 🔹 **Step 6: Run the Application**
```sh
php artisan serve
```
🔗 Open `http://localhost:8000`

### **Admin Login Credentials:**
📧 **Email:** `admin@gmail.com`  
🔑 **Password:** `1111`

---

## 📜 License
🔹 This project is **MIT Licensed**!

⭐ **Jika Anda merasa proyek ini bermanfaat, jangan lupa beri bintang!** ⭐

