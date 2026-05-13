<?php

class ProcessoLegislativoTramitacao extends TRecord
{
    const TABLENAME  = 'processo_legislativo_tramitacao';
    const PRIMARYKEY = 'id';
    const IDPOLICY   = 'serial';

    public function __construct($id = null, $callObjectLoad = true)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('processo_legislativo_id');
        parent::addAttribute('data_tramitacao');
        parent::addAttribute('situacao');
        parent::addAttribute('descricao_andamento');
        parent::addAttribute('remetente');
        parent::addAttribute('destinatario');
        parent::addAttribute('usuario_responsavel');
        parent::addAttribute('observacao');
        parent::addAttribute('created_at');
    }
}
