# Aturan Arsitektur Proyek (Laravel REST API + Vue 3 SPA)

## Backend (Folder `/backend`)
- Gunakan Laravel sebagai REST API murni.
- Semua route wajib berada di `routes/api.php` dengan prefix `/api/v1/`.
- Controller WAJIB mengembalikan JSON: `return response()->json(['data' => $data, 'message' => $msg], 200);`.
- Wajib gunakan Laravel Form Request untuk validasi data input.
- Penanganan error validasi harus mengembalikan HTTP Status 422 JSON.

## Frontend (Folder `/frontend`)
- Gunakan Vue 3 dengan Composition API (`<script setup>`).
- Komunikasi HTTP ke Backend wajib menggunakan Axios instance yang sudah terkonfigurasi dengan `baseURL: 'http://localhost:8000/api/v1'`.
- Routing antar halaman menggunakan Vue Router.
- Styling menggunakan utility class Tailwind CSS.