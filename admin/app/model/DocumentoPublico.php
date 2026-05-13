<?php

class DocumentoPublico extends TRecord
{
    const TABLENAME  = 'documento_publico';
    const PRIMARYKEY = 'id';
    const IDPOLICY   = 'serial';

    public function __construct($id = null, $callObjectLoad = true)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('numero_documento');
        parent::addAttribute('tipo');
        parent::addAttribute('data_documento');
        parent::addAttribute('assunto');
        parent::addAttribute('nome');
        parent::addAttribute('orgao');
        parent::addAttribute('status');
        parent::addAttribute('downloads');
        parent::addAttribute('created_at');
        parent::addAttribute('updated_at');
    }

    public function getDocumentoPublicoAnexos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('documento_publico_id', '=', $this->id));
        $criteria->setProperty('order', 'ordem');
        $criteria->setProperty('direction', 'asc');

        return DocumentoPublicoAnexo::getObjects($criteria);
    }
}
