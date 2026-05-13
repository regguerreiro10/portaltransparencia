<?php

class ProcessoLegislativo extends TRecord
{
    const TABLENAME  = 'processo_legislativo';
    const PRIMARYKEY = 'id';
    const IDPOLICY   = 'serial';

    public function __construct($id = null, $callObjectLoad = true)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('tipo_processo');
        parent::addAttribute('numero_processo');
        parent::addAttribute('ano');
        parent::addAttribute('numero_protocolo');
        parent::addAttribute('ementa');
        parent::addAttribute('assunto');
        parent::addAttribute('autor_principal');
        parent::addAttribute('coautores');
        parent::addAttribute('tipo_autor');
        parent::addAttribute('situacao_status');
        parent::addAttribute('departamento_gabinete');
        parent::addAttribute('sessao_vinculada');
        parent::addAttribute('sessao_apresentacao');
        parent::addAttribute('sessao_apreciacao');
        parent::addAttribute('data_sessao');
        parent::addAttribute('status_sessao');
        parent::addAttribute('despacho_texto');
        parent::addAttribute('downloads');
        parent::addAttribute('created_at');
        parent::addAttribute('updated_at');
    }

    public function getProcessoLegislativoAnexos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('processo_legislativo_id', '=', $this->id));
        $criteria->setProperty('order', 'ordem');
        $criteria->setProperty('direction', 'asc');

        return ProcessoLegislativoAnexo::getObjects($criteria);
    }

    public function getProcessoLegislativoTramitacoes()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('processo_legislativo_id', '=', $this->id));
        $criteria->setProperty('order', 'data_tramitacao');
        $criteria->setProperty('direction', 'desc');

        return ProcessoLegislativoTramitacao::getObjects($criteria);
    }
}
