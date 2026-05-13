<?php

class GaleriaItem extends TRecord
{
    const TABLENAME  = 'galeria_item';
    const PRIMARYKEY = 'id';
    const IDPOLICY   = 'serial';

    public function __construct($id = null, $callObjectLoad = true)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('titulo');
        parent::addAttribute('descricao');
        parent::addAttribute('tipo');
        parent::addAttribute('arquivo');
        parent::addAttribute('imagem_capa');
        parent::addAttribute('texto_alternativo');
        parent::addAttribute('ordem');
        parent::addAttribute('status');
        parent::addAttribute('created_at');
        parent::addAttribute('updated_at');
    }
}
