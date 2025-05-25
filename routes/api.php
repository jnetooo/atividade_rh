<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Funcionario;
use App\Models\Departamento;

// ROTAS P/ DEPARTAMENTO

// Criar Departamento
Route::post('/departamentos', function (Request $request) {
    $departamento = new Departamento();
    $departamento->nome = $request->input('nome');
    $departamento->descricao = $request->input('descricao');
    $departamento->save();
    return response()->json([
        'mensagem' => "Departamento '{$departamento->nome}' foi criado com sucesso!"], 201);
});

// Listar todos os Departamentos
Route::get('/departamentos', function () {
    $departamentos = Departamento::select( 'nome', 'descricao', 'id')->get();
    return response()->json(['departamentos' => $departamentos]);
});

// Buscar Departamento por ID
Route::get('/departamentos/{id}', function ($id) {
    $departamento = Departamento::select( 'nome', 'descricao', 'id')->find($id);
    if (!$departamento) {
        return response()->json(['erro' => 'Departamento não encontrado'], 404);
    }
    return response()->json(['departamento' => $departamento]);
});

// Atualizar Departamento
Route::patch('/departamentos/{id}', function (Request $request, $id) {
    $departamento = Departamento::find($id);

    if (!$departamento) {
        return response()->json(['erro' => 'Departamento não encontrado'], 404);
    }

    if ($request->input('nome') !== null) {
        $departamento->nome = $request->input('nome');
    }

    if ($request->input('descricao') !== null) {
        $departamento->descricao = $request->input('descricao');
    }

    $departamento->save();

    return response()->json([
        'mensagem' => "Departamento '{$departamento->nome}' foi atualizado com sucesso!"
    ], 200);
});

// Deletar Departamento
Route::delete("/departamentos/{id}", function ($id) {
    $departamento = Departamento::find($id);
    if (!$departamento) {
        return response()->json(["erro" => "Departamento não encontrado"], 404);
    }

    $nomeDepartamento = $departamento->nome;
    $idDepartamento = $departamento->id;

    $departamento->delete();

    return response()->json([
        "mensagem" => "Departamento '{$nomeDepartamento}' (ID: {$idDepartamento}) deletado com sucesso"
    ]);
});

// ROTAS P/ FUNCIONÁRIOS

// Criar Funcionário
Route::post('/funcionarios', function (Request $request) {
    $funcionario = new Funcionario();
    $funcionario->nome = $request->input('nome');
    $funcionario->email = $request->input('email');
    $funcionario->telefone = $request->input('telefone');
    $funcionario->cargo = $request->input('cargo');
    $funcionario->salario = $request->input('salario');
    $funcionario->departamento_id = $request->input('departamento_id');
    $funcionario->save();
    return response()->json([
        'mensagem' => "Funcionário(a) '{$funcionario->nome}' foi criado(a) com sucesso!"], 201);
});

// Listar todos os Funcionários
Route::get('/funcionarios', function () {
    $funcionarios = Funcionario::select('nome','id', 'cargo', 'departamento_id', 'salario', 'email', 'telefone')->get();
    return response()->json(['funcionario' => $funcionarios]);
});

// Buscar Funcionário por ID
Route::get('/funcionarios/{id}', function ($id) {
    $funcionario = Funcionario::select( 'nome', 'cargo', 'salario','departamento_id', 'email', 'telefone', 'id')->find($id);
    if (!$funcionario) {
        return response()->json(['erro' => 'Funcionário não encontrado'], 404);
    }
    $funcionario->departamento_id = $funcionario->departamento_id ?? 'Não vinculado a nenhum departamento';
    return response()->json(['funcionario' => $funcionario]);
});

// Atualizar Funcionário
Route::patch('/funcionarios/{id}', function(Request $request, $id){
    $funcionario = Funcionario::find($id);

    if (!$funcionario) {
        return response()->json(['erro' => 'Funcionário não encontrado'], 404);
    }

    if ($request->input('nome') !== null) {
        $funcionario->nome = $request->input('nome');
    }
    if ($request->input('email') !== null) {
        $funcionario->email = $request->input('email');
    }
    if ($request->input('telefone') !== null) {
        $funcionario->telefone = $request->input('telefone');
    }
    if ($request->input('cargo') !== null) {
        $funcionario->cargo = $request->input('cargo');
    }
    if ($request->input('salario') !== null) {
        $funcionario->salario = $request->input('salario');
    }
    if ($request->input('departamento_id') !== null) {
        $funcionario->departamento_id = $request->input('departamento_id');
    }

    $funcionario->save();

    return response()->json([
        'mensagem' => "Funcionário(a) '{$funcionario->nome}' foi atualizado(a) com sucesso!"
    ], 200);
});

// Deletar Funcionário
Route::delete('/funcionarios/{id}', function ($id) {
    $funcionario = Funcionario::find($id);
    if (!$funcionario) {
        return response()->json(['erro' => 'Funcionário não encontrado'], 404);
    }

    $nomeFuncionario = $funcionario->nome;
    $idFuncionario = $funcionario->id;

    $funcionario->delete();
    return response()->json([
        "mensagem" => "Funcionário(a) '{$nomeFuncionario}' (ID: {$idFuncionario}) deletado(a) com sucesso"
    ], 200);
});

// ROTAS P/ RELACIONAMENTOS

Route::get('/funcionarios-com-departamentos', function () {
    $funcionarios = Funcionario::with('departamento')->get();

    return response()->json($funcionarios);
});

// Listar Departamentos com seus Funcionários
Route::get('/departamentos-com-funcionarios', function () {
    $departamentos = Departamento::with('funcionarios')->get();

    return response()->json($departamentos);
});


// Buscar Departamento de um Funcionário específico
Route::get('/funcionarios/{id}/departamento', function ($id) {
    $funcionario = Funcionario::with('departamento')->find($id);
    if (!$funcionario) {
        return response()->json(['erro' => 'Funcionário não encontrado'], 404);
    }
    return response()->json([
        'funcionario' => $funcionario->nome,
        'departamento' => $funcionario->departamento
    ]);
});

// Buscar Funcionários de um Departamento específico
Route::get('/departamentos/{id}/funcionarios', function ($id) {
    $departamento = Departamento::with('funcionarios')->find($id);

    if (!$departamento) {
        return response()->json(['erro' => 'Departamento não encontrado'], 404);
    }

    return response()->json([
        'departamento' => $departamento->nome,
        'funcionarios' => $departamento->funcionarios
    ]);
});

