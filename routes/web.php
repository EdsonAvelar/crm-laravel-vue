<?php
use App\Http\Controllers\AuthController;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\AdminController;

Route::get('/users', [AdminController::class, 'index'])->name('users.index');


Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return Inertia::render('LandingPage');
    })->name('landingpage');


    Route::inertia('/register', 'Auth/Register')->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::inertia('/login', 'Auth/Login')->name('login');
    Route::post('/login', [AuthController::class, 'login']);

});


Route::middleware('auth')->group(function () {


    // Route::get('/configuracoes/producao/index', function (Request $request) {

    //     return inertia('Home', [
    //         'users' => User::when($request->search, function ($query) use ($request) {
    //             $query->where('name', 'like', '%' . $request->search . '%');
    //         })->paginate(5),
    //     ]);

    // })->name('home');

    Route::resource('producao', ProductionController::class);


    Route::get('/configuracoes/producao/index', [ProductionController::class, 'index'])->name('productions.index');
    Route::delete('/configuracoes/producao/delete/{id}', [ProductionController::class, 'destroy'])->name('productions.delete');
    Route::put('/configuracoes/producao/update/{id}', [ProductionController::class, 'update'])->name('productions.update');

    //Route::get('/configuracoes/producao/index', [ProductionController::class, 'index'])->name('productions.index');
    Route::post('/configuracoes/producao/store', [ProductionController::class, 'store'])->name('productions.store');

    Route::get('/home', function (Request $request) {

        return inertia('Home', [
            'users' => User::when($request->search, function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            })->paginate(5),
        ]);

    })->name('home');


    Route::group(
        ['prefix' => 'empresa'],
        function () {
            Route::get('/profile', [EmpresaController::class, 'empresa_profile'])->name('empresa_profile');
            Route::post('/save', [EmpresaController::class, 'save'])->name('empresa_save');
            Route::post('/config', [EmpresaController::class, 'empresa_config'])->name('empresa_config');
            Route::post('/image/save', [EmpresaController::class, 'empresa_images'])->name('empresa_images');

        }
    );




    Route::get('/', function () {
        return Inertia::render(component: 'Configuracoes');
    })->name('configuracoes');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


});

