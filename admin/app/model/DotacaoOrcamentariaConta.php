<?php

class DotacaoOrcamentariaConta extends TRecord
{
    const TABLENAME  = 'dotacao_orcamentaria_conta';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const DELETEDAT  = 'deleted_at';
    const CREATEDAT  = 'created_at';
    const UPDATEDAT  = 'updated_at';

    private Conta $conta;
    private SystemSaldoEmpenhoDepartamento $system_saldo_empenho_departamento;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('created_at');
        parent::addAttribute('updated_at');
        parent::addAttribute('deleted_at');
        parent::addAttribute('conta_id');
        parent::addAttribute('system_saldo_empenho_departamento_id');
        parent::addAttribute('valor');
        parent::addAttribute('saldo_atual');
            
    }

    /**
     * Method set_conta
     * Sample of usage: $var->conta = $object;
     * @param $object Instance of Conta
     */
    public function set_conta(Conta $object)
    {
        $this->conta = $object;
        $this->conta_id = $object->id;
    }

    /**
     * Method get_conta
     * Sample of usage: $var->conta->attribute;
     * @returns Conta instance
     */
    public function get_conta()
    {
    
        // loads the associated object
        if (empty($this->conta))
            $this->conta = new Conta($this->conta_id);
    
        // returns the associated object
        return $this->conta;
    }
    /**
     * Method set_system_saldo_empenho_departamento
     * Sample of usage: $var->system_saldo_empenho_departamento = $object;
     * @param $object Instance of SystemSaldoEmpenhoDepartamento
     */
    public function set_system_saldo_empenho_departamento(SystemSaldoEmpenhoDepartamento $object)
    {
        $this->system_saldo_empenho_departamento = $object;
        $this->system_saldo_empenho_departamento_id = $object->id;
    }

    /**
     * Method get_system_saldo_empenho_departamento
     * Sample of usage: $var->system_saldo_empenho_departamento->attribute;
     * @returns SystemSaldoEmpenhoDepartamento instance
     */
    public function get_system_saldo_empenho_departamento()
    {
    
        // loads the associated object
        if (empty($this->system_saldo_empenho_departamento))
            $this->system_saldo_empenho_departamento = new SystemSaldoEmpenhoDepartamento($this->system_saldo_empenho_departamento_id);
    
        // returns the associated object
        return $this->system_saldo_empenho_departamento;
    }

    
}

