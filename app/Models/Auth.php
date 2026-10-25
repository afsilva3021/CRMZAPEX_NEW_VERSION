<?php

namespace App\Models;

use CodeIgniter\Model;


class Auth extends Model
{
    protected $table            = 'USUARIO';
    protected $primaryKey       = 'ID';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $useTimestamps    = false;

    protected $allowedFields = [
        'NOME',
        'EMAIL',
        'SENHA',
        'DEPARTAMENTO',
        'CARGO',
        'ID_GRUPO',
        'STATUS'
    ];

    public function autenticar(string $email, string $senha): ?array
    {
        $usuario = $this->where('EMAIL', trim($email))
            ->where('STATUS', 'A')
            ->first();

        if (
            ! is_array($usuario)
            || ! password_verify($senha, $usuario['SENHA'])
        ) {
            return null;
        }

        unset($usuario['SENHA']);

        return $usuario;
    }
}
