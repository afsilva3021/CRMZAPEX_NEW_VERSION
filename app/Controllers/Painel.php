<?php

namespace App\Controllers;

use Config\Services;

class Painel extends BaseController
{
    public function index(): string
    {
        $db = db_connect();
        $grupoId = session()->get('grupo_id');
        $grupo = $db->table('grupo_menu')
            ->select('NOME')
            ->where('ID_GRUPO', $grupoId)
            ->get(1)
            ->getRowArray();

        return Services::twig()->render('painel', [
            'title'           => 'Painel',
            'pagina_ativa'    => 'painel',
            'usuario_nome'    => session()->get('usuario_nome'),
            'grupo_id'        => $grupoId,
            'grupo_nome'      => $grupo['NOME'] ?? 'Sem grupo',
            'total_usuarios'  => $db->table('usuario')->countAllResults(),
            'usuarios_ativos' => $db->table('usuario')->where('STATUS', 'A')->countAllResults(),
            'total_grupos'    => $db->table('grupo_menu')->countAllResults(),
        ]);
    }
}
