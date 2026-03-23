<?php
/**
 * SystemUnitForm
 *
 * @version    1.0
 * @package    control
 * @subpackage admin
 */
class SystemUnitForm extends TStandardForm
{
    protected $form;
    private static $formName = 'form_SystemUnit';

    /**
     * Class constructor
     * Creates the page and the registration form
     */
    public function __construct()
    {
        parent::__construct();

        $ini = AdiantiApplicationConfig::get();

        $this->setDatabase('permission');
        $this->setActiveRecord('SystemUnit');

        $this->form = new BootstrapFormBuilder(self::$formName);
        $this->form->setFormTitle(_t('Unit'));

        $id = new TEntry('id');
        $name = new TEntry('name');
        $email = new TEntry('email');
        $cep = new TEntry('cep');
        $rua = new TEntry('rua');
        $numero = new TEntry('numero');
        $bairro = new TEntry('bairro');
        $complemento = new TEntry('complemento');
        $cnpj = new TEntry('cnpj');
        $telefone01 = new TEntry('telefone01');
        $telefone02 = new TEntry('telefone02');
        $logo = new TEntry('logo');
        $longitude = new TEntry('longitude');
        $latitude = new TEntry('latitude');
        $system_entidade_id = new TDBCombo('system_entidade_id', 'minierp', 'SystemEntidade', 'id', '{nome}', 'nome asc');
        $cidade_id = new TDBCombo('cidade_id', 'minierp', 'Cidade', 'id', '{nome} ({estado->sigla})', 'nome asc');
        $button_buscar_cep = new TButton('button_buscar_cep');
        $button_localizar_endereco = new TButton('button_localizar_endereco');

        $button_buscar_cep->setAction(new TAction([__CLASS__, 'onBuscarCep']), 'Buscar CEP');
        $button_localizar_endereco->setAction(new TAction([__CLASS__, 'onLocalizarEndereco']), 'Localizar mapa');
        $button_buscar_cep->addStyleClass('btn-default');
        $button_localizar_endereco->addStyleClass('btn-default');
        $button_buscar_cep->setImage('fas:search #000000');
        $button_localizar_endereco->setImage('fas:map-marker-alt #e74c3c');

        $id->setEditable(false);

        $id->setSize('100%');
        $name->setSize('100%');
        $email->setSize('100%');
        $cep->setSize('calc(100% - 120px)');
        $rua->setSize('100%');
        $numero->setSize('100%');
        $bairro->setSize('100%');
        $complemento->setSize('100%');
        $cnpj->setSize('100%');
        $telefone01->setSize('100%');
        $telefone02->setSize('100%');
        $logo->setSize('100%');
        $longitude->setSize('100%');
        $latitude->setSize('100%');
        $system_entidade_id->setSize('100%');
        $cidade_id->setSize('100%');
        $system_entidade_id->enableSearch();
        $cidade_id->enableSearch();

        $cnpj->setMask('99.999.999/9999-99');
        $cep->setMask('99999-999');
        $telefone01->setMask('(99) 99999-9999');
        $telefone02->setMask('(99) 99999-9999');

        $name->addValidation(_t('Name'), new TRequiredValidator);
        $email->addValidation(_t('Email'), new TEmailValidator);

        $this->form->addFields([new TFormSeparator('Dados gerais')]);

        $row = $this->form->addFields([new TLabel('ID', null, null, null, '100%'), $id], [new TLabel(_t('Name'), '#ff0000', null, null, '100%'), $name]);
        $row->layout = ['col-sm-2', 'col-sm-10'];

        if (!empty($ini['general']['multi_database']) && $ini['general']['multi_database'] == '1')
        {
            $database = new TCombo('connection_name');
            $database->addItems(SystemDatabaseInformationService::getConnections());
            $database->setSize('100%');
            $row = $this->form->addFields([new TLabel(_t('Database'), null, null, null, '100%'), $database]);
            $row->layout = ['col-sm-12'];
        }

        $row = $this->form->addFields([new TLabel(_t('Email'), null, null, null, '100%'), $email], [new TLabel('CNPJ', null, null, null, '100%'), $cnpj]);
        $row->layout = ['col-sm-6', 'col-sm-6'];

        $this->form->addFields([new TFormSeparator('Endereco')]);

        $row = $this->form->addFields([new TLabel('CEP', null, null, null, '100%'), $cep, $button_buscar_cep], [new TLabel('Numero', null, null, null, '100%'), $numero]);
        $row->layout = ['col-sm-6', 'col-sm-6'];

        $row = $this->form->addFields([new TLabel('Cidade', null, null, null, '100%'), $cidade_id], [new TLabel('Bairro', null, null, null, '100%'), $bairro]);
        $row->layout = ['col-sm-6', 'col-sm-6'];

        $row = $this->form->addFields([new TLabel('Rua', null, null, null, '100%'), $rua], [new TLabel('Complemento', null, null, null, '100%'), $complemento]);
        $row->layout = ['col-sm-6', 'col-sm-6'];

        $row = $this->form->addFields([new TLabel('Longitude', null, null, null, '100%'), $longitude], [new TLabel('Latitude', null, null, null, '100%'), $latitude], [$button_localizar_endereco]);
        $row->layout = ['col-sm-4', 'col-sm-4', 'col-sm-4'];

        $this->form->addFields([new TFormSeparator('Contato e vinculacao')]);

        $row = $this->form->addFields([new TLabel('Telefone 01', null, null, null, '100%'), $telefone01], [new TLabel('Telefone 02', null, null, null, '100%'), $telefone02]);
        $row->layout = ['col-sm-6', 'col-sm-6'];

        $row = $this->form->addFields([new TLabel('Entidade', null, null, null, '100%'), $system_entidade_id], [new TLabel('Logo', null, null, null, '100%'), $logo]);
        $row->layout = ['col-sm-6', 'col-sm-6'];

        $btn = $this->form->addAction(_t('Save'), new TAction([$this, 'onSave']), 'far:save');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addActionLink(_t('Clear'), new TAction([$this, 'onEdit']), 'fa:eraser red');
        $this->form->addActionLink(_t('Back'), new TAction(['SystemUnitList', 'onReload']), 'far:arrow-alt-circle-left blue');

        parent::setTargetContainer('adianti_right_panel');

        $btnClose = new TButton('closeCurtain');
        $btnClose->class = 'btn btn-sm btn-default';
        $btnClose->style = 'margin-right:10px;';
        $btnClose->onClick = "Template.closeRightPanel();";
        $btnClose->setLabel(_t('Close'));
        $btnClose->setImage('fas:times');

        $this->form->addHeaderWidget($btnClose);

        parent::add($this->form);

        $style = new TStyle('right-panel > .container-part[page-name=SystemUnitForm]');
        $style->width = '70% !important';
        $style->show(true);
    }

    public static function onBuscarCep($param = null)
    {
        try
        {
            if (empty($param['cep']))
            {
                throw new Exception('Informe o CEP para localizar o endereco');
            }

            TTransaction::open('minierp');
            $dadosCep = AddressLookupService::getAddressDataByCep($param['cep']);
            TTransaction::close();

            if (!$dadosCep)
            {
                throw new Exception('Endereco nao encontrado para o CEP informado');
            }

            $object = new stdClass;
            $object->cidade_id = $dadosCep->cidade_id ?? null;
            $object->rua = $dadosCep->rua ?? null;
            $object->bairro = $dadosCep->bairro ?? null;
            $object->longitude = $dadosCep->longitude ?? null;
            $object->latitude = $dadosCep->latitude ?? null;

            TForm::sendData(self::$formName, $object);
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }

    public static function onLocalizarEndereco($param = null)
    {
        try
        {
            TTransaction::open('minierp');
            $coordinates = AddressLookupService::locateCoordinates($param['rua'] ?? null, $param['bairro'] ?? null, $param['cidade_id'] ?? null);
            TTransaction::close();

            if (!$coordinates)
            {
                throw new Exception('Nao foi possivel localizar longitude e latitude para este endereco');
            }

            $object = new stdClass;
            $object->longitude = $coordinates->longitude ?? null;
            $object->latitude = $coordinates->latitude ?? null;

            TForm::sendData(self::$formName, $object);
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }
}
