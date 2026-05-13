<?php

class Licitacao extends TRecord
{
    const TABLENAME  = 'licitacao';
    const PRIMARYKEY = 'id';
    const IDPOLICY   = 'serial';

    public function __construct($id = null, $callObjectLoad = true)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('numero_edital');
        parent::addAttribute('processo_origem');
        parent::addAttribute('objeto');
        parent::addAttribute('status');
        parent::addAttribute('modalidade');
        parent::addAttribute('gestor');
        parent::addAttribute('fornecedor_nome');
        parent::addAttribute('fornecedor_documento');
        parent::addAttribute('valor_estimado');
        parent::addAttribute('data_licitacao');
        parent::addAttribute('downloads');
        parent::addAttribute('created_at');
        parent::addAttribute('updated_at');
    }

    public function getLicitacaoAnexos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('licitacao_id', '=', $this->id));
        $criteria->setProperty('order', 'ordem');
        $criteria->setProperty('direction', 'asc');

        return LicitacaoAnexo::getObjects($criteria);
    }
}
