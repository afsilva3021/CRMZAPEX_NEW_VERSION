<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RedirectResponse;
use Config\Services;

class Login extends BaseController
{

    protected $twig;

    public function __construct()
    {
        $this->twig = Services::twig();
    }

    public function index(): string|RedirectResponse
    {
        if (session()->get('autenticado') === true) {
            return redirect()->to('/painel');
        }

        $data = [
            'title' => 'CRM Zapex | Login',
            'erro' => session()->getFlashdata('login_error'),
        ];

        return $this->twig->render('Login', $data);
    }
}
