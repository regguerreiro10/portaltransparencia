<?php

class FinanceiroArquivo extends TRecord
{
    const TABLENAME  = 'financeiro_arquivo';
    const PRIMARYKEY = 'id';
    const IDPOLICY   = 'serial';

    private ?FinanceiroCadastro $financeiro_cadastro = null;
    private ?FinanceiroSubcategoria $subcategoria = null;

    public function __construct($id = null, $callObjectLoad = true)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('financeiro_cadastro_id');
        parent::addAttribute('subcategoria_id');
        parent::addAttribute('nome_arquivo');
        parent::addAttribute('caminho_arquivo');
        parent::addAttribute('link_externo');
        parent::addAttribute('tipo');
        parent::addAttribute('extensao');
        parent::addAttribute('created_at');
        parent::addAttribute('updated_at');
    }

    public function set_financeiro_cadastro(FinanceiroCadastro $object)
    {
        $this->financeiro_cadastro = $object;
        $this->financeiro_cadastro_id = $object->id;
    }

    public function get_financeiro_cadastro()
    {
        if (!empty($this->financeiro_cadastro_id) && empty($this->financeiro_cadastro)) {
            $this->financeiro_cadastro = new FinanceiroCadastro($this->financeiro_cadastro_id);
        }

        return $this->financeiro_cadastro;
    }

    public function set_subcategoria(FinanceiroSubcategoria $object)
    {
        $this->subcategoria = $object;
        $this->subcategoria_id = $object->id;
    }

    public function get_subcategoria()
    {
        if (!empty($this->subcategoria_id) && empty($this->subcategoria)) {
            $this->subcategoria = new FinanceiroSubcategoria($this->subcategoria_id);
        }

        return $this->subcategoria;
    }
}
