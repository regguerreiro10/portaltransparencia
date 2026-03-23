<?php

class Pessoa extends TRecord
{
    const TABLENAME  = 'pessoa';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const DELETEDAT  = 'deleted_at';
    const CREATEDAT  = 'created_at';
    const UPDATEDAT  = 'updated_at';

    private TipoCliente $tipo_cliente;
    private CategoriaCliente $categoria_cliente;
    private SystemUsers $system_user;

    

    use SystemChangeLogTrait;
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('tipo_cliente_id');
        parent::addAttribute('categoria_cliente_id');
        parent::addAttribute('system_user_id');
        parent::addAttribute('nome');
        parent::addAttribute('documento');
        parent::addAttribute('obs');
        parent::addAttribute('fone');
        parent::addAttribute('email');
        parent::addAttribute('created_at');
        parent::addAttribute('updated_at');
        parent::addAttribute('deleted_at');
        parent::addAttribute('login');
        parent::addAttribute('senha');
            
    }

    /**
     * Method set_tipo_cliente
     * Sample of usage: $var->tipo_cliente = $object;
     * @param $object Instance of TipoCliente
     */
    public function set_tipo_cliente(TipoCliente $object)
    {
        $this->tipo_cliente = $object;
        $this->tipo_cliente_id = $object->id;
    }

    /**
     * Method get_tipo_cliente
     * Sample of usage: $var->tipo_cliente->attribute;
     * @returns TipoCliente instance
     */
    public function get_tipo_cliente()
    {
    
        // loads the associated object
        if (empty($this->tipo_cliente))
            $this->tipo_cliente = new TipoCliente($this->tipo_cliente_id);
    
        // returns the associated object
        return $this->tipo_cliente;
    }
    /**
     * Method set_categoria_cliente
     * Sample of usage: $var->categoria_cliente = $object;
     * @param $object Instance of CategoriaCliente
     */
    public function set_categoria_cliente(CategoriaCliente $object)
    {
        $this->categoria_cliente = $object;
        $this->categoria_cliente_id = $object->id;
    }

    /**
     * Method get_categoria_cliente
     * Sample of usage: $var->categoria_cliente->attribute;
     * @returns CategoriaCliente instance
     */
    public function get_categoria_cliente()
    {
    
        // loads the associated object
        if (empty($this->categoria_cliente))
            $this->categoria_cliente = new CategoriaCliente($this->categoria_cliente_id);
    
        // returns the associated object
        return $this->categoria_cliente;
    }
    /**
     * Method set_system_users
     * Sample of usage: $var->system_users = $object;
     * @param $object Instance of SystemUsers
     */
    public function set_system_user(SystemUsers $object)
    {
        $this->system_user = $object;
        $this->system_user_id = $object->id;
    }

    /**
     * Method get_system_user
     * Sample of usage: $var->system_user->attribute;
     * @returns SystemUsers instance
     */
    public function get_system_user()
    {
    
        // loads the associated object
        if (empty($this->system_user))
            $this->system_user = new SystemUsers($this->system_user_id);
    
        // returns the associated object
        return $this->system_user;
    }

    /**
     * Method getContas
     */
    public function getContas()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('pessoa_id', '=', $this->id));
        return Conta::getObjects( $criteria );
    }
    /**
     * Method getPessoaContatos
     */
    public function getPessoaContatos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('pessoa_id', '=', $this->id));
        return PessoaContato::getObjects( $criteria );
    }
    /**
     * Method getPessoaEnderecos
     */
    public function getPessoaEnderecos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('pessoa_id', '=', $this->id));
        return PessoaEndereco::getObjects( $criteria );
    }
    /**
     * Method getPessoaGrupos
     */
    public function getPessoaGrupos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('pessoa_id', '=', $this->id));
        return PessoaGrupo::getObjects( $criteria );
    }
    /**
     * Method getPessoaDepartamentos
     */
    public function getPessoaDepartamentos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('pessoa_id', '=', $this->id));
        return PessoaDepartamento::getObjects( $criteria );
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
    
        $values = Conta::where('pessoa_id', '=', $this->id)->getIndexedArray('pessoa_id','{pessoa->nome}');
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
    
        $values = Conta::where('pessoa_id', '=', $this->id)->getIndexedArray('tipo_conta_id','{tipo_conta->nome}');
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
    
        $values = Conta::where('pessoa_id', '=', $this->id)->getIndexedArray('categoria_id','{categoria->nome}');
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
    
        $values = Conta::where('pessoa_id', '=', $this->id)->getIndexedArray('forma_pagamento_id','{forma_pagamento->nome}');
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
    
        $values = Conta::where('pessoa_id', '=', $this->id)->getIndexedArray('system_unit_id','{system_unit->name}');
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
    
        $values = Conta::where('pessoa_id', '=', $this->id)->getIndexedArray('system_departamento_id','{system_departamento->id}');
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
    
        $values = Conta::where('pessoa_id', '=', $this->id)->getIndexedArray('system_entidade_id','{system_entidade->id}');
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
    
        $values = Conta::where('pessoa_id', '=', $this->id)->getIndexedArray('natureza_conta_id','{natureza_conta->id}');
        return implode(', ', $values);
    }

    public function set_pessoa_contato_pessoa_to_string($pessoa_contato_pessoa_to_string)
    {
        if(is_array($pessoa_contato_pessoa_to_string))
        {
            $values = Pessoa::where('id', 'in', $pessoa_contato_pessoa_to_string)->getIndexedArray('nome', 'nome');
            $this->pessoa_contato_pessoa_to_string = implode(', ', $values);
        }
        else
        {
            $this->pessoa_contato_pessoa_to_string = $pessoa_contato_pessoa_to_string;
        }

        $this->vdata['pessoa_contato_pessoa_to_string'] = $this->pessoa_contato_pessoa_to_string;
    }

    public function get_pessoa_contato_pessoa_to_string()
    {
        if(!empty($this->pessoa_contato_pessoa_to_string))
        {
            return $this->pessoa_contato_pessoa_to_string;
        }
    
        $values = PessoaContato::where('pessoa_id', '=', $this->id)->getIndexedArray('pessoa_id','{pessoa->nome}');
        return implode(', ', $values);
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
    
        $values = PessoaEndereco::where('pessoa_id', '=', $this->id)->getIndexedArray('pessoa_id','{pessoa->nome}');
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
    
        $values = PessoaEndereco::where('pessoa_id', '=', $this->id)->getIndexedArray('cidade_id','{cidade->nome}');
        return implode(', ', $values);
    }

    public function set_pessoa_grupo_pessoa_to_string($pessoa_grupo_pessoa_to_string)
    {
        if(is_array($pessoa_grupo_pessoa_to_string))
        {
            $values = Pessoa::where('id', 'in', $pessoa_grupo_pessoa_to_string)->getIndexedArray('nome', 'nome');
            $this->pessoa_grupo_pessoa_to_string = implode(', ', $values);
        }
        else
        {
            $this->pessoa_grupo_pessoa_to_string = $pessoa_grupo_pessoa_to_string;
        }

        $this->vdata['pessoa_grupo_pessoa_to_string'] = $this->pessoa_grupo_pessoa_to_string;
    }

    public function get_pessoa_grupo_pessoa_to_string()
    {
        if(!empty($this->pessoa_grupo_pessoa_to_string))
        {
            return $this->pessoa_grupo_pessoa_to_string;
        }
    
        $values = PessoaGrupo::where('pessoa_id', '=', $this->id)->getIndexedArray('pessoa_id','{pessoa->nome}');
        return implode(', ', $values);
    }

    public function set_pessoa_grupo_grupo_pessoa_to_string($pessoa_grupo_grupo_pessoa_to_string)
    {
        if(is_array($pessoa_grupo_grupo_pessoa_to_string))
        {
            $values = GrupoPessoa::where('id', 'in', $pessoa_grupo_grupo_pessoa_to_string)->getIndexedArray('nome', 'nome');
            $this->pessoa_grupo_grupo_pessoa_to_string = implode(', ', $values);
        }
        else
        {
            $this->pessoa_grupo_grupo_pessoa_to_string = $pessoa_grupo_grupo_pessoa_to_string;
        }

        $this->vdata['pessoa_grupo_grupo_pessoa_to_string'] = $this->pessoa_grupo_grupo_pessoa_to_string;
    }

    public function get_pessoa_grupo_grupo_pessoa_to_string()
    {
        if(!empty($this->pessoa_grupo_grupo_pessoa_to_string))
        {
            return $this->pessoa_grupo_grupo_pessoa_to_string;
        }
    
        $values = PessoaGrupo::where('pessoa_id', '=', $this->id)->getIndexedArray('grupo_pessoa_id','{grupo_pessoa->nome}');
        return implode(', ', $values);
    }

    public function set_pessoa_departamento_pessoa_to_string($pessoa_departamento_pessoa_to_string)
    {
        if(is_array($pessoa_departamento_pessoa_to_string))
        {
            $values = Pessoa::where('id', 'in', $pessoa_departamento_pessoa_to_string)->getIndexedArray('nome', 'nome');
            $this->pessoa_departamento_pessoa_to_string = implode(', ', $values);
        }
        else
        {
            $this->pessoa_departamento_pessoa_to_string = $pessoa_departamento_pessoa_to_string;
        }

        $this->vdata['pessoa_departamento_pessoa_to_string'] = $this->pessoa_departamento_pessoa_to_string;
    }

    public function get_pessoa_departamento_pessoa_to_string()
    {
        if(!empty($this->pessoa_departamento_pessoa_to_string))
        {
            return $this->pessoa_departamento_pessoa_to_string;
        }
    
        $values = PessoaDepartamento::where('pessoa_id', '=', $this->id)->getIndexedArray('pessoa_id','{pessoa->nome}');
        return implode(', ', $values);
    }

    public function set_pessoa_departamento_system_departamento_to_string($pessoa_departamento_system_departamento_to_string)
    {
        if(is_array($pessoa_departamento_system_departamento_to_string))
        {
            $values = SystemDepartamento::where('id', 'in', $pessoa_departamento_system_departamento_to_string)->getIndexedArray('id', 'id');
            $this->pessoa_departamento_system_departamento_to_string = implode(', ', $values);
        }
        else
        {
            $this->pessoa_departamento_system_departamento_to_string = $pessoa_departamento_system_departamento_to_string;
        }

        $this->vdata['pessoa_departamento_system_departamento_to_string'] = $this->pessoa_departamento_system_departamento_to_string;
    }

    public function get_pessoa_departamento_system_departamento_to_string()
    {
        if(!empty($this->pessoa_departamento_system_departamento_to_string))
        {
            return $this->pessoa_departamento_system_departamento_to_string;
        }
    
        $values = PessoaDepartamento::where('pessoa_id', '=', $this->id)->getIndexedArray('system_departamento_id','{system_departamento->id}');
        return implode(', ', $values);
    }

    
}

