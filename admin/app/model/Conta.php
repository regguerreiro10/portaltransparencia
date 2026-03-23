<?php

class Conta extends TRecord
{
    const TABLENAME  = 'conta';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const DELETEDAT  = 'deleted_at';
    const CREATEDAT  = 'created_at';
    const UPDATEDAT  = 'updated_at';

    private TipoConta $tipo_conta;
    private Categoria $categoria;
    private FormaPagamento $forma_pagamento;
    private Pessoa $pessoa;
    private SystemUnit $system_unit;
    private SystemDepartamento $system_departamento;
    private SystemEntidade $system_entidade;
    private NaturezaConta $natureza_conta;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('pessoa_id');
        parent::addAttribute('tipo_conta_id');
        parent::addAttribute('categoria_id');
        parent::addAttribute('forma_pagamento_id');
        parent::addAttribute('pedido_venda_id');
        parent::addAttribute('dt_vencimento');
        parent::addAttribute('dt_emissao');
        parent::addAttribute('dt_pagamento');
        parent::addAttribute('valor');
        parent::addAttribute('parcela');
        parent::addAttribute('obs');
        parent::addAttribute('mes_vencimento');
        parent::addAttribute('ano_vencimento');
        parent::addAttribute('ano_mes_vencimento');
        parent::addAttribute('mes_emissao');
        parent::addAttribute('ano_emissao');
        parent::addAttribute('ano_mes_emissao');
        parent::addAttribute('mes_pagamento');
        parent::addAttribute('ano_pagamento');
        parent::addAttribute('ano_mes_pagamento');
        parent::addAttribute('created_at');
        parent::addAttribute('updated_at');
        parent::addAttribute('deleted_at');
        parent::addAttribute('system_unit_id');
        parent::addAttribute('system_departamento_id');
        parent::addAttribute('system_entidade_id');
        parent::addAttribute('natureza_conta_id');
    
    }

    /**
     * Method set_tipo_conta
     * Sample of usage: $var->tipo_conta = $object;
     * @param $object Instance of TipoConta
     */
    public function set_tipo_conta(TipoConta $object)
    {
        $this->tipo_conta = $object;
        $this->tipo_conta_id = $object->id;
    }

    /**
     * Method get_tipo_conta
     * Sample of usage: $var->tipo_conta->attribute;
     * @returns TipoConta instance
     */
    public function get_tipo_conta()
    {
    
        // loads the associated object
        if (empty($this->tipo_conta))
            $this->tipo_conta = new TipoConta($this->tipo_conta_id);
    
        // returns the associated object
        return $this->tipo_conta;
    }
    /**
     * Method set_categoria
     * Sample of usage: $var->categoria = $object;
     * @param $object Instance of Categoria
     */
    public function set_categoria(Categoria $object)
    {
        $this->categoria = $object;
        $this->categoria_id = $object->id;
    }

    /**
     * Method get_categoria
     * Sample of usage: $var->categoria->attribute;
     * @returns Categoria instance
     */
    public function get_categoria()
    {
    
        // loads the associated object
        if (empty($this->categoria))
            $this->categoria = new Categoria($this->categoria_id);
    
        // returns the associated object
        return $this->categoria;
    }
    /**
     * Method set_forma_pagamento
     * Sample of usage: $var->forma_pagamento = $object;
     * @param $object Instance of FormaPagamento
     */
    public function set_forma_pagamento(FormaPagamento $object)
    {
        $this->forma_pagamento = $object;
        $this->forma_pagamento_id = $object->id;
    }

    /**
     * Method get_forma_pagamento
     * Sample of usage: $var->forma_pagamento->attribute;
     * @returns FormaPagamento instance
     */
    public function get_forma_pagamento()
    {
    
        // loads the associated object
        if (empty($this->forma_pagamento))
            $this->forma_pagamento = new FormaPagamento($this->forma_pagamento_id);
    
        // returns the associated object
        return $this->forma_pagamento;
    }
    /**
     * Method set_pessoa
     * Sample of usage: $var->pessoa = $object;
     * @param $object Instance of Pessoa
     */
    public function set_pessoa(Pessoa $object)
    {
        $this->pessoa = $object;
        $this->pessoa_id = $object->id;
    }

    /**
     * Method get_pessoa
     * Sample of usage: $var->pessoa->attribute;
     * @returns Pessoa instance
     */
    public function get_pessoa()
    {
    
        // loads the associated object
        if (empty($this->pessoa))
            $this->pessoa = new Pessoa($this->pessoa_id);
    
        // returns the associated object
        return $this->pessoa;
    }
    /**
     * Method set_system_unit
     * Sample of usage: $var->system_unit = $object;
     * @param $object Instance of SystemUnit
     */
    public function set_system_unit(SystemUnit $object)
    {
        $this->system_unit = $object;
        $this->system_unit_id = $object->id;
    }

    /**
     * Method get_system_unit
     * Sample of usage: $var->system_unit->attribute;
     * @returns SystemUnit instance
     */
    public function get_system_unit()
    {
    
        // loads the associated object
        if (empty($this->system_unit))
            $this->system_unit = new SystemUnit($this->system_unit_id);
    
        // returns the associated object
        return $this->system_unit;
    }
    /**
     * Method set_system_departamento
     * Sample of usage: $var->system_departamento = $object;
     * @param $object Instance of SystemDepartamento
     */
    public function set_system_departamento(SystemDepartamento $object)
    {
        $this->system_departamento = $object;
        $this->system_departamento_id = $object->id;
    }

    /**
     * Method get_system_departamento
     * Sample of usage: $var->system_departamento->attribute;
     * @returns SystemDepartamento instance
     */
    public function get_system_departamento()
    {
    
        // loads the associated object
        if (empty($this->system_departamento))
            $this->system_departamento = new SystemDepartamento($this->system_departamento_id);
    
        // returns the associated object
        return $this->system_departamento;
    }
    /**
     * Method set_system_entidade
     * Sample of usage: $var->system_entidade = $object;
     * @param $object Instance of SystemEntidade
     */
    public function set_system_entidade(SystemEntidade $object)
    {
        $this->system_entidade = $object;
        $this->system_entidade_id = $object->id;
    }

    /**
     * Method get_system_entidade
     * Sample of usage: $var->system_entidade->attribute;
     * @returns SystemEntidade instance
     */
    public function get_system_entidade()
    {
    
        // loads the associated object
        if (empty($this->system_entidade))
            $this->system_entidade = new SystemEntidade($this->system_entidade_id);
    
        // returns the associated object
        return $this->system_entidade;
    }
    /**
     * Method set_natureza_conta
     * Sample of usage: $var->natureza_conta = $object;
     * @param $object Instance of NaturezaConta
     */
    public function set_natureza_conta(NaturezaConta $object)
    {
        $this->natureza_conta = $object;
        $this->natureza_conta_id = $object->id;
    }

    /**
     * Method get_natureza_conta
     * Sample of usage: $var->natureza_conta->attribute;
     * @returns NaturezaConta instance
     */
    public function get_natureza_conta()
    {
    
        // loads the associated object
        if (empty($this->natureza_conta))
            $this->natureza_conta = new NaturezaConta($this->natureza_conta_id);
    
        // returns the associated object
        return $this->natureza_conta;
    }

    /**
     * Method getContaAnexos
     */
    public function getContaAnexos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('conta_id', '=', $this->id));
        return ContaAnexo::getObjects( $criteria );
    }
    /**
     * Method getDotacaoOrcamentariaContas
     */
    public function getDotacaoOrcamentariaContas()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('conta_id', '=', $this->id));
        return DotacaoOrcamentariaConta::getObjects( $criteria );
    }

    public function set_conta_anexo_conta_to_string($conta_anexo_conta_to_string)
    {
        if(is_array($conta_anexo_conta_to_string))
        {
            $values = Conta::where('id', 'in', $conta_anexo_conta_to_string)->getIndexedArray('id', 'id');
            $this->conta_anexo_conta_to_string = implode(', ', $values);
        }
        else
        {
            $this->conta_anexo_conta_to_string = $conta_anexo_conta_to_string;
        }

        $this->vdata['conta_anexo_conta_to_string'] = $this->conta_anexo_conta_to_string;
    }

    public function get_conta_anexo_conta_to_string()
    {
        if(!empty($this->conta_anexo_conta_to_string))
        {
            return $this->conta_anexo_conta_to_string;
        }
    
        $values = ContaAnexo::where('conta_id', '=', $this->id)->getIndexedArray('conta_id','{conta->id}');
        return implode(', ', $values);
    }

    public function set_conta_anexo_tipo_anexo_to_string($conta_anexo_tipo_anexo_to_string)
    {
        if(is_array($conta_anexo_tipo_anexo_to_string))
        {
            $values = TipoAnexo::where('id', 'in', $conta_anexo_tipo_anexo_to_string)->getIndexedArray('nome', 'nome');
            $this->conta_anexo_tipo_anexo_to_string = implode(', ', $values);
        }
        else
        {
            $this->conta_anexo_tipo_anexo_to_string = $conta_anexo_tipo_anexo_to_string;
        }

        $this->vdata['conta_anexo_tipo_anexo_to_string'] = $this->conta_anexo_tipo_anexo_to_string;
    }

    public function get_conta_anexo_tipo_anexo_to_string()
    {
        if(!empty($this->conta_anexo_tipo_anexo_to_string))
        {
            return $this->conta_anexo_tipo_anexo_to_string;
        }
    
        $values = ContaAnexo::where('conta_id', '=', $this->id)->getIndexedArray('tipo_anexo_id','{tipo_anexo->nome}');
        return implode(', ', $values);
    }

    public function set_dotacao_orcamentaria_conta_conta_to_string($dotacao_orcamentaria_conta_conta_to_string)
    {
        if(is_array($dotacao_orcamentaria_conta_conta_to_string))
        {
            $values = Conta::where('id', 'in', $dotacao_orcamentaria_conta_conta_to_string)->getIndexedArray('id', 'id');
            $this->dotacao_orcamentaria_conta_conta_to_string = implode(', ', $values);
        }
        else
        {
            $this->dotacao_orcamentaria_conta_conta_to_string = $dotacao_orcamentaria_conta_conta_to_string;
        }

        $this->vdata['dotacao_orcamentaria_conta_conta_to_string'] = $this->dotacao_orcamentaria_conta_conta_to_string;
    }

    public function get_dotacao_orcamentaria_conta_conta_to_string()
    {
        if(!empty($this->dotacao_orcamentaria_conta_conta_to_string))
        {
            return $this->dotacao_orcamentaria_conta_conta_to_string;
        }
    
        $values = DotacaoOrcamentariaConta::where('conta_id', '=', $this->id)->getIndexedArray('conta_id','{conta->id}');
        return implode(', ', $values);
    }

    public function set_dotacao_orcamentaria_conta_system_saldo_empenho_departamento_to_string($dotacao_orcamentaria_conta_system_saldo_empenho_departamento_to_string)
    {
        if(is_array($dotacao_orcamentaria_conta_system_saldo_empenho_departamento_to_string))
        {
            $values = SystemSaldoEmpenhoDepartamento::where('id', 'in', $dotacao_orcamentaria_conta_system_saldo_empenho_departamento_to_string)->getIndexedArray('id', 'id');
            $this->dotacao_orcamentaria_conta_system_saldo_empenho_departamento_to_string = implode(', ', $values);
        }
        else
        {
            $this->dotacao_orcamentaria_conta_system_saldo_empenho_departamento_to_string = $dotacao_orcamentaria_conta_system_saldo_empenho_departamento_to_string;
        }

        $this->vdata['dotacao_orcamentaria_conta_system_saldo_empenho_departamento_to_string'] = $this->dotacao_orcamentaria_conta_system_saldo_empenho_departamento_to_string;
    }

    public function get_dotacao_orcamentaria_conta_system_saldo_empenho_departamento_to_string()
    {
        if(!empty($this->dotacao_orcamentaria_conta_system_saldo_empenho_departamento_to_string))
        {
            return $this->dotacao_orcamentaria_conta_system_saldo_empenho_departamento_to_string;
        }
    
        $values = DotacaoOrcamentariaConta::where('conta_id', '=', $this->id)->getIndexedArray('system_saldo_empenho_departamento_id','{system_saldo_empenho_departamento->id}');
        return implode(', ', $values);
    }

    public function get_status()
    {
        if(date('Y-m-d') > $this->dt_vencimento && !$this->dt_pagamento)
        {
            return "<label style='width:120px;' class='label label-danger'> ATRASADA </label>";
        }
        elseif(!$this->dt_pagamento )
        {
            return "<label style='width:120px;' class='label label-warning'> EM ABERTA </label>";
        }
        elseif($this->dt_pagamento )
        {
            return "<label style='width:120px;' class='label label-success'> QUITADA </label>";
        }
    }

    public function get_status_texto()
    {
        if(date('Y-m-d') > $this->dt_vencimento && !$this->dt_pagamento)
        {
            return "ATRASADA";
        }
        elseif(!$this->dt_pagamento )
        {
            return "EM ABERTA";
        }
        elseif($this->dt_pagamento )
        {
            return "QUITADA";
        }
    }

    public function onBeforeStore($object)
    {
        if (! empty($object->dt_emissao))
        {
            $object->mes_emissao = date('m', strtotime($object->dt_emissao));
            $object->ano_emissao = date('Y', strtotime($object->dt_emissao));
            $object->ano_mes_emissao = date('Ym', strtotime($object->dt_emissao));
        }
    
        if (! empty($object->dt_vencimento))
        {
            $object->mes_vencimento = date('m', strtotime($object->dt_vencimento));
            $object->ano_vencimento = date('Y', strtotime($object->dt_vencimento));
            $object->ano_mes_vencimento = date('Ym', strtotime($object->dt_vencimento));
        }
    
        if (! empty($object->dt_pagamento))
        {
            $object->mes_pagamento = date('m', strtotime($object->dt_pagamento));
            $object->ano_pagamento = date('Y', strtotime($object->dt_pagamento));
            $object->ano_mes_pagamento = date('Ym', strtotime($object->dt_pagamento));
        }
    }

    public function get_valor_real()
    {
        $valor = $this->valor;

        if(!$valor)
        {
            $valor = 0;
        }
    
        if($this->tipo_conta_id == TipoConta::PAGAR)
        {
            $valor = $this->valor * -1;
        }
    
        return 'R$ '.number_format($valor, 2, ',', '.');
    }

                            
}

