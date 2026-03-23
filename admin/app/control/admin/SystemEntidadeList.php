<?php

use Adianti\Base\AdiantiStandardListExportTrait;

class SystemEntidadeList extends TPage
{
    private $datagrid;
    private $datagrid_form;
    private $pageNavigation;
    private $loaded;
    private $filter_criteria;
    private $database = 'minierp';
    private static $activeRecord = 'SystemEntidade';
    private static $primaryKey = 'id';
    private static $formName = 'formList_SystemEntidade';
    private $showMethods = ['onReload', 'onSearch'];
    private $limit = 20;

    use AdiantiStandardListExportTrait;

    public function __construct($param = null)
    {
        parent::__construct();

        if (!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        $criteria_cidade_id = new TCriteria();

        $id = new TEntry('id');
        $nome = new TEntry('nome');
        $cnpj = new TEntry('cnpj');
        $email = new TEntry('email');
        $cep = new TEntry('cep');
        $numero = new TEntry('numero');
        $rua = new TEntry('rua');
        $bairro = new TEntry('bairro');
        $telefone01 = new TEntry('telefone01');
        $telefone02 = new TEntry('telefone02');
        $longitude = new TEntry('longitude');
        $latitude = new TEntry('latitude');
        $logo = new TEntry('logo');
        $complemento = new TEntry('complemento');
        $cidade_id = new TDBCombo('cidade_id', 'minierp', 'Cidade', 'id', '{nome} ({estado->sigla})', 'nome asc', $criteria_cidade_id);

        $searchAction = new TAction([$this, 'onSearch'], ['static' => '1', 'target_container' => $param['target_container'] ?? null]);

        $id->exitOnEnter();
        $nome->exitOnEnter();
        $cnpj->exitOnEnter();
        $email->exitOnEnter();
        $cep->exitOnEnter();
        $numero->exitOnEnter();
        $rua->exitOnEnter();
        $bairro->exitOnEnter();
        $telefone01->exitOnEnter();
        $telefone02->exitOnEnter();
        $longitude->exitOnEnter();
        $latitude->exitOnEnter();
        $logo->exitOnEnter();
        $complemento->exitOnEnter();

        $id->setExitAction($searchAction);
        $nome->setExitAction($searchAction);
        $cnpj->setExitAction($searchAction);
        $email->setExitAction($searchAction);
        $cep->setExitAction($searchAction);
        $numero->setExitAction($searchAction);
        $rua->setExitAction($searchAction);
        $bairro->setExitAction($searchAction);
        $telefone01->setExitAction($searchAction);
        $telefone02->setExitAction($searchAction);
        $longitude->setExitAction($searchAction);
        $latitude->setExitAction($searchAction);
        $logo->setExitAction($searchAction);
        $complemento->setExitAction($searchAction);
        $cidade_id->setChangeAction($searchAction);

        $id->setSize('100%');
        $nome->setSize('100%');
        $cnpj->setSize('100%');
        $email->setSize('100%');
        $cep->setSize('100%');
        $numero->setSize('100%');
        $rua->setSize('100%');
        $bairro->setSize('100%');
        $telefone01->setSize('100%');
        $telefone02->setSize('100%');
        $longitude->setSize('100%');
        $latitude->setSize('100%');
        $logo->setSize('100%');
        $complemento->setSize('100%');
        $cidade_id->setSize('100%');

        $this->datagrid = new TDataGrid;
        $this->datagrid_form = new TForm(self::$formName);
        $this->datagrid_form->onsubmit = 'return false';

        $this->datagrid = new BootstrapDatagridWrapper($this->datagrid);
        $this->filter_criteria = new TCriteria;

        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);

        $column_id = new TDataGridColumn('id', 'ID', 'center', '70px');
        $column_nome = new TDataGridColumn('nome', 'Nome', 'left');
        $column_cnpj = new TDataGridColumn('cnpj', 'CNPJ', 'left');
        $column_email = new TDataGridColumn('email', 'Email', 'left');
        $column_cep = new TDataGridColumn('cep', 'CEP', 'left');
        $column_numero = new TDataGridColumn('numero', 'Numero', 'left');
        $column_rua = new TDataGridColumn('rua', 'Rua', 'left');
        $column_bairro = new TDataGridColumn('bairro', 'Bairro', 'left');
        $column_telefone01 = new TDataGridColumn('telefone01', 'Telefone 01', 'left');
        $column_telefone02 = new TDataGridColumn('telefone02', 'Telefone 02', 'left');
        $column_longitude = new TDataGridColumn('longitude', 'Longitude', 'left');
        $column_latitude = new TDataGridColumn('latitude', 'Latitude', 'left');
        $column_logo = new TDataGridColumn('logo', 'Logo', 'left');
        $column_complemento = new TDataGridColumn('complemento', 'Complemento', 'left');
        $column_cidade = new TDataGridColumn('cidade->nome', 'Cidade', 'left');

        $column_cnpj->setTransformer([__CLASS__, 'formatCnpj']);
        $column_cep->setTransformer([__CLASS__, 'formatCep']);
        $column_telefone01->setTransformer([__CLASS__, 'formatPhone']);
        $column_telefone02->setTransformer([__CLASS__, 'formatPhone']);
        $column_cidade->setTransformer(function ($value, $object) {
            if (!empty($object->cidade) && !empty($object->cidade->estado->sigla))
            {
                return $object->cidade->nome . ' / ' . $object->cidade->estado->sigla;
            }

            return $value;
        });

        $order_id = new TAction([$this, 'onReload']);
        $order_id->setParameter('order', 'id');
        $column_id->setAction($order_id);

        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_nome);
        $this->datagrid->addColumn($column_cnpj);
        $this->datagrid->addColumn($column_email);
        $this->datagrid->addColumn($column_cep);
        $this->datagrid->addColumn($column_numero);
        $this->datagrid->addColumn($column_rua);
        $this->datagrid->addColumn($column_bairro);
        $this->datagrid->addColumn($column_telefone01);
        $this->datagrid->addColumn($column_telefone02);
        $this->datagrid->addColumn($column_longitude);
        $this->datagrid->addColumn($column_latitude);
        $this->datagrid->addColumn($column_logo);
        $this->datagrid->addColumn($column_complemento);
        $this->datagrid->addColumn($column_cidade);

        $action_onEdit = new TDataGridAction(['SystemEntidadeForm', 'onEdit']);
        $action_onEdit->setUseButton(false);
        $action_onEdit->setButtonClass('btn btn-default btn-sm');
        $action_onEdit->setLabel(_t('Edit'));
        $action_onEdit->setImage('far:edit #478fca');
        $action_onEdit->setField(self::$primaryKey);
        $this->datagrid->addAction($action_onEdit);

        $action_onDelete = new TDataGridAction(['SystemEntidadeList', 'onDelete']);
        $action_onDelete->setUseButton(false);
        $action_onDelete->setButtonClass('btn btn-default btn-sm');
        $action_onDelete->setLabel(_t('Delete'));
        $action_onDelete->setImage('fas:trash-alt #dd5a43');
        $action_onDelete->setField(self::$primaryKey);
        $this->datagrid->addAction($action_onDelete);

        $this->datagrid->createModel();

        $tr = new TElement('tr');
        $this->datagrid->prependRow($tr);

        $tr->add(TElement::tag('td', ''));
        $tr->add(TElement::tag('td', ''));
        $tr->add(TElement::tag('td', $id));
        $tr->add(TElement::tag('td', $nome));
        $tr->add(TElement::tag('td', $cnpj));
        $tr->add(TElement::tag('td', $email));
        $tr->add(TElement::tag('td', $cep));
        $tr->add(TElement::tag('td', $numero));
        $tr->add(TElement::tag('td', $rua));
        $tr->add(TElement::tag('td', $bairro));
        $tr->add(TElement::tag('td', $telefone01));
        $tr->add(TElement::tag('td', $telefone02));
        $tr->add(TElement::tag('td', $longitude));
        $tr->add(TElement::tag('td', $latitude));
        $tr->add(TElement::tag('td', $logo));
        $tr->add(TElement::tag('td', $complemento));
        $tr->add(TElement::tag('td', $cidade_id));

        $this->datagrid_form->addField($id);
        $this->datagrid_form->addField($nome);
        $this->datagrid_form->addField($cnpj);
        $this->datagrid_form->addField($email);
        $this->datagrid_form->addField($cep);
        $this->datagrid_form->addField($numero);
        $this->datagrid_form->addField($rua);
        $this->datagrid_form->addField($bairro);
        $this->datagrid_form->addField($telefone01);
        $this->datagrid_form->addField($telefone02);
        $this->datagrid_form->addField($longitude);
        $this->datagrid_form->addField($latitude);
        $this->datagrid_form->addField($logo);
        $this->datagrid_form->addField($complemento);
        $this->datagrid_form->addField($cidade_id);

        $this->datagrid_form->setData(TSession::getValue(__CLASS__ . '_filter_data'));

        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->enableCounters();
        $this->pageNavigation->setAction(new TAction([$this, 'onReload']));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());

        $panel = new TPanelGroup('Entidades');
        $panel->datagrid = 'datagrid-container';
        $panel->getBody()->class .= ' table-responsive';
        $panel->addFooter($this->pageNavigation);

        $headerActions = new TElement('div');
        $headerActions->class = ' datagrid-header-actions ';

        $head_left_actions = new TElement('div');
        $head_left_actions->class = ' datagrid-header-actions-left-actions ';

        $head_right_actions = new TElement('div');
        $head_right_actions->class = ' datagrid-header-actions-left-actions ';

        $headerActions->add($head_left_actions);
        $headerActions->add($head_right_actions);

        $panel->add($headerActions);
        $this->datagrid_form->add($this->datagrid);
        $panel->add($this->datagrid_form);

        $button_new = new TButton('button_new');
        $button_new->setAction(new TAction(['SystemEntidadeForm', 'onEdit']), _t('New'));
        $button_new->addStyleClass('btn-default');
        $button_new->setImage('fas:plus #69aa46');
        $this->datagrid_form->addField($button_new);

        $button_refresh = new TButton('button_refresh');
        $button_refresh->setAction(new TAction(['SystemEntidadeList', 'onRefresh']), _t('Refresh'));
        $button_refresh->addStyleClass('btn-default');
        $button_refresh->setImage('fas:sync-alt #03a9f4');
        $this->datagrid_form->addField($button_refresh);

        $button_clear_filters = new TButton('button_clear_filters');
        $button_clear_filters->setAction(new TAction(['SystemEntidadeList', 'onClearFilters']), _t('Clear filters'));
        $button_clear_filters->addStyleClass('btn-default');
        $button_clear_filters->setImage('fas:eraser #f44336');
        $this->datagrid_form->addField($button_clear_filters);

        $dropdown_button_export = new TDropDown('Exportar', 'fas:file-export #2d3436');
        $dropdown_button_export->setPullSide('right');
        $dropdown_button_export->setButtonClass('btn btn-default waves-effect dropdown-toggle');
        $dropdown_button_export->addPostAction('CSV', new TAction(['SystemEntidadeList', 'onExportCsv'], ['static' => 1]), self::$formName, 'fas:file-csv #00b894');
        $dropdown_button_export->addPostAction('XLS', new TAction(['SystemEntidadeList', 'onExportXls'], ['static' => 1]), self::$formName, 'fas:file-excel #4CAF50');
        $dropdown_button_export->addPostAction('PDF', new TAction(['SystemEntidadeList', 'onExportPdf'], ['static' => 1]), self::$formName, 'far:file-pdf #e74c3c');

        $head_left_actions->add($button_new);
        $head_left_actions->add($button_refresh);
        $head_left_actions->add($button_clear_filters);
        $head_right_actions->add($dropdown_button_export);

        $container = new TVBox;
        $container->style = 'width: 100%';
        if (empty($param['target_container']))
        {
            $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        }

        $container->add($panel);

        parent::add($container);
    }

    public function onRefresh($param = null)
    {
        $this->onReload([]);
    }

    public function onClearFilters($param = null)
    {
        TSession::setValue(__CLASS__ . '_filter_data', null);
        TSession::setValue(__CLASS__ . '_filters', null);

        $this->onReload(['offset' => 0, 'first_page' => 1]);
        $this->datagrid_form->clear();
    }

    public function onSearch($param = null)
    {
        $data = $this->datagrid_form->getData();
        $filters = [];

        TSession::setValue(__CLASS__ . '_filter_data', null);
        TSession::setValue(__CLASS__ . '_filters', null);

        if (isset($data->id) && $data->id !== '')
        {
            $filters[] = new TFilter('id', '=', $data->id);
        }
        if (isset($data->nome) && $data->nome !== '')
        {
            $filters[] = new TFilter('nome', 'like', "%{$data->nome}%");
        }
        if (isset($data->cnpj) && $data->cnpj !== '')
        {
            $filters[] = new TFilter('cnpj', 'like', "%{$data->cnpj}%");
        }
        if (isset($data->email) && $data->email !== '')
        {
            $filters[] = new TFilter('email', 'like', "%{$data->email}%");
        }
        if (isset($data->cep) && $data->cep !== '')
        {
            $filters[] = new TFilter('cep', 'like', "%{$data->cep}%");
        }
        if (isset($data->numero) && $data->numero !== '')
        {
            $filters[] = new TFilter('numero', 'like', "%{$data->numero}%");
        }
        if (isset($data->rua) && $data->rua !== '')
        {
            $filters[] = new TFilter('rua', 'like', "%{$data->rua}%");
        }
        if (isset($data->bairro) && $data->bairro !== '')
        {
            $filters[] = new TFilter('bairro', 'like', "%{$data->bairro}%");
        }
        if (isset($data->telefone01) && $data->telefone01 !== '')
        {
            $filters[] = new TFilter('telefone01', 'like', "%{$data->telefone01}%");
        }
        if (isset($data->telefone02) && $data->telefone02 !== '')
        {
            $filters[] = new TFilter('telefone02', 'like', "%{$data->telefone02}%");
        }
        if (isset($data->longitude) && $data->longitude !== '')
        {
            $filters[] = new TFilter('longitude', 'like', "%{$data->longitude}%");
        }
        if (isset($data->latitude) && $data->latitude !== '')
        {
            $filters[] = new TFilter('latitude', 'like', "%{$data->latitude}%");
        }
        if (isset($data->logo) && $data->logo !== '')
        {
            $filters[] = new TFilter('logo', 'like', "%{$data->logo}%");
        }
        if (isset($data->complemento) && $data->complemento !== '')
        {
            $filters[] = new TFilter('complemento', 'like', "%{$data->complemento}%");
        }
        if (isset($data->cidade_id) && $data->cidade_id !== '')
        {
            $filters[] = new TFilter('cidade_id', '=', $data->cidade_id);
        }

        $this->datagrid_form->setData($data);
        TSession::setValue(__CLASS__ . '_filter_data', $data);
        TSession::setValue(__CLASS__ . '_filters', $filters);

        if (isset($param['static']) && $param['static'] == '1')
        {
            $class = get_class($this);
            $onReloadParam = ['offset' => 0, 'first_page' => 1, 'target_container' => $param['target_container'] ?? null];
            AdiantiCoreApplication::loadPage($class, 'onReload', $onReloadParam);
            TScript::create('$(".select2").prev().select2("close");');
        }
        else
        {
            $this->onReload(['offset' => 0, 'first_page' => 1]);
        }
    }

    public function onDelete($param = null)
    {
        if (isset($param['delete']) && $param['delete'] == 1)
        {
            try
            {
                $key = $param['key'];

                TTransaction::open($this->database);
                $object = new SystemEntidade($key, false);
                $object->delete();
                TTransaction::close();

                $this->onReload($param);
                new TMessage('info', AdiantiCoreTranslator::translate('Record deleted'));
            }
            catch (Exception $e)
            {
                new TMessage('error', $e->getMessage());
                TTransaction::rollback();
            }
        }
        else
        {
            $action = new TAction([$this, 'onDelete']);
            $action->setParameters($param);
            $action->setParameter('delete', 1);
            new TQuestion(AdiantiCoreTranslator::translate('Do you really want to delete ?'), $action);
        }
    }

    public function onReload($param = null)
    {
        try
        {
            TTransaction::open($this->database);

            $repository = new TRepository(self::$activeRecord);
            $criteria = clone $this->filter_criteria;

            if (empty($param['order']))
            {
                $param['order'] = 'id';
            }
            if (empty($param['direction']))
            {
                $param['direction'] = 'desc';
            }

            $criteria->setProperties($param);
            $criteria->setProperty('limit', $this->limit);

            if ($filters = TSession::getValue(__CLASS__ . '_filters'))
            {
                foreach ($filters as $filter)
                {
                    $criteria->add($filter);
                }
            }

            $objects = $repository->load($criteria, false);

            $this->datagrid->clear();
            if ($objects)
            {
                foreach ($objects as $object)
                {
                    $row = $this->datagrid->addItem($object);
                    $row->id = "row_{$object->id}";
                }
            }

            $criteria->resetProperties();
            $count = $repository->count($criteria);

            $this->pageNavigation->setCount($count);
            $this->pageNavigation->setProperties($param);
            $this->pageNavigation->setLimit($this->limit);

            TTransaction::close();
            $this->loaded = true;

            return $objects;
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }

    public function onShow($param = null)
    {
    }

    public function show()
    {
        if (!$this->loaded && (!isset($_GET['method']) || !(in_array($_GET['method'], $this->showMethods))))
        {
            if (func_num_args() > 0)
            {
                $this->onReload(func_get_arg(0));
            }
            else
            {
                $this->onReload();
            }
        }
        parent::show();
    }

    public static function formatCnpj($value)
    {
        $digits = preg_replace('/\D/', '', (string) $value);

        if (strlen($digits) !== 14)
        {
            return $value;
        }

        return preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $digits);
    }

    public static function formatCep($value)
    {
        $digits = preg_replace('/\D/', '', (string) $value);

        if (strlen($digits) !== 8)
        {
            return $value;
        }

        return preg_replace('/(\d{5})(\d{3})/', '$1-$2', $digits);
    }

    public static function formatPhone($value)
    {
        $digits = preg_replace('/\D/', '', (string) $value);

        if (strlen($digits) === 11)
        {
            return preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $digits);
        }

        if (strlen($digits) === 10)
        {
            return preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1) $2-$3', $digits);
        }

        return $value;
    }
}
