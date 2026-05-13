<?php

class FinanceiroCategoria extends TRecord
{
    const TABLENAME  = 'financeiro_categoria';
    const PRIMARYKEY = 'id';
    const IDPOLICY   = 'serial';

    public function __construct($id = null, $callObjectLoad = true)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
        parent::addAttribute('created_at');
        parent::addAttribute('updated_at');
    }
}
