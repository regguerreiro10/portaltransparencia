<?php

class LicitacaoAnexo extends TRecord
{
    const TABLENAME  = 'licitacao_anexo';
    const PRIMARYKEY = 'id';
    const IDPOLICY   = 'serial';

    private ?Licitacao $licitacao = null;

    public function __construct($id = null, $callObjectLoad = true)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('licitacao_id');
        parent::addAttribute('nome');
        parent::addAttribute('arquivo');
        parent::addAttribute('ordem');
        parent::addAttribute('created_at');
    }

    public function set_licitacao(Licitacao $object)
    {
        $this->licitacao = $object;
        $this->licitacao_id = $object->id;
    }

    public function get_licitacao()
    {
        if (empty($this->licitacao)) {
            $this->licitacao = new Licitacao($this->licitacao_id);
        }

        return $this->licitacao;
    }
}
