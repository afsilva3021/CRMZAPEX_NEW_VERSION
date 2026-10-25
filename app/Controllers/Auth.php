<?php

namespace App\Controllers;

use App\Models\Auth as AuthModel;
use CodeIgniter\HTTP\RedirectResponse;

class Auth extends BaseController
{

    public function entrar(): RedirectResponse
    {
        $email = $this->request->getPost('email');
        $senha = $this->request->getPost('senha');

        if (
            ! is_string($email) ||
            ! is_string($senha) ||
            ! $this->validateData(
                ['email' => trim($email), 'senha' => $senha],
                ['email' => 'required|valid_email', 'senha' => 'required']
            )
        ) {
            return redirect()->to('/')->with('login_error', 'E-mail ou senha inválidos.');
        }

        $usuario = (new AuthModel())->autenticar(trim($email), $senha);

        if ($usuario === null) {
            return redirect()->to('/')->with('login_error', 'E-mail ou senha inválidos.');
        }

        $sessao = session();
        $sessao->regenerate(true);
        $sessao->set([
            'usuario_id'    => $usuario['ID'],
            'usuario_nome'  => $usuario['NOME'],
            'grupo_id'      => $usuario['ID_GRUPO'],
            'autenticado'   => true,
        ]);

        return redirect()->to('/painel');
    }

    public function sair(): RedirectResponse
    {
        session()->destroy();

        return redirect()->to('/');
    }
}
