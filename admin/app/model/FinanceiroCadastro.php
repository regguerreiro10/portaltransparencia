<?php

class FinanceiroCadastro extends TRecord
{
    const TABLENAME  = 'financeiro_cadastro';
    const PRIMARYKEY = 'id';
    const IDPOLICY   = 'serial';

    public function __construct($id = null, $callObjectLoad = true)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
        parent::addAttribute('descricao');
        parent::addAttribute('created_at');
        parent::addAttribute('updated_at');
    }

    public function getFinanceiroSubcategorias()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('financeiro_cadastro_id', '=', $this->id));
        $criteria->setProperty('order', 'ano');
        $criteria->setProperty('direction', 'desc');

        return FinanceiroSubcategoria::getObjects($criteria);
    }

    public function getFinanceiroArquivos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('financeiro_cadastro_id', '=', $this->id));
        $criteria->setProperty('order', 'id');
        $criteria->setProperty('direction', 'desc');

        return FinanceiroArquivo::getObjects($criteria);
    }
}
