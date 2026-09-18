# Tugas Web Pertemuan 7 — Login & Register

## Deskripsi

Implementasi sistem **Login/Register menggunakan PHP Native** dengan **JSON sebagai media penyimpanan data**. Tugas berfokus pada penerapan form handling, validasi input, autentikasi, session, cookie, serta dasar keamanan pada aplikasi web.

## Requirement

| Requirement | Implementasi |
|---|---|
| Registrasi pengguna | `register.php` |
| Validasi nama, email, dan password | PHP |
| Validasi email | `filter_var()` |
| Password hashing | `password_hash()` |
| Verifikasi password | `password_verify()` |
| Penyimpanan data | `users.json` |
| Cek email duplikat | PHP + JSON |
| Login | `login.php` |
| Session | PHP Session |
| Proteksi dashboard | Pemeriksaan session |
| Logout | `session_destroy()` |
| Sanitasi output | `htmlspecialchars()` |
| Pesan error/success | PHP |
| Remember Me | Cookie + token hash |
| Edit Profile | `edit-profile.php` |
| Responsive design | CSS Media Query |

## Teknologi

- PHP Native
- HTML5
- CSS3
- JSON
- Apache
- Laragon
- Visual Studio Code
- Git & GitHub

## Struktur Project

```text
TugasWeb-Pertemuan7-LoginRegister/
├── css/
│   └── style.css
├── index.php
├── register.php
├── login.php
├── dashboard.php
├── profile.php
├── edit-profile.php
├── logout.php
├── users.json
└── README.md
```

## Alur Sistem

```text
Register → Validasi → Hash Password → users.json
                                      ↓
Login → Verifikasi Password → Session → Dashboard
                                      ↓
                              Profile / Edit Profile
                                      ↓
                                    Logout
```

## Keamanan Dasar

Implementasi menerapkan:

- Validasi format email dengan `filter_var()`
- Password hashing dengan `password_hash()`
- Verifikasi password dengan `password_verify()`
- Regenerasi session ID setelah login
- Sanitasi output dengan `htmlspecialchars()`
- Token acak untuk Remember Me
- Hash token Remember Me dalam `users.json`
- Cookie `HttpOnly` dan `SameSite=Lax`
- Proteksi halaman berdasarkan session

## Menjalankan Project

1. Letakkan folder project di `C:\laragon\www\`.
2. Jalankan **Apache** melalui Laragon.
3. Buka browser.
4. Akses:

```text
http://localhost/TugasWeb-Pertemuan7-LoginRegister/
```

## Requirement Checklist

- [x] Registrasi
- [x] Validasi input
- [x] Validasi email dengan `filter_var()`
- [x] Password hashing
- [x] Penyimpanan JSON
- [x] Cek email duplikat
- [x] Login dan session
- [x] Proteksi dashboard
- [x] Logout
- [x] `htmlspecialchars()`
- [x] Pesan error dan success
- [x] Remember Me
- [x] Edit Profile
- [x] Responsive design

## Informasi Tugas

**Mata Kuliah:** Pemrograman Web  
**Pertemuan:** 7  
**Nama:** Fatih Taqiyyuddin  
**Repository:** `TugasWeb-Pertemuan7-LoginRegister`

## Author

**Fatih Taqiyyuddin**
