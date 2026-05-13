<?php

class ProcessoAdministrativoTramitacao extends TRecord
{
    const TABLENAME  = 'processo_administrativo_tramitacao';
    const PRIMARYKEY = 'id';
    const IDPOLICY   = 'serial';

    public function __construct($id = null, $callObjectLoad = true)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('processo_administrativo_id');
        parent::addAttribute('data_tramitacao');
        parent::addAttribute('tipo_evento');
        parent::addAttribute('acao_executada');
        parent::addAttribute('departamento_origem');
        parent::addAttribute('departamento_destino');
        parent::addAttribute('usuario_responsavel');
        parent::addAttribute('despacho_texto');
        parent::addAttribute('prazo_tipo');
        parent::addAttribute('prazo_dias');
        parent::addAttribute('prazo_final');
        parent::addAttribute('prazo_status');
        parent::addAttribute('anexo_descricao');
        parent::addAttribute('observacao');
        parent::addAttribute('created_at');
    }
}
