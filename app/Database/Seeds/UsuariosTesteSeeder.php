<?php

namespace App\Database\Seeds;

use CodeIgniter\CLI\CLI;
use CodeIgniter\Database\Seeder;
use RuntimeException;
use Throwable;

class UsuariosTesteSeeder extends Seeder
{
    public function run(): void
    {
        if (ENVIRONMENT !== 'development') {
            throw new RuntimeException('Este seeder so pode ser executado em development.');
        }

        $nomesGrupos = ['ADMIN', 'GERENTE', 'VENDEDOR'];
        $grupos = $this->db->table('grupo_menu')
            ->select('ID_GRUPO, NOME')
            ->where('STATUS', 'A')
            ->whereIn('NOME', $nomesGrupos)
            ->get()
            ->getResultArray();

        $idsPorGrupo = [];

        foreach ($grupos as $grupo) {
            $idsPorGrupo[$grupo['NOME']] = (int) $grupo['ID_GRUPO'];
        }

        foreach ($nomesGrupos as $nomeGrupo) {
            if (! isset($idsPorGrupo[$nomeGrupo])) {
                throw new RuntimeException("Grupo ativo {$nomeGrupo} nao encontrado em grupo_menu.");
            }
        }

        $usuarios = [
            [
                'NOME'         => 'Administrador Teste',
                'EMAIL'        => 'admin.teste@example.com',
                'DEPARTAMENTO' => 'Administracao',
                'CARGO'        => 'Administrador',
                'ID_GRUPO'     => $idsPorGrupo['ADMIN'],
            ],
            [
                'NOME'         => 'Gerente Teste',
                'EMAIL'        => 'gerente.teste@example.com',
                'DEPARTAMENTO' => 'Comercial',
                'CARGO'        => 'Gerente',
                'ID_GRUPO'     => $idsPorGrupo['GERENTE'],
            ],
            [
                'NOME'         => 'Vendedor Teste',
                'EMAIL'        => 'vendedor.teste@example.com',
                'DEPARTAMENTO' => 'Vendas',
                'CARGO'        => 'Vendedor',
                'ID_GRUPO'     => $idsPorGrupo['VENDEDOR'],
            ],
        ];
// senha de todos os usuario da9878d3ac62dc1d
        $senhaInicial = null;
        $criados = [];
        $existentes = [];

        $this->db->transBegin();

        try {
            foreach ($usuarios as $usuario) {
                $email = $usuario['EMAIL'];

                if ($this->db->table('usuario')->where('EMAIL', $email)->countAllResults() > 0) {
                    $existentes[] = $email;
                    continue;
                }

                $senhaInicial ??= bin2hex(random_bytes(8));

                $usuario['SENHA'] = password_hash($senhaInicial, PASSWORD_DEFAULT);
                $usuario['STATUS'] = 'A';

                if (! $this->db->table('usuario')->insert($usuario)) {
                    throw new RuntimeException("Falha ao criar {$email}.");
                }

                $criados[] = $email;
            }

            if ($this->db->transStatus() === false) {
                throw new RuntimeException('Falha na transacao de usuarios de teste.');
            }

            $this->db->transCommit();
        } catch (Throwable $erro) {
            $this->db->transRollback();
            throw $erro;
        }

        if ($criados === []) {
            CLI::write('Nenhum usuario novo. Os tres e-mails de teste ja existem.');
        } else {
            CLI::write('Usuarios de teste criados:');

            foreach ($criados as $email) {
                CLI::write("- {$email}");
            }

            CLI::write("Senha inicial dos usuarios criados: {$senhaInicial}");
            CLI::write('Guarde a senha: uma nova execucao nao a altera.');
        }

        if ($existentes !== []) {
            CLI::write('E-mails existentes foram mantidos sem alterar suas senhas.');
        }
    }
}
