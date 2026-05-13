<?php

class ProcessoAdministrativo extends TRecord
{
    const TABLENAME  = 'processo_administrativo';
    const PRIMARYKEY = 'id';
    const IDPOLICY   = 'serial';

    public function __construct($id = null, $callObjectLoad = true)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('numero_processo');
        parent::addAttribute('numero_protocolo');
        parent::addAttribute('tipo_processo');
        parent::addAttribute('assunto');
        parent::addAttribute('ementa');
        parent::addAttribute('descricao');
        parent::addAttribute('status');
        parent::addAttribute('status_leitura');
        parent::addAttribute('nivel_sigilo');
        parent::addAttribute('sigilo_motivo');
        parent::addAttribute('sigilo_alterado_em');
        parent::addAttribute('sigilo_alterado_por');
        parent::addAttribute('remetente_user_id');
        parent::addAttribute('remetente_nome');
        parent::addAttribute('autor_nome');
        parent::addAttribute('departamento_origem');
        parent::addAttribute('departamento_origem_id');
        parent::addAttribute('departamento_atual');
        parent::addAttribute('departamento_atual_id');
        parent::addAttribute('departamento_destino');
        parent::addAttribute('departamento_destino_id');
        parent::addAttribute('destinatario_nome');
        parent::addAttribute('responsavel');
        parent::addAttribute('permissao_grupo');
        parent::addAttribute('requerente');
        parent::addAttribute('solicitante');
        parent::addAttribute('data_abertura');
        parent::addAttribute('data_envio');
        parent::addAttribute('prazo_tipo');
        parent::addAttribute('prazo_dias');
        parent::addAttribute('prazo_inicio');
        parent::addAttribute('prazo_final');
        parent::addAttribute('prazo_status');
        parent::addAttribute('prazo_interrompido_em');
        parent::addAttribute('prazo_prorrogado_ate');
        parent::addAttribute('lido_em');
        parent::addAttribute('rascunho_em');
        parent::addAttribute('arquivado_em');
        parent::addAttribute('observacoes');
        parent::addAttribute('integra_gerada_em');
        parent::addAttribute('downloads');
        parent::addAttribute('created_at');
        parent::addAttribute('updated_at');
    }

    public function get_prazo_restante_dias(): ?int
    {
        if (empty($this->prazo_final)) {
            return null;
        }

        $today = new DateTime(date('Y-m-d'));
        $final = new DateTime(substr($this->prazo_final, 0, 10));

        return (int) $today->diff($final)->format('%r%a');
    }

    public function getProcessoAdministrativoAnexos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('processo_administrativo_id', '=', $this->id));
        $criteria->setProperty('order', 'ordem');
        $criteria->setProperty('direction', 'asc');

        return ProcessoAdministrativoAnexo::getObjects($criteria);
    }

    public function getProcessoAdministrativoTramitacoes()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('processo_administrativo_id', '=', $this->id));
        $criteria->setProperty('order', 'data_tramitacao');
        $criteria->setProperty('direction', 'desc');

        return ProcessoAdministrativoTramitacao::getObjects($criteria);
    }
}
