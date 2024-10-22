<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProdutoController;

Route::middleware('auth')->group(function () {
    Route::get('/categoria', [ CategoriaController::class, 'index' ])->name('categoria');
    Route::get("/categoria/exc/{id}", [ CategoriaController::class, 'ExcluirCategoria' ])->name('categoria_ex');
    Route::get("/categoria/upd/{id}",
        [ CategoriaController::class, 'BuscarAlteracao' ]
    )->name('categoria_upd');

    // Página inicial pública (Home)
    Route::get('/', function () {
        return view('carwash.home');  // Sua página inicial, sem necessidade de login
    })->name('home');

    // Rotas para autenticação
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


    // Protegendo a área administrativa com middleware 'auth'
    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', function () {
            return view('admin_layout.index');  // Página de dashboard para admin
        })->name('dashboard');

        // Adicionar CRUD de categorias e outros recursos protegidos
        Route::resource('categorias', CategoriaController::class);
    });

    Route::post('/categoria', [ CategoriaController::class, 'IncluirCategoria' ]);
    Route::post('/categoria/upd', [ CategoriaController::class, 'ExecutaAlteracao' ]);

    Route::get('/produtos',
        [ProdutoController::class,'index']
    )->name('produtos_index');

    Route::get('/home', function () {
        return view('carwash.home');
    })->name('home');


    Route::post('/produtos',
        [ProdutoController::class,'salvarNovoProduto']
    )->name('novo_produto');
});

Route::get('/login', function () {
    return view("admin_layout.login");
})->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::post('/registrar', [AuthController::class, 'register'])->name('registrar');
Route::get('/registrar', function () {
    return view("admin_layout.register");
});
