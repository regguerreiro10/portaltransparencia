<?php

class NaturezaConta extends TRecord
{
    const TABLENAME  = 'natureza_conta';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const DELETEDAT  = 'deleted_at';
    const CREATEDAT  = 'created_at';
    const UPDATEDAT  = 'updated_at';

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('created_at');
        parent::addAttribute('updated_at');
        parent::addAttribute('deleted_at');
        parent::addAttribute('descricao');
            
    }

    /**
     * Method getContas
     */
    public function getContas()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('natureza_conta_id', '=', $this->id));
        return Conta::getObjects( $criteria );
    }

    public function set_conta_pessoa_to_string($conta_pessoa_to_string)
    {
        if(is_array($conta_pessoa_to_string))
        {
            $values = Pessoa::where('id', 'in', $conta_pessoa_to_string)->getIndexedArray('nome', 'nome');
            $this->conta_pessoa_to_string = implode(', ', $values);
        }
        else
        {
            $this->conta_pessoa_to_string = $conta_pessoa_to_string;
        }

        $this->vdata['conta_pessoa_to_string'] = $this->conta_pessoa_to_string;
    }

    public function get_conta_pessoa_to_string()
    {
        if(!empty($this->conta_pessoa_to_string))
        {
            return $this->conta_pessoa_to_string;
        }
    
        $values = Conta::where('natureza_conta_id', '=', $this->id)->getIndexedArray('pessoa_id','{pessoa->nome}');
        return implode(', ', $values);
    }

    public function set_conta_tipo_conta_to_string($conta_tipo_conta_to_string)
    {
        if(is_array($conta_tipo_conta_to_string))
        {
            $values = TipoConta::where('id', 'in', $conta_tipo_conta_to_string)->getIndexedArray('nome', 'nome');
            $this->conta_tipo_conta_to_string = implode(', ', $values);
        }
        else
        {
            $this->conta_tipo_conta_to_string = $conta_tipo_conta_to_string;
        }

        $this->vdata['conta_tipo_conta_to_string'] = $this->conta_tipo_conta_to_string;
    }

    public function get_conta_tipo_conta_to_string()
    {
        if(!empty($this->conta_tipo_conta_to_string))
        {
            return $this->conta_tipo_conta_to_string;
        }
    
        $values = Conta::where('natureza_conta_id', '=', $this->id)->getIndexedArray('tipo_conta_id','{tipo_conta->nome}');
        return implode(', ', $values);
    }

    public function set_conta_categoria_to_string($conta_categoria_to_string)
    {
        if(is_array($conta_categoria_to_string))
        {
            $values = Categoria::where('id', 'in', $conta_categoria_to_string)->getIndexedArray('nome', 'nome');
            $this->conta_categoria_to_string = implode(', ', $values);
        }
        else
        {
            $this->conta_categoria_to_string = $conta_categoria_to_string;
        }

        $this->vdata['conta_categoria_to_string'] = $this->conta_categoria_to_string;
    }

    public function get_conta_categoria_to_string()
    {
        if(!empty($this->conta_categoria_to_string))
        {
            return $this->conta_categoria_to_string;
        }
    
        $values = Conta::where('natureza_conta_id', '=', $this->id)->getIndexedArray('categoria_id','{categoria->nome}');
        return implode(', ', $values);
    }

    public function set_conta_forma_pagamento_to_string($conta_forma_pagamento_to_string)
    {
        if(is_array($conta_forma_pagamento_to_string))
        {
            $values = FormaPagamento::where('id', 'in', $conta_forma_pagamento_to_string)->getIndexedArray('nome', 'nome');
            $this->conta_forma_pagamento_to_string = implode(', ', $values);
        }
        else
        {
            $this->conta_forma_pagamento_to_string = $conta_forma_pagamento_to_string;
        }

        $this->vdata['conta_forma_pagamento_to_string'] = $this->conta_forma_pagamento_to_string;
    }

    public function get_conta_forma_pagamento_to_string()
    {
        if(!empty($this->conta_forma_pagamento_to_string))
        {
            return $this->conta_forma_pagamento_to_string;
        }
    
        $values = Conta::where('natureza_conta_id', '=', $this->id)->getIndexedArray('forma_pagamento_id','{forma_pagamento->nome}');
        return implode(', ', $values);
    }

    public function set_conta_system_unit_to_string($conta_system_unit_to_string)
    {
        if(is_array($conta_system_unit_to_string))
        {
            $values = SystemUnit::where('id', 'in', $conta_system_unit_to_string)->getIndexedArray('name', 'name');
            $this->conta_system_unit_to_string = implode(', ', $values);
        }
        else
        {
            $this->conta_system_unit_to_string = $conta_system_unit_to_string;
        }

        $this->vdata['conta_system_unit_to_string'] = $this->conta_system_unit_to_string;
    }

    public function get_conta_system_unit_to_string()
    {
        if(!empty($this->conta_system_unit_to_string))
        {
            return $this->conta_system_unit_to_string;
        }
    
        $values = Conta::where('natureza_conta_id', '=', $this->id)->getIndexedArray('system_unit_id','{system_unit->name}');
        return implode(', ', $values);
    }

    public function set_conta_system_departamento_to_string($conta_system_departamento_to_string)
    {
        if(is_array($conta_system_departamento_to_string))
        {
            $values = SystemDepartamento::where('id', 'in', $conta_system_departamento_to_string)->getIndexedArray('id', 'id');
            $this->conta_system_departamento_to_string = implode(', ', $values);
        }
        else
        {
            $this->conta_system_departamento_to_string = $conta_system_departamento_to_string;
        }

        $this->vdata['conta_system_departamento_to_string'] = $this->conta_system_departamento_to_string;
    }

    public function get_conta_system_departamento_to_string()
    {
        if(!empty($this->conta_system_departamento_to_string))
        {
            return $this->conta_system_departamento_to_string;
        }
    
        $values = Conta::where('natureza_conta_id', '=', $this->id)->getIndexedArray('system_departamento_id','{system_departamento->id}');
        return implode(', ', $values);
    }

    public function set_conta_system_entidade_to_string($conta_system_entidade_to_string)
    {
        if(is_array($conta_system_entidade_to_string))
        {
            $values = SystemEntidade::where('id', 'in', $conta_system_entidade_to_string)->getIndexedArray('id', 'id');
            $this->conta_system_entidade_to_string = implode(', ', $values);
        }
        else
        {
            $this->conta_system_entidade_to_string = $conta_system_entidade_to_string;
        }

        $this->vdata['conta_system_entidade_to_string'] = $this->conta_system_entidade_to_string;
    }

    public function get_conta_system_entidade_to_string()
    {
        if(!empty($this->conta_system_entidade_to_string))
        {
            return $this->conta_system_entidade_to_string;
        }
    
        $values = Conta::where('natureza_conta_id', '=', $this->id)->getIndexedArray('system_entidade_id','{system_entidade->id}');
        return implode(', ', $values);
    }

    public function set_conta_natureza_conta_to_string($conta_natureza_conta_to_string)
    {
        if(is_array($conta_natureza_conta_to_string))
        {
            $values = NaturezaConta::where('id', 'in', $conta_natureza_conta_to_string)->getIndexedArray('id', 'id');
            $this->conta_natureza_conta_to_string = implode(', ', $values);
        }
        else
        {
            $this->conta_natureza_conta_to_string = $conta_natureza_conta_to_string;
        }

        $this->vdata['conta_natureza_conta_to_string'] = $this->conta_natureza_conta_to_string;
    }

    public function get_conta_natureza_conta_to_string()
    {
        if(!empty($this->conta_natureza_conta_to_string))
        {
            return $this->conta_natureza_conta_to_string;
        }
    
        $values = Conta::where('natureza_conta_id', '=', $this->id)->getIndexedArray('natureza_conta_id','{natureza_conta->id}');
        return implode(', ', $values);
    }

    /**
     * Method onBeforeDelete
     */
    public function onBeforeDelete()
    {
            

        if(Conta::where('natureza_conta_id', '=', $this->id)->first())
        {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    
    }

    
}

