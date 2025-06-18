# API E-Commerce Batik Nusantara

API untuk aplikasi e-commerce Batik Nusantara yang dibangun menggunakan PHP Native dan MySQL.

## Instalasi

### Prasyarat

- PHP 8.0
- MySQL
- Postman (untuk testing API)

### Cara Clone dan Instalasi

1. Clone repository

```bash
git clone https://github.com/NDP4/api_native_sertifikasi2.git
cd api_native_sertifikasi2
```

2. Import database

- Buat database baru dengan nama `batiknusantara`
- Import file SQL yang ada di folder `database/batiknusantara.sql`

3. Konfigurasi database

- Buka file `config/database.php`
- Sesuaikan konfigurasi database:

```php
private $host = "localhost";
private $database_name = "batiknusantara";
private $username = "root";
private $password = "";
```

## Daftar Endpoint API

### 1. Autentikasi

#### Login

```
POST /api/auth.php?action=login
Content-Type: application/json

Request Body:
{
    "email": "user@example.com",
    "password": "password123"
}

Response Success:
{
    "status": true,
    "message": "Login successful",
    "data": {
        "id": 1,
        "nama": "User Name",
        "email": "user@example.com"
    }
}
```

#### Register

```
POST /api/auth.php?action=register
Content-Type: application/json

Request Body:
{
    "nama": "User Name",
    "alamat": "Jl. Example No. 123",
    "kota": "Jakarta",
    "provinsi": "DKI Jakarta",
    "kodepos": "12345",
    "telp": "081234567890",
    "email": "user@example.com",
    "password": "password123"
}

Response Success:
{
    "status": true,
    "message": "Registration successful"
}
```

### 2. Produk

#### Get All Products

```
GET /api/products.php

Response:
{
    "status": true,
    "message": "Success",
    "data": [
        {
            "kode": "BT001",
            "merk": "Batik Solo",
            "kategori": "Batik Tulis",
            "hargajual": 150000,
            "foto": "bt001.jpg",
            "deskripsi": "Batik tulis premium"
        }
    ]
}
```

#### Get Product by ID

```
GET /api/products.php?kode=BT001

Response:
{
    "status": true,
    "message": "Success",
    "data": {
        "kode": "BT001",
        "merk": "Batik Solo",
        "kategori": "Batik Tulis",
        "hargajual": 150000,
        "foto": "bt001.jpg",
        "deskripsi": "Batik tulis premium"
    }
}
```

#### Search Products

```
GET /api/products.php?search=solo

Response: sama seperti Get All Products
```

### 3. Order

#### Calculate Shipping Cost

```
GET /api/orders.php?action=shipping&city_id=151&weight=1000

Response:
{
    "status": true,
    "data": {
        "service": "REG",
        "description": "Layanan Reguler",
        "cost": {
            "value": 44000,
            "etd": "2-3",
            "note": ""
        }
    }
}
```

#### Create Order

```
POST /api/orders.php
Content-Type: application/json

Request Body:
{
    "email": "user@example.com",
    "subtotal": 150000,
    "ongkir": 44000,
    "total_bayar": 194000,
    "alamat_kirim": "Jl. Example No. 123",
    "telp_kirim": "081234567890",
    "kota": "Jakarta",
    "provinsi": "DKI Jakarta",
    "lamakirim": "2-3 hari",
    "kodepos": "12345",
    "metodebayar": 1,
    "items": [
        {
            "kode_brg": "BT001",
            "harga_jual": 150000,
            "qty": 1,
            "bayar": 150000
        }
    ]
}

Response Success:
{
    "status": true,
    "message": "Order created successfully",
    "data": {
        "trans_id": 1
    }
}
```

#### Get Order History

```
GET /api/orders.php?email=user@example.com

Response:
{
    "status": true,
    "message": "Success",
    "data": [
        {
            "trans_id": 1,
            "tgl_order": "2025-06-18",
            "total_bayar": 194000,
            "status": 1
        }
    ]
}
```

#### Get Order Detail

```
GET /api/orders.php?email=user@example.com&trans_id=1

Response:
{
    "status": true,
    "message": "Success",
    "data": {
        "order": {
            "trans_id": 1,
            "tgl_order": "2025-06-18",
            "total_bayar": 194000,
            "status": 1
        },
        "items": [
            {
                "kode_brg": "BT001",
                "harga_jual": 150000,
                "qty": 1,
                "bayar": 150000
            }
        ]
    }
}
```

### 4. Profile

#### Get Profile

```
GET /api/profile.php?id=1

Response:
{
    "status": true,
    "message": "Success",
    "data": {
        "id": 1,
        "nama": "User Name",
        "alamat": "Jl. Example No. 123",
        "kota": "Jakarta",
        "provinsi": "DKI Jakarta",
        "kodepos": "12345",
        "telp": "081234567890",
        "email": "user@example.com",
        "foto": "profile.jpg"
    }
}
```

#### Update Profile

```
PUT /api/profile.php?id=1
Content-Type: application/json

Request Body:
{
    "nama": "Updated Name",
    "alamat": "Jl. New Address",
    "kota": "Bandung",
    "provinsi": "Jawa Barat",
    "kodepos": "40111",
    "telp": "081234567890",
    "password": "newpassword123" // opsional
}

Response:
{
    "status": true,
    "message": "Profile updated successfully"
}
```

## Catatan

- Untuk metode pembayaran:
  - 1 = COD (Cash On Delivery)
  - 0 = Transfer Bank
- Status order:
  - 0 = Pending
  - 1 = Confirmed
  - 2 = Shipped
  - 3 = Delivered
  - 4 = Cancelled

## Cara Testing di Postman

1. Buka Postman
2. Import collection yang disediakan (jika ada) atau buat request baru
3. Atur environment variable base URL: `http://localhost` atau domain yang digunakan
4. Ikuti panduan endpoint di atas untuk testing masing-masing fitur
5. Untuk endpoint yang membutuhkan body request, gunakan format JSON
6. Pastikan header `Content-Type: application/json` sudah ditambahkan untuk request POST/PUT

## Kontribusi

Silakan buat issue atau pull request jika ingin berkontribusi pada project ini.

## Lisensi

MIT License

# Penggunaaan Web Admin Batik

1. Update /admin/config/config.php sesuaikan dengan user, password, dan database
   define('DB_USER', 'root');
   define('DB_PASS', '');
   define('DB_NAME', 'batiknusantara');
2. Cek Web Admin di situs http://localhost/api_native_sertifikasi2/admin/index.php
