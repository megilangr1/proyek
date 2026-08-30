# Refactor Volt SFC → Livewire Class Components (v3-style)

## Context
Project currently uses Livewire 4 **Volt single-file components** (files prefixed `⚡`) for every
page. The user wants to move back to **class-based components** (separate `.php` class + `.blade.php`
view), i.e. the Livewire v3 mental model. `config/livewire.php` is already adjusted:

- `make_command.type => 'class'`
- `class_namespace => 'App\\Livewire'`
- `class_path => app_path('Livewire')`
- `view_path => resource_path('views/livewire')`

Volt package stays installed (removing a dependency needs approval) — we just stop using SFCs.

## Components to create (via `php artisan make:livewire`)
Class name → view file → layout (bound with `#[Layout(...)]`, dot notation, standard Blade view).

| Class | View | Route | Layout |
|-------|------|-------|--------|
| `App\Livewire\Pages\Main` | `resources/views/livewire/pages/main.blade.php` | `/` (name `main`) | `layouts.public` |
| `App\Livewire\Auth\Login` | `resources/views/livewire/auth/login.blade.php` | `/login` (name `login`) | `layouts.auth` |
| `App\Livewire\Admin\Main` | `resources/views/livewire/admin/main.blade.php` | `/admin` (name `admin.main`) | `layouts.app` |
| `App\Livewire\Admin\MasterData\Pengguna\MainIndex` | `resources/views/livewire/admin/master-data/pengguna/main-index.blade.php` | `/admin/master-data/pengguna` (name `admin.pengguna.index`) | `layouts.app` |

Use explicit `#[Layout('layouts.x')]` on every class (don't rely on the `component_layout`
config default or Volt `::` namespaces) so routing is unambiguous.

## Steps

1. **Scaffold** (each generates class + empty view, FQCN autodiscovered under `App\Livewire`):
   ```bash
   php artisan make:livewire Pages.Main
   php artisan make:livewire Auth.Login
   php artisan make:livewire Admin.Main
   php artisan make:livewire Admin.MasterData.Pengguna.MainIndex
   php artisan livewire:discover
   ```

2. **Move markup** — copy the Blade body (strip the `<?php new class... ?>` PHP block and the
   closing `};`) from each old ⚡ SFC into its new view file, unchanged:
   - `⚡main.blade.php` (pages) → `livewire/pages/main.blade.php`
   - `⚡login.blade.php` (auth) → `livewire/auth/login.blade.php`
   - `⚡main.blade.php` (admin) → `livewire/admin/main.blade.php`
   - `⚡main-index.blade.php` (pengguna) → `livewire/admin/master-data/pengguna/main-index.blade.php`

3. **Move logic** into each class (keep behavior identical):
   - `Pages\Main`: empty body + `#[Layout('layouts.public')]`.
   - `Auth\Login`: `public string $email`, `public string $password`, `public bool $remember`,
     `login()` (Auth::attempt + RateLimiter + session()->regenerate() + redirect
     `route('admin.main')` with `navigate: true`), `#[Layout('layouts.auth')]`. Keep `use`
     statements for `Auth`, `RateLimiter`, `Component`.
   - `Admin\Main`: empty body + `#[Layout('layouts.app')]`.
   - `Admin\MasterData\Pengguna\MainIndex`: copy properties (`#[Locked] public string $search`,
     `$editingId`, `$name`, `$email`, `$password`, `$password_confirmation`, `$role`,
     `array $roles`), `use WithPagination`, `use Livewire\Attributes\Locked`, `mount()`
     (abort_unless + load roles), `getUsersProperty()`, `create()`, `edit()`, `save()`,
     `delete()`, `#[Layout('layouts.app')]`. Keep `use` for `User`, `Auth`, `Rule`,
     `Spatie\Permission\Models\Role`.

4. **Update `routes/web.php`** — replace Volt names with FQCN classes:
   ```php
   use App\Livewire\Pages\Main;
   use App\Livewire\Auth\Login;
   use App\Livewire\Admin\Main as AdminMain;
   use App\Livewire\Admin\MasterData\Pengguna\MainIndex;

   Route::livewire('/', Main::class)->name('main');
   Route::middleware('guest')->group(fn () => Route::livewire('/login', Login::class)->name('login'));
   Route::post('/logout', [MainController::class, 'logout'])->name('logout')->middleware('auth');
   Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
       Route::livewire('/', AdminMain::class)->name('main');
       Route::prefix('master-data')->group(function () {
           Route::livewire('/pengguna', MainIndex::class)->name('pengguna.index');
       });
   });
   ```
   (Keep `MainController` — still used for logout. No route name changes.)

5. **Update tests** (references use Volt `::` names → switch to FQCN):
   - `tests/Feature/AuthTest.php`: add `use App\Livewire\Auth\Login;`; replace the 3
     `Livewire::test('auth::login')` with `Livewire::test(Login::class)`.
   - `tests/Feature/UserCrudTest.php`: add
     `use App\Livewire\Admin\MasterData\Pengguna\MainIndex;`; replace the 5
     `->test('admin::master-data.pengguna.main-index')` with `->test(MainIndex::class)`.
   (Route-based assertions `route('admin.pengguna.index')`, `/login`, `/admin`, `/logout`
   are unchanged.)

6. **Delete old Volt SFCs**:
   `resources/views/pages/⚡main.blade.php`,
   `resources/views/auth/⚡login.blade.php`,
   `resources/views/admin/⚡main.blade.php`,
   `resources/views/admin/master-data/pengguna/⚡main-index.blade.php`.

7. **Optional cleanup** (only if user confirms): delete true orphans
   `resources/views/main.blade.php` and `resources/views/welcome.blade.php` (PROJ.md lists
   them as orphans; `MainController` must stay — it backs logout). Leave `component_namespaces`
   in config (harmless now).

## Validation
```bash
vendor/bin/pint --format agent                              # PHP style
php artisan route:list                                      # confirm 4 routes resolve to classes
php artisan test --compact                                  # AuthTest + UserCrudTest green
npm run build                                               # frontend asset check
```
Manual: visit `/`, `/login`, `/admin`, `/admin/master-data/pengguna` (Herd at http://proyek.test)
and confirm layout + animations render.

## Risks / Notes
- `#[Layout('layouts.public')]` uses standard Blade view resolution →
  `resources/views/layouts/public.blade.php`. Do **not** use the Volt `layouts::public` form.
- `$search` keeps `#[Locked]` and `wire:model.live.debounce.300ms="search"` exactly as before
  (preserve existing behavior, do not "fix").
- Autodiscovery requires the class under `App\Livewire`; run `livewire:discover` after creating.
- If `php artisan test` reports unknown component, run `composer dump-autoload`.

## Open question
None blocking. Confirm only if you also want the two orphan Blade views
(`main.blade.php`, `welcome.blade.php`) removed in step 7.
