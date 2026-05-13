<?php

class Noticia extends TRecord
{
    const TABLENAME  = 'noticia';
    const PRIMARYKEY = 'id';
    const IDPOLICY   = 'serial';

    public function __construct($id = null, $callObjectLoad = true)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('titulo');
        parent::addAttribute('slug');
        parent::addAttribute('categoria');
        parent::addAttribute('data_publicacao');
        parent::addAttribute('resumo');
        parent::addAttribute('conteudo');
        parent::addAttribute('imagem');
        parent::addAttribute('status');
        parent::addAttribute('created_at');
        parent::addAttribute('updated_at');
    }
}
