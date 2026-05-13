<?php

class MemorandoDestinatario extends TRecord
{
    const TABLENAME  = 'memorando_destinatario';
    const PRIMARYKEY = 'id';
    const IDPOLICY   = 'serial';

    public function __construct($id = null, $callObjectLoad = true)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('memorando_id');
        parent::addAttribute('tipo_destino');
        parent::addAttribute('system_user_id');
        parent::addAttribute('destinatario_nome');
        parent::addAttribute('system_departamento_id');
        parent::addAttribute('departamento_destino_nome');
        parent::addAttribute('status');
        parent::addAttribute('recebido_em');
        parent::addAttribute('lido_em');
        parent::addAttribute('respondido_em');
        parent::addAttribute('arquivado_em');
        parent::addAttribute('created_at');
        parent::addAttribute('updated_at');
    }
}
