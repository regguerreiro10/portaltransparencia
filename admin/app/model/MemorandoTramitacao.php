<?php

class MemorandoTramitacao extends TRecord
{
    const TABLENAME  = 'memorando_tramitacao';
    const PRIMARYKEY = 'id';
    const IDPOLICY   = 'serial';

    public function __construct($id = null, $callObjectLoad = true)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('memorando_id');
        parent::addAttribute('memorando_destinatario_id');
        parent::addAttribute('acao');
        parent::addAttribute('status_resultante');
        parent::addAttribute('usuario_id');
        parent::addAttribute('usuario_nome');
        parent::addAttribute('departamento_nome');
        parent::addAttribute('descricao');
        parent::addAttribute('data_evento');
        parent::addAttribute('created_at');
    }
}
