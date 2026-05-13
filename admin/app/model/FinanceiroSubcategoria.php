<?php

class FinanceiroSubcategoria extends TRecord
{
    const TABLENAME  = 'financeiro_subcategoria';
    const PRIMARYKEY = 'id';
    const IDPOLICY   = 'serial';

    private ?FinanceiroCadastro $financeiro_cadastro = null;
    private ?FinanceiroCategoria $categoria = null;

    public function __construct($id = null, $callObjectLoad = true)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('financeiro_cadastro_id');
        parent::addAttribute('categoria_id');
        parent::addAttribute('nome');
        parent::addAttribute('ano');
        parent::addAttribute('visivel');
        parent::addAttribute('created_at');
        parent::addAttribute('updated_at');
    }

    public function set_financeiro_cadastro(FinanceiroCadastro $object)
    {
        $this->financeiro_cadastro = $object;
        $this->financeiro_cadastro_id = $object->id;
    }

    public function get_financeiro_cadastro()
    {
        if (empty($this->financeiro_cadastro)) {
            $this->financeiro_cadastro = new FinanceiroCadastro($this->financeiro_cadastro_id);
        }

        return $this->financeiro_cadastro;
    }

    public function set_categoria(FinanceiroCategoria $object)
    {
        $this->categoria = $object;
        $this->categoria_id = $object->id;
    }

    public function get_categoria()
    {
        if (empty($this->categoria)) {
            $this->categoria = new FinanceiroCategoria($this->categoria_id);
        }

        return $this->categoria;
    }

    public function getFinanceiroArquivos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('subcategoria_id', '=', $this->id));
        $criteria->setProperty('order', 'id');
        $criteria->setProperty('direction', 'desc');

        return FinanceiroArquivo::getObjects($criteria);
    }
}
