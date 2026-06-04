# API Documentation - Final Project

**Base URL:** `http://localhost:8000/api`

Semua endpoint bertanda 🔒 membutuhkan header:

```
Authorization: Bearer {token}
```

---

## 📌 Daftar Isi

### Auth

- [POST /auth/register](#post-authregister)
- [POST /auth/login](#post-authlogin)
- [POST /auth/logout](#post-authlogout-)

### Kategori

- [GET /kategori](#get-kategori-)
- [GET /kategori/{id}](#get-kategoriid-)
- [POST /kategori](#post-kategori-)
- [PATCH /kategori/{id}](#patch-kategoriid-)
- [DELETE /kategori/{id}](#delete-kategoriid-)

### Alat

- [GET /alat](#get-alat-)
- [GET /alat/{id}](#get-alatid-)
- [POST /alat](#post-alat-)
- [PATCH /alat/{id}](#patch-alatid-)
- [DELETE /alat/{id}](#delete-alatid-)

### Pelanggan

- [GET /pelanggan](#get-pelanggan-)
- [GET /pelanggan/{id}](#get-pelangganid-)
- [POST /pelanggan](#post-pelanggan-)
- [PATCH /pelanggan/{id}](#patch-pelangganid-)
- [DELETE /pelanggan/{id}](#delete-pelangganid-)

### Pelanggan Data

- [GET /pelanggan-data](#get-pelanggan-data-)
- [GET /pelanggan-data/{id}](#get-pelanggan-dataid-)
- [POST /pelanggan-data](#post-pelanggan-data-)
- [PATCH /pelanggan-data/{id}](#patch-pelanggan-dataid-)
- [DELETE /pelanggan-data/{id}](#delete-pelanggan-dataid-)

### Penyewaan

- [GET /penyewaan](#get-penyewaan-)
- [GET /penyewaan/{id}](#get-penyewaanid-)
- [POST /penyewaan](#post-penyewaan-)
- [PATCH /penyewaan/{id}](#patch-penyewaanid-)
- [DELETE /penyewaan/{id}](#delete-penyewaanid-)

### Penyewaan Detail

- [GET /penyewaan-detail](#get-penyewaan-detail-)
- [GET /penyewaan-detail/{id}](#get-penyewaan-detailid-)
- [POST /penyewaan-detail](#post-penyewaan-detail-)
- [PATCH /penyewaan-detail/{id}](#patch-penyewaan-detailid-)
- [DELETE /penyewaan-detail/{id}](#delete-penyewaan-detailid-)

---

## 🔑 Auth

### POST /auth/register

**Public** — Tidak perlu token.

| Field          | Type   | Required | Keterangan             |
| -------------- | ------ | -------- | ---------------------- |
| admin_username | string | Ya       | Maks 50 karakter, unik |
| admin_password | string | Ya       | Min 6 karakter         |

**Request Body:**

```json
{
    "admin_username": "admin",
    "admin_password": "password123"
}
```

**Response 201 — Sukses:**

```json
{
    "success": true,
    "message": "Successfully registered.",
    "data": {
        "admin_id": 1,
        "admin_username": "admin",
        "created_at": "2026-06-03T00:00:00.000000Z"
    }
}
```

**Response 422 — Validasi Gagal:**

```json
{
    "success": false,
    "message": "Failed to register. Please check your input data",
    "data": null,
    "errors": [
        { "admin_username": "The admin username has already been taken." }
    ]
}
```

---

### POST /auth/login

**Public** — Tidak perlu token.

| Field          | Type   | Required | Keterangan |
| -------------- | ------ | -------- | ---------- |
| admin_username | string | Ya       |            |
| admin_password | string | Ya       |            |

**Request Body:**

```json
{
    "admin_username": "admin",
    "admin_password": "password123"
}
```

**Response 200 — Sukses:**

```json
{
    "success": true,
    "message": "Successfully logged in.",
    "data": {
        "admin_id": 1,
        "admin_username": "admin"
    },
    "accesstoken": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."
}
```

**Response 400 — Username/Password Salah:**

```json
{
    "success": false,
    "message": "Failed to login. Wrong username or password",
    "data": null
}
```

---

### POST /auth/logout 🔒

Tidak membutuhkan body.

**Response 200 — Sukses:**

```json
{
    "success": true,
    "message": "Successfully logged out."
}
```

---

## 🗂️ Kategori

### GET /kategori 🔒

**Response 200 — Ada Data:**

```json
{
    "success": true,
    "message": "Successfully get kategori data",
    "data": [
        {
            "kategori_id": 1,
            "kategori_nama": "Tenda",
            "created_at": "2026-06-03T00:00:00.000000Z",
            "updated_at": "2026-06-03T00:00:00.000000Z"
        }
    ]
}
```

**Response 200 — Data Kosong:**

```json
{
    "success": true,
    "message": "Successfully get kategori data",
    "data": null
}
```

---

### GET /kategori/{id} 🔒

**Response 200 — Sukses:**

```json
{
    "success": true,
    "message": "Successfully get kategori data",
    "data": {
        "kategori_id": 1,
        "kategori_nama": "Tenda"
    }
}
```

**Response 404 — Tidak Ditemukan:**

```json
{
    "success": false,
    "message": "Kategori tidak ditemukan!",
    "data": null
}
```

---

### POST /kategori 🔒

| Field         | Type   | Required | Keterangan        |
| ------------- | ------ | -------- | ----------------- |
| kategori_nama | string | Ya       | Maks 100 karakter |

**Request Body:**

```json
{
    "kategori_nama": "Tenda"
}
```

**Response 201 — Sukses:**

```json
{
    "success": true,
    "message": "Berhasil menambahkan data kategori!",
    "data": {
        "kategori_id": 1,
        "kategori_nama": "Tenda"
    }
}
```

**Response 422 — Validasi Gagal:**

```json
{
    "success": false,
    "message": "Gagal menambahkan data kategori!",
    "data": null,
    "errors": [{ "kategori_nama": "Nama kategori wajib diisi" }]
}
```

---

### PATCH /kategori/{id} 🔒

| Field         | Type   | Required | Keterangan        |
| ------------- | ------ | -------- | ----------------- |
| kategori_nama | string | Opsional | Maks 100 karakter |

**Request Body:**

```json
{
    "kategori_nama": "Sleeping Bag"
}
```

**Response 200 — Sukses:**

```json
{
    "success": true,
    "message": "Berhasil mengubah data kategori!",
    "data": {
        "kategori_id": 1,
        "kategori_nama": "Sleeping Bag"
    }
}
```

---

### DELETE /kategori/{id} 🔒

**Response 200 — Sukses:**

```json
{
    "success": true,
    "message": "Berhasil menghapus data kategori!",
    "data": null
}
```

---

## 🔧 Alat

### GET /alat 🔒

**Response 200 — Ada Data:**

```json
{
    "success": true,
    "message": "Successfully get alat data",
    "data": [
        {
            "alat_id": 1,
            "alat_kategori_id": 1,
            "alat_nama": "Tenda Dome 2P",
            "alat_deskripsi": "Tenda untuk 2 orang",
            "alat_hargaperhari": 50000,
            "alat_stok": 10,
            "kategori": {
                "kategori_id": 1,
                "kategori_nama": "Tenda"
            }
        }
    ]
}
```

**Response 200 — Data Kosong:**

```json
{
    "success": true,
    "message": "Successfully get alat data",
    "data": null
}
```

---

### GET /alat/{id} 🔒

**Response 200 — Sukses:**

```json
{
    "success": true,
    "message": "Successfully get alat data",
    "data": {
        "alat_id": 1,
        "alat_kategori_id": 1,
        "alat_nama": "Tenda Dome 2P",
        "alat_deskripsi": "Tenda untuk 2 orang",
        "alat_hargaperhari": 50000,
        "alat_stok": 10
    }
}
```

**Response 404:**

```json
{
    "success": false,
    "message": "Alat tidak ditemukan!",
    "data": null
}
```

---

### POST /alat 🔒

| Field             | Type    | Required | Keterangan        |
| ----------------- | ------- | -------- | ----------------- |
| alat_kategori_id  | integer | Ya       | ID kategori valid |
| alat_nama         | string  | Ya       | Maks 150 karakter |
| alat_deskripsi    | string  | Ya       |                   |
| alat_hargaperhari | integer | Ya       | Min 0             |
| alat_stok         | integer | Ya       | Min 0             |

**Request Body:**

```json
{
    "alat_kategori_id": 1,
    "alat_nama": "Tenda Dome 2P",
    "alat_deskripsi": "Tenda untuk 2 orang, waterproof",
    "alat_hargaperhari": 50000,
    "alat_stok": 10
}
```

**Response 201 — Sukses:**

```json
{
    "success": true,
    "message": "Berhasil menambahkan data alat!",
    "data": {
        "alat_id": 1,
        "alat_kategori_id": 1,
        "alat_nama": "Tenda Dome 2P",
        "alat_deskripsi": "Tenda untuk 2 orang, waterproof",
        "alat_hargaperhari": 50000,
        "alat_stok": 10
    }
}
```

**Response 422 — Validasi Gagal:**

```json
{
    "success": false,
    "message": "Gagal menambahkan data alat!",
    "data": null,
    "errors": [
        { "alat_nama": "Nama alat wajib diisi" },
        { "alat_hargaperhari": "Harga per hari wajib diisi" }
    ]
}
```

---

### PATCH /alat/{id} 🔒

| Field             | Type    | Required | Keterangan        |
| ----------------- | ------- | -------- | ----------------- |
| alat_kategori_id  | integer | Opsional | ID kategori valid |
| alat_nama         | string  | Opsional | Maks 150 karakter |
| alat_deskripsi    | string  | Opsional |                   |
| alat_hargaperhari | integer | Opsional | Min 0             |
| alat_stok         | integer | Opsional | Min 0             |

**Request Body:**

```json
{
    "alat_stok": 15,
    "alat_hargaperhari": 60000
}
```

**Response 200 — Sukses:**

```json
{
    "success": true,
    "message": "Berhasil mengubah data alat!",
    "data": {
        "alat_id": 1,
        "alat_nama": "Tenda Dome 2P",
        "alat_hargaperhari": 60000,
        "alat_stok": 15
    }
}
```

---

### DELETE /alat/{id} 🔒

**Response 200 — Sukses:**

```json
{
    "success": true,
    "message": "Berhasil menghapus data alat!",
    "data": null
}
```

---

## 👤 Pelanggan

### GET /pelanggan 🔒

**Response 200 — Ada Data:**

```json
{
    "success": true,
    "message": "Successfully get pelanggan data",
    "data": [
        {
            "pelanggan_id": 1,
            "pelanggan_nama": "Budi Santoso",
            "pelanggan_alamat": "Jl. Merdeka No. 1, Jakarta",
            "pelanggan_notelp": "081234567890",
            "pelanggan_email": "budi@email.com",
            "pelanggan_data": {
                "pelanggan_data_id": 1,
                "pelanggan_data_jenis": "KTP",
                "pelanggan_data_file": "pelanggan_data/ktp.jpg"
            },
            "penyewaan": []
        }
    ]
}
```

**Response 200 — Data Kosong:**

```json
{
    "success": true,
    "message": "Successfully get pelanggan data",
    "data": null
}
```

---

### GET /pelanggan/{id} 🔒

**Response 200 — Sukses:**

```json
{
    "success": true,
    "message": "Successfully get pelanggan data",
    "data": {
        "pelanggan_id": 1,
        "pelanggan_nama": "Budi Santoso",
        "pelanggan_email": "budi@email.com"
    }
}
```

**Response 404:**

```json
{
    "success": false,
    "message": "Pelanggan tidak ditemukan!",
    "data": null
}
```

---

### POST /pelanggan 🔒

| Field            | Type   | Required | Keterangan                    |
| ---------------- | ------ | -------- | ----------------------------- |
| pelanggan_nama   | string | Ya       | Maks 150 karakter             |
| pelanggan_alamat | string | Ya       | Maks 200 karakter             |
| pelanggan_notelp | string | Ya       | Maks 13 karakter, hanya angka |
| pelanggan_email  | string | Ya       | Format email valid, unik      |

**Request Body:**

```json
{
    "pelanggan_nama": "Budi Santoso",
    "pelanggan_alamat": "Jl. Merdeka No. 1, Jakarta",
    "pelanggan_notelp": "081234567890",
    "pelanggan_email": "budi@email.com"
}
```

**Response 201 — Sukses:**

```json
{
    "success": true,
    "message": "Berhasil menambahkan data pelanggan!",
    "data": {
        "pelanggan_id": 1,
        "pelanggan_nama": "Budi Santoso",
        "pelanggan_alamat": "Jl. Merdeka No. 1, Jakarta",
        "pelanggan_notelp": "081234567890",
        "pelanggan_email": "budi@email.com"
    }
}
```

**Response 422 — Validasi Gagal:**

```json
{
    "success": false,
    "message": "Gagal menambahkan data pelanggan!",
    "data": null,
    "errors": [
        { "pelanggan_email": "Email sudah terdaftar" },
        { "pelanggan_notelp": "Nomor telepon hanya boleh berisi angka" }
    ]
}
```

---

### PATCH /pelanggan/{id} 🔒

| Field            | Type   | Required | Keterangan                    |
| ---------------- | ------ | -------- | ----------------------------- |
| pelanggan_nama   | string | Opsional | Maks 150 karakter             |
| pelanggan_alamat | string | Opsional | Maks 200 karakter             |
| pelanggan_notelp | string | Opsional | Maks 13 karakter, hanya angka |
| pelanggan_email  | string | Opsional | Format email valid, unik      |

**Request Body:**

```json
{
    "pelanggan_alamat": "Jl. Sudirman No. 5, Jakarta",
    "pelanggan_notelp": "089876543210"
}
```

**Response 200 — Sukses:**

```json
{
    "success": true,
    "message": "Berhasil mengubah data pelanggan!",
    "data": {
        "pelanggan_id": 1,
        "pelanggan_nama": "Budi Santoso",
        "pelanggan_alamat": "Jl. Sudirman No. 5, Jakarta",
        "pelanggan_notelp": "089876543210",
        "pelanggan_email": "budi@email.com"
    }
}
```

---

### DELETE /pelanggan/{id} 🔒

**Response 200 — Sukses:**

```json
{
    "success": true,
    "message": "Berhasil menghapus data pelanggan!",
    "data": null
}
```

---

## 🪪 Pelanggan Data

### GET /pelanggan-data 🔒

**Response 200 — Ada Data:**

```json
{
    "success": true,
    "message": "Successfully get pelanggan data",
    "data": [
        {
            "pelanggan_data_id": 1,
            "pelanggan_data_pelanggan_id": 1,
            "pelanggan_data_jenis": "KTP",
            "pelanggan_data_file": "pelanggan_data/ktp.jpg",
            "pelanggan": {
                "pelanggan_id": 1,
                "pelanggan_nama": "Budi Santoso"
            }
        }
    ]
}
```

---

### GET /pelanggan-data/{id} 🔒

**Response 200 — Sukses:**

```json
{
    "success": true,
    "message": "Successfully get pelanggan data",
    "data": {
        "pelanggan_data_id": 1,
        "pelanggan_data_pelanggan_id": 1,
        "pelanggan_data_jenis": "KTP",
        "pelanggan_data_file": "pelanggan_data/ktp.jpg"
    }
}
```

---

### POST /pelanggan-data 🔒

> ⚠️ Gunakan **`multipart/form-data`** karena ada upload file, bukan JSON.

| Field                       | Type    | Required | Keterangan                           |
| --------------------------- | ------- | -------- | ------------------------------------ |
| pelanggan_data_pelanggan_id | integer | Ya       | ID pelanggan valid, belum punya data |
| pelanggan_data_jenis        | string  | Ya       | `KTP` atau `SIM`                     |
| pelanggan_data_file         | file    | Ya       | Format jpg/jpeg/png, maks 2MB        |

**Response 201 — Sukses:**

```json
{
    "success": true,
    "message": "Berhasil menambahkan data pelanggan!",
    "data": {
        "pelanggan_data_id": 1,
        "pelanggan_data_pelanggan_id": 1,
        "pelanggan_data_jenis": "KTP",
        "pelanggan_data_file": "pelanggan_data/abc123.jpg"
    }
}
```

**Response 422 — Validasi Gagal:**

```json
{
    "success": false,
    "message": "Gagal menambahkan data pelanggan!",
    "data": null,
    "errors": [
        {
            "pelanggan_data_file": "File harus memiliki format .jpg, .png, atau .jpeg"
        }
    ]
}
```

---

### PATCH /pelanggan-data/{id} 🔒

> ⚠️ Gunakan **`multipart/form-data`** jika mengganti file.

| Field                | Type   | Required | Keterangan                    |
| -------------------- | ------ | -------- | ----------------------------- |
| pelanggan_data_jenis | string | Opsional | `KTP` atau `SIM`              |
| pelanggan_data_file  | file   | Opsional | Format jpg/jpeg/png, maks 2MB |

**Response 200 — Sukses:**

```json
{
    "success": true,
    "message": "Berhasil mengubah data pelanggan!",
    "data": {
        "pelanggan_data_id": 1,
        "pelanggan_data_jenis": "SIM",
        "pelanggan_data_file": "pelanggan_data/sim_baru.jpg"
    }
}
```

---

### DELETE /pelanggan-data/{id} 🔒

**Response 200 — Sukses:**

```json
{
    "success": true,
    "message": "Berhasil menghapus data pelanggan!",
    "data": null
}
```

---

## 📋 Penyewaan

### GET /penyewaan 🔒

**Response 200 — Ada Data:**

```json
{
    "success": true,
    "message": "Successfully get penyewaan data",
    "data": [
        {
            "penyewaan_id": 1,
            "penyewaan_pelanggan_id": 1,
            "penyewaan_tglsewa": "2026-06-03",
            "penyewaan_tglkembali": "2026-06-05",
            "penyewaan_sttspembayaran": "Belum Dibayar",
            "penyewaan_sttskembali": "Belum Kembali",
            "penyewaan_totalharga": 150000,
            "pelanggan": {
                "pelanggan_id": 1,
                "pelanggan_nama": "Budi Santoso"
            },
            "penyewaan_detail": []
        }
    ]
}
```

---

### GET /penyewaan/{id} 🔒

**Response 200 — Sukses:**

```json
{
    "success": true,
    "message": "Successfully get penyewaan data",
    "data": {
        "penyewaan_id": 1,
        "penyewaan_tglsewa": "2026-06-03",
        "penyewaan_tglkembali": "2026-06-05",
        "penyewaan_totalharga": 150000
    }
}
```

---

### POST /penyewaan 🔒

| Field                    | Type    | Required | Keterangan                                                 |
| ------------------------ | ------- | -------- | ---------------------------------------------------------- |
| penyewaan_pelanggan_id   | integer | Ya       | ID pelanggan valid                                         |
| penyewaan_tglsewa        | date    | Ya       | Format `YYYY-MM-DD`                                        |
| penyewaan_tglkembali     | date    | Ya       | Format `YYYY-MM-DD`, tidak boleh sebelum tgl sewa          |
| penyewaan_sttspembayaran | string  | Opsional | `Lunas`, `Belum Dibayar`, `DP`. Default: `Belum Dibayar`   |
| penyewaan_sttskembali    | string  | Opsional | `Sudah Kembali`, `Belum Kembali`. Default: `Belum Kembali` |
| penyewaan_totalharga     | integer | Ya       | Min 0                                                      |

**Request Body:**

```json
{
    "penyewaan_pelanggan_id": 1,
    "penyewaan_tglsewa": "2026-06-03",
    "penyewaan_tglkembali": "2026-06-05",
    "penyewaan_sttspembayaran": "DP",
    "penyewaan_totalharga": 150000
}
```

**Response 201 — Sukses:**

```json
{
    "success": true,
    "message": "Berhasil menambahkan data penyewaan!",
    "data": {
        "penyewaan_id": 1,
        "penyewaan_pelanggan_id": 1,
        "penyewaan_tglsewa": "2026-06-03",
        "penyewaan_tglkembali": "2026-06-05",
        "penyewaan_sttspembayaran": "DP",
        "penyewaan_sttskembali": "Belum Kembali",
        "penyewaan_totalharga": 150000
    }
}
```

**Response 422 — Validasi Gagal:**

```json
{
    "success": false,
    "message": "Gagal menambahkan data penyewaan!",
    "data": null,
    "errors": [
        {
            "penyewaan_tglkembali": "Tanggal kembali tidak boleh sebelum tanggal sewa"
        }
    ]
}
```

---

### PATCH /penyewaan/{id} 🔒

| Field                    | Type    | Required | Keterangan                       |
| ------------------------ | ------- | -------- | -------------------------------- |
| penyewaan_pelanggan_id   | integer | Opsional | ID pelanggan valid               |
| penyewaan_tglsewa        | date    | Opsional | Format `YYYY-MM-DD`              |
| penyewaan_tglkembali     | date    | Opsional | Format `YYYY-MM-DD`              |
| penyewaan_sttspembayaran | string  | Opsional | `Lunas`, `Belum Dibayar`, `DP`   |
| penyewaan_sttskembali    | string  | Opsional | `Sudah Kembali`, `Belum Kembali` |
| penyewaan_totalharga     | integer | Opsional | Min 0                            |

**Request Body:**

```json
{
    "penyewaan_sttspembayaran": "Lunas",
    "penyewaan_sttskembali": "Sudah Kembali"
}
```

**Response 200 — Sukses:**

```json
{
    "success": true,
    "message": "Berhasil mengubah data penyewaan!",
    "data": {
        "penyewaan_id": 1,
        "penyewaan_sttspembayaran": "Lunas",
        "penyewaan_sttskembali": "Sudah Kembali"
    }
}
```

---

### DELETE /penyewaan/{id} 🔒

**Response 200 — Sukses:**

```json
{
    "success": true,
    "message": "Berhasil menghapus data penyewaan!",
    "data": null
}
```

---

## 📦 Penyewaan Detail

### GET /penyewaan-detail 🔒

**Response 200 — Ada Data:**

```json
{
    "success": true,
    "message": "Successfully get penyewaan detail data",
    "data": [
        {
            "penyewaan_detail_id": 1,
            "penyewaan_detail_penyewaan_id": 1,
            "penyewaan_detail_alat_id": 1,
            "penyewaan_detail_jumlah": 2,
            "penyewaan_detail_subharga": 100000,
            "penyewaan": {
                "penyewaan_id": 1,
                "penyewaan_tglsewa": "2026-06-03"
            },
            "alat": { "alat_id": 1, "alat_nama": "Tenda Dome 2P" }
        }
    ]
}
```

---

### GET /penyewaan-detail/{id} 🔒

**Response 200 — Sukses:**

```json
{
    "success": true,
    "message": "Successfully get penyewaan detail data",
    "data": {
        "penyewaan_detail_id": 1,
        "penyewaan_detail_penyewaan_id": 1,
        "penyewaan_detail_alat_id": 1,
        "penyewaan_detail_jumlah": 2,
        "penyewaan_detail_subharga": 100000
    }
}
```

---

### POST /penyewaan-detail 🔒

| Field                         | Type    | Required | Keterangan         |
| ----------------------------- | ------- | -------- | ------------------ |
| penyewaan_detail_penyewaan_id | integer | Ya       | ID penyewaan valid |
| penyewaan_detail_alat_id      | integer | Ya       | ID alat valid      |
| penyewaan_detail_jumlah       | integer | Ya       | Min 1              |
| penyewaan_detail_subharga     | integer | Ya       | Min 0              |

**Request Body:**

```json
{
    "penyewaan_detail_penyewaan_id": 1,
    "penyewaan_detail_alat_id": 1,
    "penyewaan_detail_jumlah": 2,
    "penyewaan_detail_subharga": 100000
}
```

**Response 201 — Sukses:**

```json
{
    "success": true,
    "message": "Berhasil menambahkan data detail penyewaan!",
    "data": {
        "penyewaan_detail_id": 1,
        "penyewaan_detail_penyewaan_id": 1,
        "penyewaan_detail_alat_id": 1,
        "penyewaan_detail_jumlah": 2,
        "penyewaan_detail_subharga": 100000
    }
}
```

**Response 422 — Validasi Gagal:**

```json
{
    "success": false,
    "message": "Gagal menambahkan data detail penyewaan!",
    "data": null,
    "errors": [{ "penyewaan_detail_jumlah": "Jumlah minimal 1" }]
}
```

---

### PATCH /penyewaan-detail/{id} 🔒

| Field                         | Type    | Required | Keterangan         |
| ----------------------------- | ------- | -------- | ------------------ |
| penyewaan_detail_penyewaan_id | integer | Opsional | ID penyewaan valid |
| penyewaan_detail_alat_id      | integer | Opsional | ID alat valid      |
| penyewaan_detail_jumlah       | integer | Opsional | Min 1              |
| penyewaan_detail_subharga     | integer | Opsional | Min 0              |

**Request Body:**

```json
{
    "penyewaan_detail_jumlah": 3,
    "penyewaan_detail_subharga": 150000
}
```

**Response 200 — Sukses:**

```json
{
    "success": true,
    "message": "Berhasil mengubah data detail penyewaan!",
    "data": {
        "penyewaan_detail_id": 1,
        "penyewaan_detail_jumlah": 3,
        "penyewaan_detail_subharga": 150000
    }
}
```

---

### DELETE /penyewaan-detail/{id} 🔒

**Response 200 — Sukses:**

```json
{
    "success": true,
    "message": "Berhasil menghapus data detail penyewaan!",
    "data": null
}
```

---

## ⚠️ Response Error Umum

**Response 500 — Server Error:**

```json
{
    "success": false,
    "message": "There error in Internal Server",
    "data": null,
    "errors": "SQLSTATE[42S02]: Base table or view not found..."
}
```

**Response 401 — Unauthenticated:**

```json
{
    "message": "Unauthenticated."
}
```

---

> **Catatan:**
>
> - Format tanggal: `YYYY-MM-DD`
> - Upload file wajib pakai `multipart/form-data`, bukan JSON
> - Jalankan `php artisan storage:link` agar file bisa diakses via URL publik
