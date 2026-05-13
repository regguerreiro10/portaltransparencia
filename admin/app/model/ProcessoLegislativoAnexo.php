<?php

class ProcessoLegislativoAnexo extends TRecord
{
    const TABLENAME  = 'processo_legislativo_anexo';
    const PRIMARYKEY = 'id';
    const IDPOLICY   = 'serial';

    public function __construct($id = null, $callObjectLoad = true)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('processo_legislativo_id');
        parent::addAttribute('nome');
        parent::addAttribute('arquivo');
        parent::addAttribute('principal');
        parent::addAttribute('ordem');
        parent::addAttribute('created_at');
    }
}
