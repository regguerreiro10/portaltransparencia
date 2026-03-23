<?php

class FakeDataService
{
    public static function generatePedidosVenda()
    {
        // Código gerado pelo snippet: "Conexão com banco de dados"
        TTransaction::open('minierp');

        $pessoas = Pessoa::getObjects();
        
        $vendedores = [];
        $clientes = [];
        for($i = 0; $i <= 5; $i++)
        {
            $vendedor = new Pessoa();
            $vendedor->nome = "Vendedor {$i}";
            $vendedor->documento = '11111111111';
            $vendedor->tipo_cliente_id = 2;
            $vendedor->store();
            
            $vendedores[] = $vendedor;
            
            $grupoPessoa = new PessoaGrupo();
            $grupoPessoa->pessoa_id = $vendedor->id;
            $grupoPessoa->grupo_pessoa_id = GrupoPessoa::VENDEDOR;
            $grupoPessoa->store();
        }
        
        for($i = 0; $i <= 50; $i++)
        {
            $cliente = new Pessoa();
            $cliente->nome = "Cliente {$i}";
            $cliente->documento = '11111111111';
            $cliente->tipo_cliente_id = 1;
            $cliente->store();
            
            $clientes[] = $cliente;
            
            $grupoPessoa = new PessoaGrupo();
            $grupoPessoa->pessoa_id = $cliente->id;
            $grupoPessoa->grupo_pessoa_id = GrupoPessoa::CLIENTE;
            $grupoPessoa->store();
        }
        
        
        $categoriasReceber = Categoria::where('tipo_conta_id', '=', TipoConta::RECEBER)->load();
        $categoriasPagar = Categoria::where('tipo_conta_id', '=', TipoConta::PAGAR)->load();
        
        for($i = 0; $i <= 400; $i++)
        {
            $conta = new Conta();
            $conta->pessoa_id = rand(1, count($pessoas));
            $conta->tipo_conta_id = rand(1,2);
            
            if($conta->tipo_conta_id == TipoConta::PAGAR)
            {
                $conta->categoria_id = $categoriasPagar[rand(0, count($categoriasPagar)-1)]->id;
            }
            else
            {
                $conta->categoria_id = $categoriasReceber[rand(0, count($categoriasReceber)-1)]->id;
            }
        
            $conta->forma_pagamento_id = rand(1,3);

            $mes = str_pad(rand(1,12), 2, "0", STR_PAD_LEFT);
            $dia = str_pad(rand(1,28), 2, "0", STR_PAD_LEFT);
            $conta->dt_emissao = '2022-'.$mes.'-'.$dia;
            
            $dtEmissao = new DateTime($conta->dt_emissao);
            $dtEmissao->add(new DateInterval("P5D"));
            $conta->dt_vencimento = $dtEmissao->format('Y-m-d');
            
            if(rand(1,2) == 2)
            {
                $conta->dt_pagamento = $conta->dt_vencimento;
            }
            
            $conta->valor = rand(50, 1000);
            $conta->store();
        }
        
        
        
        TTransaction::close();
        // -----
    }
}
