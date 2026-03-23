<?php

class PessoaDepartamento extends TRecord
{
    const TABLENAME  = 'pessoa_departamento';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const DELETEDAT  = 'deleted_at';
    const CREATEDAT  = 'created_at';
    const UPDATEDAT  = 'updated_at';

    private Pessoa $pessoa;
    private SystemDepartamento $system_departamento;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('created_at');
        parent::addAttribute('updated_at');
        parent::addAttribute('deleted_at');
        parent::addAttribute('pessoa_id');
        parent::addAttribute('system_departamento_id');
            
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

    
}

