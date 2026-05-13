<?php

class DocumentoPublicoAnexo extends TRecord
{
    const TABLENAME  = 'documento_publico_anexo';
    const PRIMARYKEY = 'id';
    const IDPOLICY   = 'serial';

    private ?DocumentoPublico $documento_publico = null;

    public function __construct($id = null, $callObjectLoad = true)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('documento_publico_id');
        parent::addAttribute('nome');
        parent::addAttribute('arquivo');
        parent::addAttribute('ordem');
        parent::addAttribute('created_at');
    }

    public function set_documento_publico(DocumentoPublico $object)
    {
        $this->documento_publico = $object;
        $this->documento_publico_id = $object->id;
    }

    public function get_documento_publico()
    {
        if (empty($this->documento_publico)) {
            $this->documento_publico = new DocumentoPublico($this->documento_publico_id);
        }

        return $this->documento_publico;
    }
}
