<?php

class SystemSaldoEmpenhoDepartamento extends TRecord
{
    const TABLENAME  = 'system_saldo_empenho_departamento';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const DELETEDAT  = 'deleted_at';
    const CREATEDAT  = 'created_at';
    const UPDATEDAT  = 'updated_at';

    private SystemDepartamento $system_departamento;
    private SystemUsers $system_users;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('created_at');
        parent::addAttribute('updated_at');
        parent::addAttribute('deleted_at');
        parent::addAttribute('system_departamento_id');
        parent::addAttribute('saldo');
        parent::addAttribute('historico');
        parent::addAttribute('numero_documento_empenho');
        parent::addAttribute('data_documento_empenho');
        parent::addAttribute('numero_processo');
        parent::addAttribute('arquivo_empenho');
        parent::addAttribute('system_users_id');
            
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
     * Method set_system_users
     * Sample of usage: $var->system_users = $object;
     * @param $object Instance of SystemUsers
     */
    public function set_system_users(SystemUsers $object)
    {
        $this->system_users = $object;
        $this->system_users_id = $object->id;
    }

    /**
     * Method get_system_users
     * Sample of usage: $var->system_users->attribute;
     * @returns SystemUsers instance
     */
    public function get_system_users()
    {
    
        // loads the associated object
        if (empty($this->system_users))
            $this->system_users = new SystemUsers($this->system_users_id);
    
        // returns the associated object
        return $this->system_users;
    }

    /**
     * Method getDotacaoOrcamentariaContas
     */
    public function getDotacaoOrcamentariaContas()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('system_saldo_empenho_departamento_id', '=', $this->id));
        return DotacaoOrcamentariaConta::getObjects( $criteria );
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
    
        $values = DotacaoOrcamentariaConta::where('system_saldo_empenho_departamento_id', '=', $this->id)->getIndexedArray('conta_id','{conta->id}');
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
    
        $values = DotacaoOrcamentariaConta::where('system_saldo_empenho_departamento_id', '=', $this->id)->getIndexedArray('system_saldo_empenho_departamento_id','{system_saldo_empenho_departamento->id}');
        return implode(', ', $values);
    }

    /**
     * Method onBeforeDelete
     */
    public function onBeforeDelete()
    {
            

        if(DotacaoOrcamentariaConta::where('system_saldo_empenho_departamento_id', '=', $this->id)->first())
        {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    
    }

    
}

