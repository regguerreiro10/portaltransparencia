<?php

class Cidade extends TRecord
{
    const TABLENAME  = 'cidade';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private Estado $estado;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('estado_id');
        parent::addAttribute('nome');
        parent::addAttribute('codigo_ibge');
            
    }

    /**
     * Method set_estado
     * Sample of usage: $var->estado = $object;
     * @param $object Instance of Estado
     */
    public function set_estado(Estado $object)
    {
        $this->estado = $object;
        $this->estado_id = $object->id;
    }

    /**
     * Method get_estado
     * Sample of usage: $var->estado->attribute;
     * @returns Estado instance
     */
    public function get_estado()
    {
    
        // loads the associated object
        if (empty($this->estado))
            $this->estado = new Estado($this->estado_id);
    
        // returns the associated object
        return $this->estado;
    }

    /**
     * Method getPessoaEnderecos
     */
    public function getPessoaEnderecos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('cidade_id', '=', $this->id));
        return PessoaEndereco::getObjects( $criteria );
    }
    /**
     * Method getSystemEntidades
     */
    public function getSystemEntidades()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('cidade_id', '=', $this->id));
        return SystemEntidade::getObjects( $criteria );
    }
    /**
     * Method getSystemDepartamentos
     */
    public function getSystemDepartamentos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('cidade_id', '=', $this->id));
        return SystemDepartamento::getObjects( $criteria );
    }

    public function set_pessoa_endereco_pessoa_to_string($pessoa_endereco_pessoa_to_string)
    {
        if(is_array($pessoa_endereco_pessoa_to_string))
        {
            $values = Pessoa::where('id', 'in', $pessoa_endereco_pessoa_to_string)->getIndexedArray('nome', 'nome');
            $this->pessoa_endereco_pessoa_to_string = implode(', ', $values);
        }
        else
        {
            $this->pessoa_endereco_pessoa_to_string = $pessoa_endereco_pessoa_to_string;
        }

        $this->vdata['pessoa_endereco_pessoa_to_string'] = $this->pessoa_endereco_pessoa_to_string;
    }

    public function get_pessoa_endereco_pessoa_to_string()
    {
        if(!empty($this->pessoa_endereco_pessoa_to_string))
        {
            return $this->pessoa_endereco_pessoa_to_string;
        }
    
        $values = PessoaEndereco::where('cidade_id', '=', $this->id)->getIndexedArray('pessoa_id','{pessoa->nome}');
        return implode(', ', $values);
    }

    public function set_pessoa_endereco_cidade_to_string($pessoa_endereco_cidade_to_string)
    {
        if(is_array($pessoa_endereco_cidade_to_string))
        {
            $values = Cidade::where('id', 'in', $pessoa_endereco_cidade_to_string)->getIndexedArray('nome', 'nome');
            $this->pessoa_endereco_cidade_to_string = implode(', ', $values);
        }
        else
        {
            $this->pessoa_endereco_cidade_to_string = $pessoa_endereco_cidade_to_string;
        }

        $this->vdata['pessoa_endereco_cidade_to_string'] = $this->pessoa_endereco_cidade_to_string;
    }

    public function get_pessoa_endereco_cidade_to_string()
    {
        if(!empty($this->pessoa_endereco_cidade_to_string))
        {
            return $this->pessoa_endereco_cidade_to_string;
        }
    
        $values = PessoaEndereco::where('cidade_id', '=', $this->id)->getIndexedArray('cidade_id','{cidade->nome}');
        return implode(', ', $values);
    }

    public function set_system_entidade_cidade_to_string($system_entidade_cidade_to_string)
    {
        if(is_array($system_entidade_cidade_to_string))
        {
            $values = Cidade::where('id', 'in', $system_entidade_cidade_to_string)->getIndexedArray('nome', 'nome');
            $this->system_entidade_cidade_to_string = implode(', ', $values);
        }
        else
        {
            $this->system_entidade_cidade_to_string = $system_entidade_cidade_to_string;
        }

        $this->vdata['system_entidade_cidade_to_string'] = $this->system_entidade_cidade_to_string;
    }

    public function get_system_entidade_cidade_to_string()
    {
        if(!empty($this->system_entidade_cidade_to_string))
        {
            return $this->system_entidade_cidade_to_string;
        }
    
        $values = SystemEntidade::where('cidade_id', '=', $this->id)->getIndexedArray('cidade_id','{cidade->nome}');
        return implode(', ', $values);
    }

    public function set_system_departamento_system_unit_to_string($system_departamento_system_unit_to_string)
    {
        if(is_array($system_departamento_system_unit_to_string))
        {
            $values = SystemUnit::where('id', 'in', $system_departamento_system_unit_to_string)->getIndexedArray('name', 'name');
            $this->system_departamento_system_unit_to_string = implode(', ', $values);
        }
        else
        {
            $this->system_departamento_system_unit_to_string = $system_departamento_system_unit_to_string;
        }

        $this->vdata['system_departamento_system_unit_to_string'] = $this->system_departamento_system_unit_to_string;
    }

    public function get_system_departamento_system_unit_to_string()
    {
        if(!empty($this->system_departamento_system_unit_to_string))
        {
            return $this->system_departamento_system_unit_to_string;
        }
    
        $values = SystemDepartamento::where('cidade_id', '=', $this->id)->getIndexedArray('system_unit_id','{system_unit->name}');
        return implode(', ', $values);
    }

    public function set_system_departamento_cidade_to_string($system_departamento_cidade_to_string)
    {
        if(is_array($system_departamento_cidade_to_string))
        {
            $values = Cidade::where('id', 'in', $system_departamento_cidade_to_string)->getIndexedArray('nome', 'nome');
            $this->system_departamento_cidade_to_string = implode(', ', $values);
        }
        else
        {
            $this->system_departamento_cidade_to_string = $system_departamento_cidade_to_string;
        }

        $this->vdata['system_departamento_cidade_to_string'] = $this->system_departamento_cidade_to_string;
    }

    public function get_system_departamento_cidade_to_string()
    {
        if(!empty($this->system_departamento_cidade_to_string))
        {
            return $this->system_departamento_cidade_to_string;
        }
    
        $values = SystemDepartamento::where('cidade_id', '=', $this->id)->getIndexedArray('cidade_id','{cidade->nome}');
        return implode(', ', $values);
    }

    
}

