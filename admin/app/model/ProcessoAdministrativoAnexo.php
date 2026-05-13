<?php

class ProcessoAdministrativoAnexo extends TRecord
{
    const TABLENAME  = 'processo_administrativo_anexo';
    const PRIMARYKEY = 'id';
    const IDPOLICY   = 'serial';

    public function __construct($id = null, $callObjectLoad = true)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('processo_administrativo_id');
        parent::addAttribute('nome');
        parent::addAttribute('arquivo');
        parent::addAttribute('ordem');
        parent::addAttribute('created_at');
    }
}
