# Rencana: Implementasi Auth (Login + Logout) — Livewire 4 Volt

## Konteks
Proyek Laravel 13.29 + Livewire 4 (Volt single-file, prefix `⚡`), daisyUI 5, MySQL.
Belum ada auth scaffolding. `User` model sudah `Authenticatable + HasRoles + SoftDeletes`
dengan cast `password => hashed`. Konvensi render halaman pakai `Route::livewire()` dan
komponen disimpan di `resources/views/{namespace}/⚡{name}.blade.php`.

Pendekatan: **hand-roll auth tanpa dependency baru** (sesuai aturan PROJECT.md) memakai
facade `Auth` (docs Laravel 13: `Auth::attempt` + `session()->regenerate()`, `Auth::logout`
+ `session()->invalidate()` + `regenerateToken()`).

## Keputusan (dari user)
- Scope: **Login + Logout** saja. Register/password-reset/email-verify = out of scope (simpan
  user via seeder/tinker untuk latihan).
- **/admin wajib login**: `auth` middleware; halaman login pakai `guest` middleware.
- Layout auth: **`layouts::auth` baru** (card centered, tanpa navbar marketing/footer).

## Rencana Implementasi (urutan)

### 1. Daftarkan namespace `auth` di Livewire
`config/livewire.php` → tambah ke `component_namespaces`:
```php
'auth' => resource_path('views/auth'),
```
Agar `Route::livewire('/login', 'auth::login')` ter-resolve.

### 2. Layout `resources/views/layouts/auth.blade.php` (baru)
Full HTML doc, minimal: `<html x-data="themeSwitcher" x-init="init()" :data-theme="theme">`,
`<head>` isi Bunny Fonts Outfit + `@vite(['resources/css/app.css','resources/js/app.js'])`
+ `@livewireStyles`, `<body class="...">` berisi container centered (`min-h-screen grid place-items-center`)
dan `{{ $slot }}`, lalu `@livewireScripts`. Tanpa navbar/footer marketing.

### 3. Komponen `resources/views/auth/⚡login.blade.php` (baru, Volt)
```php
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

new class extends Component {
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    public function login(): void {
        $this->validate([
            'email' => ['required','email'],
            'password' => ['required'],
        ]);
        $key = strtolower($this->email).'|'.request()->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $this->addError('email', 'Terlalu banyak percobaan. Coba lagi nanti.');
            return;
        }
        if (Auth::attempt(['email'=>$this->email,'password'=>$this->password], $this->remember)) {
            RateLimiter::clear($key);
            request()->session()->regenerate();
            $this->redirect(route('admin.main'), navigate: true);
        }
        RateLimiter::hit($key);
        $this->addError('email', 'Email atau password salah.');
    }

    public function render() {
        return $this->view()->layout('layouts::auth');
    }
};
```
Markup: daisyUI 5 `card` centered — input `email` (wire:model), input `password`
(wire:model), checkbox `remember` (wire:model), tombol submit `wire:click="login"`,
tampilkan `$errors`/`$getErrorBag`. Pesan error via `@error` atau `$errors->first('email')`.

### 4. Route `routes/web.php`
```php
use App\Http\Controllers\MainController; // hapus jika tidak dipakai
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::livewire('/', 'pages::main')->name('main');

Route::middleware('guest')->group(function () {
    Route::livewire('/login', 'auth::login')->name('login');
});

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
})->name('logout')->middleware('auth');

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::livewire('/', 'admin::main')->name('main');
});
```
Hapus import `MainController` yang orphan (tidak dipakai route lagi).

### 5. Hubungkan tombol logout di admin
- `resources/views/layouts/navbar.blade.php`: ganti `<form method="POST" action="{{ '#' }}"`
  → `action="{{ route('logout') }}"` (baris ~42).
- `resources/views/layouts/sidebar.blade.php`: ganti `action="{{ '#' }}"` → `route('logout')`
  (baris ~64).
- Pastikan kedua form pakai `@csrf` (sudah ada).

### 6. User demo untuk testing
Buat user lewat `php artisan tinker`:
```php
App\Models\User::create(['name'=>'Demo','email'=>'demo@proyek.test','password'=>'password']);
```
(Atau tambahkan ke `Database\Seeders\DatabaseSeeder` jika diinginkan — opsional.)

## Validasi
- `vendor/bin/pint --format agent` (format PHP).
- `php artisan test --compact` — buat test Pest `tests/Feature/AuthTest.php`.
  Catatan: login terjadi via **aksi Livewire**, bukan POST ke `/login`, jadi uji komponen
  dengan `Livewire::test('auth.login')` (nama komponen = namespace `auth` + `login`,
  lihat `component_namespaces` di `config/livewire.php`).

  - `login_page_can_be_rendered`: `GET /login` → `assertStatus(200)` & `assertSee('email')`.
  - `users_can_authenticate`:
    ```php
    $user = User::factory()->create(['password' => 'password']);
    Livewire::test('auth.login')
        ->set(['email' => $user->email, 'password' => 'password'])
        ->call('login')
        ->assertRedirectToRoute('admin.main');
    $this->assertAuthenticated();
    ```
  - `users_cannot_authenticate_with_invalid_password`:
    ```php
    $user = User::factory()->create(['password' => 'password']);
    Livewire::test('auth.login')
        ->set(['email' => $user->email, 'password' => 'wrong'])
        ->call('login')
        ->assertHasErrors('email')
        ->assertNoRedirect();
    $this->assertGuest();
    ```
  - `authenticated_users_cannot_view_login`: `actingAs($user)->get('/login')` → `assertRedirect('/')`
    (`guest` middleware default redirect ke `/`).
  - `guests_cannot_view_admin`: `GET /admin` tanpa auth → `assertRedirect('/login')`
    (middleware `auth`).
  - `users_can_logout`: `actingAs($user)->post('/logout')` → `assertRedirect('/login')` + `assertGuest()`.
- Manual: buka `http://proyek.test/login`, login demo → redirect ke `/admin`; klik Logout di
  navbar/sidebar → kembali ke `/login`; akses `/admin` tanpa login → redirect `/login`.

## Asumsi
- Alias middleware `auth` dan `guest` tersedia secara default di Laravel 13 (di-register oleh
  `Illuminate\Foundation\Configuration\Middleware` meski `bootstrap/app.php` kosong). Jika
  tidak, daftarkan manual di `bootstrap/app.php`.
- Komponen `auth.login` ter-test via `Livewire::test('auth.login')` karena `auth` sudah masuk
  `component_namespaces`.
- `request()->ip()` di dalam Volt valid (tes memakai IP `127.0.0.1`).

## Risiko / Catatan
- `guest` middleware default redirect ke `/`; setelah login sukses kita pakai
  `redirect()->intended(route('admin.main'))` — aman.
- Tidak mengubah `composer.json`/`package.json` (patuh PROJECT.md).
- Jika UI tidak muncul: jalankan `npm run dev` / `npm run build` (Vite).
- Register belum dibuat — user hanya bisa dibuat via tinker/seeder sampai fitur register ditambah.
- Penguatan opsional (di luar scope inti): tambahkan link "Masuk" di navbar `layouts/public.blade.php`
  (ganti tombol "Mulai Gratis" `#cta` → `route('login')`) agar user tahu ke mana login.

## Open question (opsional, di luar scope)
Mau ditambahkan halaman **Register** (Volt `auth::register`) agar user bisa daftar sendiri?
