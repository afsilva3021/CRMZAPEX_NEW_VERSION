<?php

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use Config\Services;

final class PainelTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    public function testVisitanteNaoAcessaPainel(): void
    {
        $resultado = $this->withSession([])->get('/painel');

        $resultado->assertRedirectTo('/');
    }

    public function testViewEscapaNomeEOfereceSaidaProtegida(): void
    {
        $html = Services::twig()->render('painel', [
            'title'           => 'Painel',
            'usuario_nome'    => '<script>alert(1)</script>',
            'grupo_id'        => 2,
            'grupo_nome'      => 'GERENTE',
            'total_usuarios'  => 3,
            'usuarios_ativos' => 3,
            'total_grupos'    => 3,
        ]);

        $this->assertStringContainsString('&lt;script&gt;alert(1)&lt;/script&gt;', $html);
        $this->assertStringNotContainsString('<script>alert(1)</script>', $html);
        $this->assertStringContainsString('action="' . site_url('sair') . '"', $html);
    }
}
