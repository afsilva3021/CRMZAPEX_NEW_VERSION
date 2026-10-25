<?php

namespace App\Controllers;

use CodeIgniter\Exceptions\PageNotFoundException;
use Config\Services;

class Rotinas extends BaseController
{
    public function cadastro(string $slug): string
    {
        $nome = match ($slug) {
            'produtos'                 => 'Produtos',
            'indicador-produtos'       => 'Indicador Produtos',
            'unidades-medida'          => 'Unidades de Medida',
            'grupo-produtos'           => 'Grupo de Produtos',
            'indicador-mod-2'          => 'Indicador (mod. 2)',
            'complemento-produto'      => 'Complemento do Produto',
            'permissoes'              => 'Permissões',
            'locais-estoque'           => 'Locais de Estoque',
            'municipios'              => 'Municípios',
            'fornecedores'            => 'Fornecedores',
            'clientes'                => 'Clientes',
            'contatos'                => 'Contatos',
            'produtos-fornecedor'      => 'Produtos X Fornecedor',
            'grupo-fornecedor'         => 'Grupo X Fornecedor',
            'bancos'                  => 'Bancos',
            'tipos-entrada-saida'      => 'Tipos de Entrada e Saída',
            'entrada-saida-inteligente' => 'Entrada/saída Inteligente',
            'moedas'                  => 'Moedas',
            'naturezas'               => 'Naturezas',
            'excecoes-fiscais'         => 'Exceções Fiscais',
            'condicao-pagamento'       => 'Condição de Pagamento',
            'impostos-variaveis'       => 'Impostos Variáveis',
            'messenger'               => 'Messenger',
            'lancamentos-padronizados' => 'Lançamentos Padronizados',
            'ncm'                     => 'N.c.m.',
            'banco-conhecimento'       => 'Banco do Conhecimento',
            'formulas'                => 'Fórmulas',
            'motivo-retorno'           => 'Motivo de Retorno',
            default                   => null,
        };

        if ($nome === null) {
            throw PageNotFoundException::forPageNotFound();
        }

        return $this->renderRotina($nome, 'Cadastros', 'cadastros');
    }

    public function solicitacao(string $slug): string
    {
        $nome = match ($slug) {
            'compras'            => 'Solicitação de Compras',
            'previstas'          => 'Solicitações Previstas',
            'aglutinar'          => 'Aglutinar Solicitações',
            'sugestao-compra'    => 'Sugestão de Compra',
            'itens-importacao'   => 'Itens Para Importação',
            'portal-marketplace' => 'Portal Marketplace',
            default              => null,
        };

        if ($nome === null) {
            throw PageNotFoundException::forPageNotFound();
        }

        return $this->renderRotina($nome, 'Solicitações', 'solicitacoes');
    }

    public function administracaoCompras(string $slug): string
    {
        $nome = match ($slug) {
            'tabela-precos'           => 'Tabela de Preços',
            'tolerancia-recebimento'  => 'Tolerância Recebimento',
            'entrega-terceiros'       => 'Entrega Por Terceiros',
            'tipos-compras'           => 'Tipos de Compras',
            'solicitantes'            => 'Solicitantes',
            'compradores'             => 'Compradores',
            'grupos-compras'          => 'Grupos de Compras',
            'aprovadores'             => 'Aprovadores',
            'perfis-aprovacao'        => 'Perfis de Aprovação',
            'grupos-aprovacao'        => 'Grupos de Aprovação',
            default                   => null,
        };

        if ($nome === null) {
            throw PageNotFoundException::forPageNotFound();
        }

        return $this->renderRotina($nome, 'Administração de Compras', 'administracao-compras');
    }

    private function renderRotina(string $nome, string $secao, string $secaoAtiva): string
    {
        $grupoId = session()->get('grupo_id');
        $grupo = db_connect()->table('grupo_menu')
            ->select('NOME')
            ->where('ID_GRUPO', $grupoId)
            ->get(1)
            ->getRowArray();

        return Services::twig()->render('rotina', [
            'title'        => $nome,
            'secao'        => $secao,
            'secao_ativa'  => $secaoAtiva,
            'usuario_nome' => session()->get('usuario_nome'),
            'grupo_nome'   => $grupo['NOME'] ?? 'Sem grupo',
        ]);
    }
}
