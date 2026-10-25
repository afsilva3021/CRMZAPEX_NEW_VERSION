<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class Autenticado implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (session()->get('autenticado') !== true) {
            return redirect()->to('/');
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return null;
    }
}
