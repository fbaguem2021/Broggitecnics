<?php



use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('index');
});

Route::get('/login', [UsuarioController::class, 'showLogin'])->name('login');
Route::post('/login', [UsuarioController::class, 'login']);
Route::get('/logout', [UsuarioController::class, 'logout']);
Route::get('/registre',[UsuarioController::class, 'showRegistre'])->name('registre');
Route::post('/registre',[UsuarioController::class, 'store']);

Route::middleware(['auth'])->group(function() {
    Route::get('/home', function () {
        $user=Auth::user();
        return view('home', compact('user'));
    });
});
