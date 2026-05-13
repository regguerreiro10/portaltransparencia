<?php

use Adianti\Base\AdiantiFileSaveTrait;
use Adianti\Widget\Form\THtmlEditor;
use Adianti\Widget\Form\TMultiFile;

class MemorandoForm extends TPage
{
    use AdiantiFileSaveTrait;

    protected BootstrapFormBuilder $form;
    private TFieldList $destinatariosList;
    private static $database = 'minierp';
    private static $activeRecord = 'Memorando';
    private static $primaryKey = 'id';
    private static $formName = 'form_MemorandoForm';

    public function __construct($param = null)
    {
        parent::__construct();

        if (!empty($param['target_container'])) {
            $this->adianti_target_container = $param['target_container'];
        }

        $this->form = new BootstrapFormBuilder(self::$formName);
        $this->form->setFormTitle('Cadastro e envio de memorandos');

        $id = new TEntry('id');
        $numero_memorando = new TEntry('numero_memorando');
        $status = new TCombo('status');
        $data_memorando = new TDateTime('data_memorando');
        $remetente_nome = new TEntry('remetente_nome');
        $departamento_origem_nome = new TEntry('departamento_origem_nome');
        $tipo = new TCombo('tipo');
        $template_codigo = new TCombo('template_codigo');
        $assunto = new TEntry('assunto');
        $texto_memorando = new THtmlEditor('texto_memorando');
        $anexos = new TMultiFile('anexos');
        $processo_referencia = new TEntry('processo_referencia');

        $destinatario_tipo = new TCombo('destinatario_tipo[]');
        $destinatario_usuario = new TDBCombo('destinatario_usuario[]', 'minierp', 'SystemUsers', 'id', '{name}', 'name asc');
        $destinatario_departamento = new TDBCombo('destinatario_departamento[]', 'minierp', 'SystemDepartamento', 'id', '{nome}', 'nome asc');
        $this->destinatariosList = new TFieldList;

        $status->addItems(MemorandoHelper::getStatusOptions());
        $tipo->addItems([
            'Normal' => 'Normal',
            'Com copia' => 'Com copia',
            'Resposta' => 'Resposta',
            'Encaminhamento' => 'Encaminhamento',
        ]);
        $template_codigo->addItems(['' => 'Selecione'] + MemorandoHelper::getTemplates());
        $destinatario_tipo->addItems([
            'Para' => 'Para',
            'Copia' => 'Copia',
        ]);

        $template_codigo->setChangeAction(new TAction([$this, 'onChangeTemplate']));

        $assunto->addValidation('Assunto', new TRequiredValidator());
        $texto_memorando->addValidation('Texto do memorando', new TRequiredValidator());
        $tipo->addValidation('Tipo', new TRequiredValidator());

        $id->setEditable(false);
        $numero_memorando->setEditable(MemorandoHelper::isAdmin());
        $status->setEditable(false);
        $data_memorando->setEditable(false);
        $remetente_nome->setEditable(false);
        $departamento_origem_nome->setEditable(false);
        $status->setValue('Enviado');
        $tipo->setValue('Normal');
        $data_memorando->setValue(date('d/m/Y H:i'));

        $status->enableSearch();
        $tipo->enableSearch();
        $template_codigo->enableSearch();
        $destinatario_tipo->enableSearch();
        $destinatario_departamento->enableSearch();
        $destinatario_usuario->enableSearch();

        $data_memorando->setMask('dd/mm/yyyy hh:ii');
        $data_memorando->setDatabaseMask('yyyy-mm-dd hh:ii:ss');

        $anexos->enableFileHandling();
        $anexos->setAllowedExtensions(['pdf', 'doc', 'docx', 'odt', 'txt', 'png', 'jpg', 'jpeg', 'xlsx', 'zip']);

        $id->setSize('100%');
        $numero_memorando->setSize('100%');
        $status->setSize('100%');
        $data_memorando->setSize('100%');
        $remetente_nome->setSize('100%');
        $departamento_origem_nome->setSize('100%');
        $tipo->setSize('100%');
        $template_codigo->setSize('100%');
        $assunto->setSize('100%');
        $texto_memorando->setSize('100%', 320);
        $anexos->setSize('100%');
        $processo_referencia->setSize('100%');
        $destinatario_tipo->setSize('100%');
        $destinatario_usuario->setSize('100%');
        $destinatario_departamento->setSize('100%');

        $this->destinatariosList->addField(new TLabel('Tipo', null, '14px', null), $destinatario_tipo, ['width' => '15%']);
        $this->destinatariosList->addField(new TLabel('Destinatario', null, '14px', null), $destinatario_usuario, ['width' => '45%']);
        $this->destinatariosList->addField(new TLabel('Departamento destino', null, '14px', null), $destinatario_departamento, ['width' => '40%']);
        $this->destinatariosList->width = '100%';
        $this->destinatariosList->name = 'fieldList_destinatarios';
        $this->destinatariosList->setRemoveAction(null, 'fas:times #dd5a43', 'Excluir');

        $this->form->addField($destinatario_tipo);
        $this->form->addField($destinatario_usuario);
        $this->form->addField($destinatario_departamento);

        $row1 = $this->form->addFields(
            [new TLabel('Id:', null, '14px', null, '100%'), $id],
            [new TLabel('Numero do memorando:', MemorandoHelper::isAdmin() ? '#ff0000' : null, '14px', null, '100%'), $numero_memorando],
            [new TLabel('Status:', null, '14px', null, '100%'), $status],
            [new TLabel('Data/hora:', null, '14px', null, '100%'), $data_memorando]
        );
        $row1->layout = ['col-sm-1', 'col-sm-4', 'col-sm-3', 'col-sm-4'];

        $row2 = $this->form->addFields(
            [new TLabel('Remetente:', null, '14px', null, '100%'), $remetente_nome],
            [new TLabel('Departamento origem:', null, '14px', null, '100%'), $departamento_origem_nome]
        );
        $row2->layout = ['col-sm-6', 'col-sm-6'];

        $row3 = $this->form->addFields(
            [new TLabel('Tipo:', '#ff0000', '14px', null, '100%'), $tipo],
            [new TLabel('Modelo padrao:', null, '14px', null, '100%'), $template_codigo],
            [new TLabel('Referencia de processo:', null, '14px', null, '100%'), $processo_referencia]
        );
        $row3->layout = ['col-sm-3', 'col-sm-4', 'col-sm-5'];

        $row4 = $this->form->addFields(
            [new TLabel('Assunto:', '#ff0000', '14px', null, '100%'), $assunto]
        );
        $row4->layout = ['col-sm-12'];

        $row5 = $this->form->addFields(
            [new TFormSeparator('Destinatarios e copias', '#333', '18', '#eee')]
        );
        $row5->layout = ['col-sm-12'];

        $row6 = $this->form->addFields([$this->destinatariosList]);
        $row6->layout = ['col-sm-12'];

        $row7 = $this->form->addFields(
            [new TLabel('Texto do memorando:', '#ff0000', '14px', null, '100%'), $texto_memorando]
        );
        $row7->layout = ['col-sm-12'];

        $row8 = $this->form->addFields(
            [new TLabel('Anexos:', null, '14px', null, '100%'), $anexos]
        );
        $row8->layout = ['col-sm-12'];

        $btnSave = $this->form->addAction('Salvar e enviar', new TAction([$this, 'onSave']), 'fas:paper-plane #ffffff');
        $btnSave->addStyleClass('btn-primary');
        $this->form->addAction('Limpar formulario', new TAction([$this, 'onClear']), 'fas:eraser #dd5a43');
        $this->form->addAction('Voltar', new TAction(['MemorandoList', 'onShow']), 'fas:arrow-left #000000');

        parent::setTargetContainer('adianti_right_panel');

        $btnClose = new TButton('closeCurtain');
        $btnClose->class = 'btn btn-sm btn-default';
        $btnClose->style = 'margin-right:10px;';
        $btnClose->onClick = "Template.closeRightPanel();";
        $btnClose->setLabel('Fechar');
        $btnClose->setImage('fas:times');
        $this->form->addHeaderWidget($btnClose);

        $this->bootstrapCurrentUser();

        if (!empty($param)) {
            $this->applyContextParams($param);
        }

        parent::add($this->form);
    }

    public static function onChangeTemplate($param = null)
    {
        $data = new stdClass;
        $codigo = $param['template_codigo'] ?? null;
        if (!empty($codigo)) {
            $data->texto_memorando = MemorandoHelper::getTemplateContent($codigo);
        }
        TForm::sendData(self::$formName, $data, false, false);
    }

    public function onSave($param = null)
    {
        try {
            TTransaction::open(self::$database);
            MemorandoSchemaHelper::ensureSchema();

            $this->form->validate();

            $data = $this->form->getData();
            $fieldListData = $this->destinatariosList->getPostData();
            $this->validateDestinatarios($fieldListData);

            $isNew = empty($data->id);
            $object = $isNew ? new Memorando() : new Memorando((int) $data->id);

            if (!$isNew && !MemorandoHelper::canEditMemorando($object)) {
                throw new Exception('Voce nao tem permissao para editar este memorando.');
            }

            $context = MemorandoHelper::getCurrentUserContext();
            $currentNumber = $isNew ? null : $object->numero_memorando;

            $object->assunto = $data->assunto;
            $object->texto_memorando = $data->texto_memorando;
            $object->tipo = $data->tipo;
            $object->template_codigo = $data->template_codigo ?: null;
            $object->template_nome = MemorandoHelper::getTemplates()[$data->template_codigo] ?? null;
            $object->remetente_user_id = $context['user_id'];
            $object->remetente_nome = $context['name'];
            $object->departamento_origem_id = $context['primary_department_id'];
            $object->departamento_origem_nome = $context['primary_department_name'];
            $object->processo_referencia = $data->processo_referencia ?: null;
            $object->downloads = (int) ($object->downloads ?? 0);

            if ($isNew || empty($object->numero_memorando)) {
                $object->numero_memorando = $this->generateNumeroMemorando();
            } elseif (!MemorandoHelper::isAdmin()) {
                $object->numero_memorando = $currentNumber;
            } else {
                $object->numero_memorando = $data->numero_memorando;
            }

            $object->status = 'Enviado';
            $object->data_memorando = !empty($object->data_memorando) ? $object->data_memorando : date('Y-m-d H:i:s');

            if (!empty($param['responder_id'])) {
                $object->memorando_pai_id = (int) $param['responder_id'];
                $object->tipo = 'Resposta';
            }

            if (!empty($param['encaminhar_id'])) {
                $object->memorando_pai_id = (int) $param['encaminhar_id'];
                $object->tipo = 'Encaminhamento';
            }

            $now = date('Y-m-d H:i:s');
            $object->updated_at = $now;
            if (empty($object->created_at)) {
                $object->created_at = $now;
            }

            $object->store();

            MemorandoDestinatario::where('memorando_id', '=', $object->id)->delete();
            $savedDestinatarios = $this->storeDestinatarios($object, $fieldListData);

            $attachments = $this->saveFiles(
                $object,
                $data,
                'anexos',
                'app/files/memorandos',
                'MemorandoAnexo',
                'arquivo',
                'memorando_id'
            );
            $this->updateAttachmentMetadata($attachments);

            $descricao = $isNew
                ? 'Memorando criado e enviado para ' . count($savedDestinatarios) . ' destinatario(s).'
                : 'Memorando atualizado pelo remetente.';

            MemorandoHelper::createTramitacao($object->id, $isNew ? 'Criado' : 'Editado', $object->status, $descricao);

            foreach ($savedDestinatarios as $destinatario) {
                MemorandoHelper::createTramitacao(
                    $object->id,
                    'Enviado',
                    'Enviado',
                    sprintf(
                        'Enviado para %s (%s).',
                        $destinatario->destinatario_nome,
                        $destinatario->departamento_destino_nome
                    ),
                    $destinatario->id
                );
            }

            if (!empty($param['responder_id'])) {
                $this->markParentAsResponded((int) $param['responder_id']);
            }

            $data->id = $object->id;
            $data->numero_memorando = $object->numero_memorando;
            $data->status = $object->status;
            $this->form->setData($data);

            TTransaction::close();

            TToast::show('success', 'Memorando salvo com sucesso.', 'topRight', 'far:check-circle');
            TApplication::loadPage('MemorandoList', 'onShow', []);
            TScript::create("Template.closeRightPanel();");
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
            $this->form->setData($this->form->getData());
            TTransaction::rollback();
        }
    }

    public function onEdit($param)
    {
        try {
            if (!isset($param['key'])) {
                $this->form->clear(true);
                $this->bootstrapCurrentUser();
                return;
            }

            TTransaction::open(self::$database);
            MemorandoSchemaHelper::ensureSchema();
            $object = new Memorando((int) $param['key']);

            if (!MemorandoHelper::canAccessMemorando($object)) {
                throw new Exception('Voce nao tem permissao para acessar este memorando.');
            }

            $data = (object) $object->toArray();
            $data->anexos = [];

            foreach ($object->getMemorandoAnexos() as $anexo) {
                $data->anexos[$anexo->id] = $anexo->arquivo;
            }

            $destinatarios = $object->getMemorandoDestinatarios();
            $total = count($destinatarios);
            if ($total > 1) {
                TFieldList::addRows('fieldList_destinatarios', $total - 1, 1);
            }

            $data->destinatario_tipo = [];
            $data->destinatario_usuario = [];
            $data->destinatario_departamento = [];
            foreach ($destinatarios as $destinatario) {
                $data->destinatario_tipo[] = $destinatario->tipo_destino;
                $data->destinatario_usuario[] = $destinatario->system_user_id;
                $data->destinatario_departamento[] = $destinatario->system_departamento_id;
            }

            $this->form->setData($data);
            TTransaction::close();
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }

    public function onClear($param)
    {
        $this->form->clear(true);
        $this->bootstrapCurrentUser();
    }

    public function onShow($param = null)
    {
        $this->destinatariosList->addHeader();
        $this->destinatariosList->addDetail(new stdClass);
        $this->destinatariosList->addCloneAction(null, 'fas:plus #69aa46', 'Clonar');
    }

    private function bootstrapCurrentUser(): void
    {
        $context = MemorandoHelper::getCurrentUserContext();
        $data = new stdClass;
        $data->status = 'Enviado';
        $data->data_memorando = date('d/m/Y H:i');
        $data->remetente_nome = $context['name'];
        $data->departamento_origem_nome = $context['primary_department_name'];
        $this->form->setData($data);
    }

    private function applyContextParams(array $param): void
    {
        if (empty($param['responder_id']) && empty($param['encaminhar_id'])) {
            return;
        }

        try {
            TTransaction::open(self::$database);
            MemorandoSchemaHelper::ensureSchema();
            $origemId = !empty($param['responder_id']) ? (int) $param['responder_id'] : (int) $param['encaminhar_id'];
            $memorando = new Memorando($origemId);

            if (!MemorandoHelper::canAccessMemorando($memorando)) {
                throw new Exception('Voce nao tem permissao para usar este memorando como base.');
            }

            $data = $this->form->getData();
            $data->assunto = !empty($param['responder_id']) ? 'RES: ' . $memorando->assunto : 'ENC: ' . $memorando->assunto;
            $data->texto_memorando = '<p></p><hr><p><strong>Referencia:</strong> ' . $memorando->numero_memorando . '</p>' . $memorando->texto_memorando;
            $data->processo_referencia = $memorando->processo_referencia;
            $data->tipo = !empty($param['responder_id']) ? 'Resposta' : 'Encaminhamento';
            $this->form->setData($data);
            TTransaction::close();
        } catch (Exception $e) {
            TTransaction::rollback();
            new TMessage('error', $e->getMessage());
        }
    }

    private function validateDestinatarios(array $rows): void
    {
        $validRows = 0;
        foreach ($rows as $row) {
            if (!empty($row->destinatario_usuario) && !empty($row->destinatario_departamento)) {
                $validRows++;
            }
        }

        if ($validRows === 0) {
            throw new Exception('Informe ao menos um destinatario com departamento.');
        }
    }

    private function storeDestinatarios(Memorando $memorando, array $rows): array
    {
        $saved = [];
        $seen = [];
        $hasCopy = false;

        foreach ($rows as $row) {
            if (empty($row->destinatario_usuario) || empty($row->destinatario_departamento)) {
                continue;
            }

            $key = $row->destinatario_tipo . '-' . $row->destinatario_usuario . '-' . $row->destinatario_departamento;
            if (isset($seen[$key])) {
                continue;
            }
            $seen[$key] = true;

            $destinatario = new MemorandoDestinatario();
            $destinatario->memorando_id = $memorando->id;
            $destinatario->tipo_destino = $row->destinatario_tipo ?: 'Para';
            $destinatario->system_user_id = (int) $row->destinatario_usuario;
            $destinatario->destinatario_nome = MemorandoHelper::getUserNameById($row->destinatario_usuario);
            $destinatario->system_departamento_id = (int) $row->destinatario_departamento;
            $destinatario->departamento_destino_nome = MemorandoHelper::getDepartmentNameById($row->destinatario_departamento);
            $destinatario->status = 'Enviado';
            $destinatario->recebido_em = date('Y-m-d H:i:s');
            $destinatario->created_at = date('Y-m-d H:i:s');
            $destinatario->updated_at = date('Y-m-d H:i:s');
            $destinatario->store();
            $saved[] = $destinatario;

            if ($destinatario->tipo_destino === 'Copia') {
                $hasCopy = true;
            }
        }

        if ($hasCopy && $memorando->tipo === 'Normal') {
            $memorando->tipo = 'Com copia';
            $memorando->store();
        }

        return $saved;
    }

    private function updateAttachmentMetadata(array $attachments): void
    {
        foreach ($attachments as $index => $attachment) {
            if (!$attachment instanceof MemorandoAnexo) {
                continue;
            }

            $attachment->ordem = $index + 1;
            if (empty($attachment->nome) && !empty($attachment->arquivo)) {
                $attachment->nome = basename($attachment->arquivo);
            }
            $attachment->store();
        }
    }

    private function generateNumeroMemorando(): string
    {
        $conn = TTransaction::get();
        $result = $conn->query('SELECT COALESCE(MAX(id), 0) + 1 AS next_id FROM memorando');
        $nextId = (int) $result->fetchColumn();
        return sprintf('MEM-%s-%06d', date('Y'), $nextId);
    }

    private function markParentAsResponded(int $memorandoId): void
    {
        $parent = new Memorando($memorandoId);
        $context = MemorandoHelper::getCurrentUserContext();
        $updated = false;

        foreach ($parent->getMemorandoDestinatarios() as $destinatario) {
            if ((int) $destinatario->system_user_id === (int) $context['user_id']) {
                $destinatario->status = 'Respondido';
                $destinatario->respondido_em = date('Y-m-d H:i:s');
                $destinatario->updated_at = date('Y-m-d H:i:s');
                $destinatario->store();
                MemorandoHelper::createTramitacao(
                    $parent->id,
                    'Respondido',
                    'Respondido',
                    'Resposta registrada a partir do memorando vinculado.',
                    $destinatario->id
                );
                $updated = true;
            }
        }

        if (!$updated) {
            $parent->status = 'Respondido';
            $parent->updated_at = date('Y-m-d H:i:s');
            $parent->store();
            MemorandoHelper::createTramitacao($parent->id, 'Respondido', 'Respondido', 'Resposta registrada.');
        } else {
            MemorandoHelper::updateOverallStatus($parent);
        }
    }
}
