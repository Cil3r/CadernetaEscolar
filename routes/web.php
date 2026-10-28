<?php

use App\Http\Controllers\AlunoController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CadernetaController;
use App\Http\Controllers\ConfiguracaoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FaltaController;
use App\Http\Controllers\OcorrenciaController;
use App\Http\Controllers\TurmaController;
use App\Http\Controllers\UtilizadorController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::resource('turmas', TurmaController::class)->parameters(['turmas' => 'turma']);
    Route::post('/turmas/{turma}/professores', [TurmaController::class, 'associarProfessor'])->name('turmas.professores.associar');
    Route::delete('/turmas/{turma}/professores/{professor}', [TurmaController::class, 'removerProfessor'])->name('turmas.professores.remover');

    Route::resource('utilizadores', UtilizadorController::class)
        ->parameters(['utilizadores' => 'utilizador'])
        ->except('show');

    Route::resource('alunos', AlunoController::class)->parameters(['alunos' => 'aluno']);
    Route::get('/alunos/{aluno}/caderneta', [CadernetaController::class, 'show'])->name('alunos.caderneta');

    Route::resource('ocorrencias', OcorrenciaController::class)
        ->parameters(['ocorrencias' => 'ocorrencia'])
        ->except('show');

    Route::resource('faltas', FaltaController::class)
        ->parameters(['faltas' => 'falta'])
        ->except('show');

    Route::get('/configuracoes', [ConfiguracaoController::class, 'index'])->name('configuracoes.index');
    Route::put('/configuracoes', [ConfiguracaoController::class, 'update'])->name('configuracoes.update');
});
