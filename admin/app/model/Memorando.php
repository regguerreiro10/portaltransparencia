<?php

class Memorando extends TRecord
{
    const TABLENAME  = 'memorando';
    const PRIMARYKEY = 'id';
    const IDPOLICY   = 'serial';

    public function __construct($id = null, $callObjectLoad = true)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('numero_memorando');
        parent::addAttribute('assunto');
        parent::addAttribute('texto_memorando');
        parent::addAttribute('status');
        parent::addAttribute('tipo');
        parent::addAttribute('template_codigo');
        parent::addAttribute('template_nome');
        parent::addAttribute('remetente_user_id');
        parent::addAttribute('remetente_nome');
        parent::addAttribute('departamento_origem_id');
        parent::addAttribute('departamento_origem_nome');
        parent::addAttribute('data_memorando');
        parent::addAttribute('memorando_pai_id');
        parent::addAttribute('pode_virar_processo');
        parent::addAttribute('processo_referencia');
        parent::addAttribute('downloads');
        parent::addAttribute('lido_em');
        parent::addAttribute('arquivado_em');
        parent::addAttribute('created_at');
        parent::addAttribute('updated_at');
    }

    public function getMemorandoAnexos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('memorando_id', '=', $this->id));
        $criteria->setProperty('order', 'ordem');
        $criteria->setProperty('direction', 'asc');
        return MemorandoAnexo::getObjects($criteria);
    }

    public function getMemorandoDestinatarios()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('memorando_id', '=', $this->id));
        $criteria->setProperty('order', 'id');
        $criteria->setProperty('direction', 'asc');
        return MemorandoDestinatario::getObjects($criteria);
    }

    public function getMemorandoTramitacoes()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('memorando_id', '=', $this->id));
        $criteria->setProperty('order', 'data_evento');
        $criteria->setProperty('direction', 'desc');
        return MemorandoTramitacao::getObjects($criteria);
    }

    public function get_destinatarios_resumo()
    {
        $nomes = [];
        foreach ($this->getMemorandoDestinatarios() as $destinatario) {
            $nomes[] = $destinatario->destinatario_nome;
        }

        return implode(', ', array_unique(array_filter($nomes)));
    }

    public function get_departamentos_destino_resumo()
    {
        $departamentos = [];
        foreach ($this->getMemorandoDestinatarios() as $destinatario) {
            $departamentos[] = $destinatario->departamento_destino_nome;
        }

        return implode(', ', array_unique(array_filter($departamentos)));
    }
}
