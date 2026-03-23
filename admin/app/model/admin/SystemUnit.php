<?php

class SystemUnit extends TRecord
{
    const TABLENAME  = 'system_unit';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'max'; // {max, serial}

    private SystemEntidade $system_entidade;
    private Cidade $cidade;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('name');
        parent::addAttribute('connection_name');
        parent::addAttribute('email');
        parent::addAttribute('cep');
        parent::addAttribute('rua');
        parent::addAttribute('numero');
        parent::addAttribute('bairro');
        parent::addAttribute('complemento');
        parent::addAttribute('cnpj');
        parent::addAttribute('telefone01');
        parent::addAttribute('telefone02');
        parent::addAttribute('logo');
        parent::addAttribute('longitude');
        parent::addAttribute('latitude');
        parent::addAttribute('system_entidade_id');
        parent::addAttribute('cidade_id');
            
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
     * Method set_cidade
     * Sample of usage: $var->cidade = $object;
     * @param $object Instance of Cidade
     */
    public function set_cidade(Cidade $object)
    {
        $this->cidade = $object;
        $this->cidade_id = $object->id;
    }

    /**
     * Method get_cidade
     * Sample of usage: $var->cidade->attribute;
     * @returns Cidade instance
     */
    public function get_cidade()
    {
    
        // loads the associated object
        if (empty($this->cidade))
            $this->cidade = new Cidade($this->cidade_id);
    
        // returns the associated object
        return $this->cidade;
    }

    /**
     * Method getSystemDepartamentos
     */
    public function getSystemDepartamentos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('system_unit_id', '=', $this->id));
        return SystemDepartamento::getObjects( $criteria );
    }
    /**
     * Method getContas
     */
    public function getContas()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('system_unit_id', '=', $this->id));
        return Conta::getObjects( $criteria );
    }
    /**
     * Method getSystemDocumentoss
     */
    public function getSystemDocumentoss()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('system_unit_id', '=', $this->id));
        return SystemDocumentos::getObjects( $criteria );
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
    
        $values = SystemDepartamento::where('system_unit_id', '=', $this->id)->getIndexedArray('system_unit_id','{system_unit->name}');
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
    
        $values = SystemDepartamento::where('system_unit_id', '=', $this->id)->getIndexedArray('cidade_id','{cidade->nome}');
        return implode(', ', $values);
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
    
        $values = Conta::where('system_unit_id', '=', $this->id)->getIndexedArray('pessoa_id','{pessoa->nome}');
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
    
        $values = Conta::where('system_unit_id', '=', $this->id)->getIndexedArray('tipo_conta_id','{tipo_conta->nome}');
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
    
        $values = Conta::where('system_unit_id', '=', $this->id)->getIndexedArray('categoria_id','{categoria->nome}');
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
    
        $values = Conta::where('system_unit_id', '=', $this->id)->getIndexedArray('forma_pagamento_id','{forma_pagamento->nome}');
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
    
        $values = Conta::where('system_unit_id', '=', $this->id)->getIndexedArray('system_unit_id','{system_unit->name}');
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
    
        $values = Conta::where('system_unit_id', '=', $this->id)->getIndexedArray('system_departamento_id','{system_departamento->id}');
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
    
        $values = Conta::where('system_unit_id', '=', $this->id)->getIndexedArray('system_entidade_id','{system_entidade->id}');
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
    
        $values = Conta::where('system_unit_id', '=', $this->id)->getIndexedArray('natureza_conta_id','{natureza_conta->id}');
        return implode(', ', $values);
    }

    public function set_system_documentos_system_unit_to_string($system_documentos_system_unit_to_string)
    {
        if(is_array($system_documentos_system_unit_to_string))
        {
            $values = SystemUnit::where('id', 'in', $system_documentos_system_unit_to_string)->getIndexedArray('name', 'name');
            $this->system_documentos_system_unit_to_string = implode(', ', $values);
        }
        else
        {
            $this->system_documentos_system_unit_to_string = $system_documentos_system_unit_to_string;
        }

        $this->vdata['system_documentos_system_unit_to_string'] = $this->system_documentos_system_unit_to_string;
    }

    public function get_system_documentos_system_unit_to_string()
    {
        if(!empty($this->system_documentos_system_unit_to_string))
        {
            return $this->system_documentos_system_unit_to_string;
        }
    
        $values = SystemDocumentos::where('system_unit_id', '=', $this->id)->getIndexedArray('system_unit_id','{system_unit->name}');
        return implode(', ', $values);
    }

    public function set_system_documentos_tipo_system_documentos_to_string($system_documentos_tipo_system_documentos_to_string)
    {
        if(is_array($system_documentos_tipo_system_documentos_to_string))
        {
            $values = TipoSystemDocumentos::where('id', 'in', $system_documentos_tipo_system_documentos_to_string)->getIndexedArray('id', 'id');
            $this->system_documentos_tipo_system_documentos_to_string = implode(', ', $values);
        }
        else
        {
            $this->system_documentos_tipo_system_documentos_to_string = $system_documentos_tipo_system_documentos_to_string;
        }

        $this->vdata['system_documentos_tipo_system_documentos_to_string'] = $this->system_documentos_tipo_system_documentos_to_string;
    }

    public function get_system_documentos_tipo_system_documentos_to_string()
    {
        if(!empty($this->system_documentos_tipo_system_documentos_to_string))
        {
            return $this->system_documentos_tipo_system_documentos_to_string;
        }
    
        $values = SystemDocumentos::where('system_unit_id', '=', $this->id)->getIndexedArray('tipo_system_documentos_id','{tipo_system_documentos->id}');
        return implode(', ', $values);
    }

    
}

