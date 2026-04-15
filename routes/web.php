<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SoldadoController;
use App\Http\Controllers\EscalaController;
use App\Http\Controllers\AtividadeController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CertificadoController;


// Rota padrão do Menu/Dashboard (já criada pelo Breeze)
Route::view('/menu', 'menu')
    ->middleware(['auth', 'verified'])
    ->name('menu');

// Rotas de recurso para Soldados (Inclui cadastro, listagem, edição...)
Route::resource('soldados', SoldadoController::class)
    ->middleware(['auth', 'verified']);

Route::get('/escalas/automacao', [EscalaController::class, 'createAutomacao'])->name('escalas.automacao');
Route::post('/escalas/automacao', [EscalaController::class, 'storeAutomacao'])->name('escalas.storeAutomacao');


// Rotas de recurso para Escalas (Inclui criação, visualização, edição...)
Route::resource('escalas', EscalaController::class)->except(['edit'])
    ->middleware(['auth', 'verified']);

// Rotas customizadas para atribuição de soldados
Route::post('escalas/{escala}/attach', [EscalaController::class, 'attachSoldier'])
    ->name('escalas.attachSoldier')
    ->middleware(['auth', 'verified']);

Route::delete('escalas/{escala}/detach', [EscalaController::class, 'detachSoldier'])
    ->name('escalas.detachSoldier')
    ->middleware(['auth', 'verified']);

Route::get('/', function () {
    if (Auth::check()) {
        return view('menu');
    } else {
        return view('auth.login');
    }
    
});

Route::get('/dashboard', function () {
    return view('menu');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::view('/certificado', 'certificado.certificado')->name('certificado.index');

/*Route::get('/certificado', function () {
    return view('certificado.certificado');
});*/

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('atividades', AtividadeController::class);
    // Rotas para Gerenciamento Manual da Escala
    Route::get('/escalas/{escala}/editar', [EscalaController::class, 'edit'])->name('escalas.edit');
    Route::post('/escalas/{escala}/adicionar-soldado', [EscalaController::class, 'adicionarSoldado'])->name('escalas.adicionarSoldado');
    Route::delete('/escalas/{escala}/remover-soldado/{soldado}', [EscalaController::class, 'removerSoldado'])->name('escalas.removerSoldado');
    Route::post('/escalas/imprimir-em-massa', [EscalaController::class, 'imprimirEmMassa'])->name('escalas.imprimirEmMassa');
    Route::get('/escalas/imprimir-multiplas', [EscalaController::class, 'printMultiple'])->name('escalas.printMultiple');
    
});

Route::post('/gerar-pdf-certificado', [CertificadoController::class, 'gerar'])->name('certificado.pdf');
Route::get('/minha-escalatrava/{matricula}', [EscalaController::class, 'publicShow'])->name('escala.publica');
    
require __DIR__.'/auth.php';
