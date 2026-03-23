<?php

use Adianti\Base\AdiantiStandardListExportTrait;

class SystemUnitList extends TPage
{
    private $form; // form
    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    private $filter_criteria;
    private $database = 'permission';
    private static $activeRecord = 'SystemUnit';
    private static $primaryKey = 'id';
    private static $formName = 'formList_SystemUnit';
    private $showMethods = ['onReload', 'onSearch'];
    private $limit = 20;

    use AdiantiStandardListExportTrait;

    /**
     * Class constructor
     * Creates the page, the form and the listing
     */
    public function __construct($param = null)
    {
        parent::__construct();
        // creates the form
        
        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        $this->limit = 20;

        $criteria_system_entidade_id = new TCriteria();
        $criteria_cidade_id = new TCriteria();

        $id = new TEntry('id');
        $name = new TEntry('name');
        $connection_name = new TEntry('connection_name');
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
        $system_entidade_id = new TDBCombo('system_entidade_id', 'minierp', 'SystemEntidade', 'id', '{nome}', 'nome asc', $criteria_system_entidade_id);
        $cidade_id = new TDBCombo('cidade_id', 'minierp', 'Cidade', 'id', '{nome} ({estado->sigla})', 'nome asc', $criteria_cidade_id);

        $id->exitOnEnter();
        $name->exitOnEnter();
        $connection_name->exitOnEnter();
        $email->exitOnEnter();
        $cep->exitOnEnter();
        $rua->exitOnEnter();
        $numero->exitOnEnter();
        $bairro->exitOnEnter();
        $complemento->exitOnEnter();
        $cnpj->exitOnEnter();
        $telefone01->exitOnEnter();
        $telefone02->exitOnEnter();
        $logo->exitOnEnter();
        $longitude->exitOnEnter();
        $latitude->exitOnEnter();

        $id->setExitAction(new TAction([$this, 'onSearch'], ['static'=>'1', 'target_container' => $param['target_container'] ?? null]));
        $name->setExitAction(new TAction([$this, 'onSearch'], ['static'=>'1', 'target_container' => $param['target_container'] ?? null]));
        $connection_name->setExitAction(new TAction([$this, 'onSearch'], ['static'=>'1', 'target_container' => $param['target_container'] ?? null]));
        $email->setExitAction(new TAction([$this, 'onSearch'], ['static'=>'1', 'target_container' => $param['target_container'] ?? null]));
        $cep->setExitAction(new TAction([$this, 'onSearch'], ['static'=>'1', 'target_container' => $param['target_container'] ?? null]));
        $rua->setExitAction(new TAction([$this, 'onSearch'], ['static'=>'1', 'target_container' => $param['target_container'] ?? null]));
        $numero->setExitAction(new TAction([$this, 'onSearch'], ['static'=>'1', 'target_container' => $param['target_container'] ?? null]));
        $bairro->setExitAction(new TAction([$this, 'onSearch'], ['static'=>'1', 'target_container' => $param['target_container'] ?? null]));
        $complemento->setExitAction(new TAction([$this, 'onSearch'], ['static'=>'1', 'target_container' => $param['target_container'] ?? null]));
        $cnpj->setExitAction(new TAction([$this, 'onSearch'], ['static'=>'1', 'target_container' => $param['target_container'] ?? null]));
        $telefone01->setExitAction(new TAction([$this, 'onSearch'], ['static'=>'1', 'target_container' => $param['target_container'] ?? null]));
        $telefone02->setExitAction(new TAction([$this, 'onSearch'], ['static'=>'1', 'target_container' => $param['target_container'] ?? null]));
        $logo->setExitAction(new TAction([$this, 'onSearch'], ['static'=>'1', 'target_container' => $param['target_container'] ?? null]));
        $longitude->setExitAction(new TAction([$this, 'onSearch'], ['static'=>'1', 'target_container' => $param['target_container'] ?? null]));
        $latitude->setExitAction(new TAction([$this, 'onSearch'], ['static'=>'1', 'target_container' => $param['target_container'] ?? null]));
        $system_entidade_id->setChangeAction(new TAction([$this, 'onSearch'], ['static'=>'1', 'target_container' => $param['target_container'] ?? null]));
        $cidade_id->setChangeAction(new TAction([$this, 'onSearch'], ['static'=>'1', 'target_container' => $param['target_container'] ?? null]));

        $id->setSize('100%');
        $name->setSize('100%');
        $connection_name->setSize('100%');
        $email->setSize('100%');
        $cep->setSize('100%');
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

        // creates a Datagrid
        $this->datagrid = new TDataGrid;

        $this->datagrid_form = new TForm(self::$formName);
        $this->datagrid_form->onsubmit = 'return false';

        $this->datagrid = new BootstrapDatagridWrapper($this->datagrid);
        $this->filter_criteria = new TCriteria;

        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);

        $column_id = new TDataGridColumn('id', "ID", 'center' , '70px');
        $column_name = new TDataGridColumn('name', _t("Name"), 'left');
        $column_connection_name = new TDataGridColumn('connection_name', _t('Database'), 'left');
        $column_email = new TDataGridColumn('email', _t('Email'), 'left');
        $column_cep = new TDataGridColumn('cep', 'CEP', 'left');
        $column_rua = new TDataGridColumn('rua', 'Rua', 'left');
        $column_numero = new TDataGridColumn('numero', 'Numero', 'left');
        $column_bairro = new TDataGridColumn('bairro', 'Bairro', 'left');
        $column_complemento = new TDataGridColumn('complemento', 'Complemento', 'left');
        $column_cnpj = new TDataGridColumn('cnpj', 'CNPJ', 'left');
        $column_telefone01 = new TDataGridColumn('telefone01', 'Telefone 01', 'left');
        $column_telefone02 = new TDataGridColumn('telefone02', 'Telefone 02', 'left');
        $column_logo = new TDataGridColumn('logo', 'Logo', 'left');
        $column_longitude = new TDataGridColumn('longitude', 'Longitude', 'left');
        $column_latitude = new TDataGridColumn('latitude', 'Latitude', 'left');
        $column_system_entidade = new TDataGridColumn('system_entidade->nome', 'Entidade', 'left');
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

        $order_id = new TAction(array($this, 'onReload'));
        $order_id->setParameter('order', 'id');
        $column_id->setAction($order_id);

        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_name);
        $this->datagrid->addColumn($column_connection_name);
        $this->datagrid->addColumn($column_email);
        $this->datagrid->addColumn($column_cep);
        $this->datagrid->addColumn($column_rua);
        $this->datagrid->addColumn($column_numero);
        $this->datagrid->addColumn($column_bairro);
        $this->datagrid->addColumn($column_complemento);
        $this->datagrid->addColumn($column_cnpj);
        $this->datagrid->addColumn($column_telefone01);
        $this->datagrid->addColumn($column_telefone02);
        $this->datagrid->addColumn($column_logo);
        $this->datagrid->addColumn($column_longitude);
        $this->datagrid->addColumn($column_latitude);
        $this->datagrid->addColumn($column_system_entidade);
        $this->datagrid->addColumn($column_cidade);

        $action_onEdit = new TDataGridAction(array('SystemUnitForm', 'onEdit'));
        $action_onEdit->setUseButton(false);
        $action_onEdit->setButtonClass('btn btn-default btn-sm');
        $action_onEdit->setLabel(_t('Edit'));
        $action_onEdit->setImage('far:edit #478fca');
        $action_onEdit->setField(self::$primaryKey);

        $this->datagrid->addAction($action_onEdit);

        $action_onDelete = new TDataGridAction(array('SystemUnitList', 'onDelete'));
        $action_onDelete->setUseButton(false);
        $action_onDelete->setButtonClass('btn btn-default btn-sm');
        $action_onDelete->setLabel(_t('Delete'));
        $action_onDelete->setImage('fas:trash-alt #dd5a43');
        $action_onDelete->setField(self::$primaryKey);

        $this->datagrid->addAction($action_onDelete);

        // create the datagrid model
        $this->datagrid->createModel();

        $tr = new TElement('tr');
        $this->datagrid->prependRow($tr);

        $tr->add(TElement::tag('td', ''));
        $tr->add(TElement::tag('td', ''));
        $td_id = TElement::tag('td', $id);
        $tr->add($td_id);
        $td_name = TElement::tag('td', $name);
        $tr->add($td_name);
        $tr->add(TElement::tag('td', $connection_name));
        $tr->add(TElement::tag('td', $email));
        $tr->add(TElement::tag('td', $cep));
        $tr->add(TElement::tag('td', $rua));
        $tr->add(TElement::tag('td', $numero));
        $tr->add(TElement::tag('td', $bairro));
        $tr->add(TElement::tag('td', $complemento));
        $tr->add(TElement::tag('td', $cnpj));
        $tr->add(TElement::tag('td', $telefone01));
        $tr->add(TElement::tag('td', $telefone02));
        $tr->add(TElement::tag('td', $logo));
        $tr->add(TElement::tag('td', $longitude));
        $tr->add(TElement::tag('td', $latitude));
        $tr->add(TElement::tag('td', $system_entidade_id));
        $tr->add(TElement::tag('td', $cidade_id));

        $this->datagrid_form->addField($id);
        $this->datagrid_form->addField($name);
        $this->datagrid_form->addField($connection_name);
        $this->datagrid_form->addField($email);
        $this->datagrid_form->addField($cep);
        $this->datagrid_form->addField($rua);
        $this->datagrid_form->addField($numero);
        $this->datagrid_form->addField($bairro);
        $this->datagrid_form->addField($complemento);
        $this->datagrid_form->addField($cnpj);
        $this->datagrid_form->addField($telefone01);
        $this->datagrid_form->addField($telefone02);
        $this->datagrid_form->addField($logo);
        $this->datagrid_form->addField($longitude);
        $this->datagrid_form->addField($latitude);
        $this->datagrid_form->addField($system_entidade_id);
        $this->datagrid_form->addField($cidade_id);

        $this->datagrid_form->setData( TSession::getValue(__CLASS__.'_filter_data') );

        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->enableCounters();
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());

        $panel = new TPanelGroup(_t('Units'));
        $panel->datagrid = 'datagrid-container';
        $this->datagridPanel = $panel;
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

        $this->datagrid_form->add($this->datagrid);
        $panel->add($headerActions);
        $panel->add($this->datagrid_form);

        $button_new = new TButton('button_new');
        $button_new->setAction(new TAction(['SystemUnitForm', 'onEdit']), _t('New'));
        $button_new->addStyleClass('btn-default');
        $button_new->setImage('fas:plus #69aa46');

        $this->datagrid_form->addField($button_new);

        $button_refresh = new TButton('button_refresh');
        $button_refresh->setAction(new TAction(['SystemUnitList', 'onRefresh']), _t("Refresh"));
        $button_refresh->addStyleClass('btn-default');
        $button_refresh->setImage('fas:sync-alt #03a9f4');

        $this->datagrid_form->addField($button_refresh);

        $button_clear_filters = new TButton('button_clear_filters');
        $button_clear_filters->setAction(new TAction(['SystemUnitList', 'onClearFilters']), _t("Clear filters"));
        $button_clear_filters->addStyleClass('btn-default');
        $button_clear_filters->setImage('fas:eraser #f44336');

        $this->datagrid_form->addField($button_clear_filters);

        $dropdown_button_export = new TDropDown("Exportar", 'fas:file-export #2d3436');
        $dropdown_button_export->setPullSide('right');
        $dropdown_button_export->setButtonClass('btn btn-default waves-effect dropdown-toggle');
        $dropdown_button_export->addPostAction( "CSV", new TAction(['SystemUnitList', 'onExportCsv'],['static' => 1]), self::$formName, 'fas:file-csv #00b894' );
        $dropdown_button_export->addPostAction( "XLS", new TAction(['SystemUnitList', 'onExportXls'],['static' => 1]), self::$formName, 'fas:file-excel #4CAF50' );
        $dropdown_button_export->addPostAction( "PDF", new TAction(['SystemUnitList', 'onExportPdf'],['static' => 1]), self::$formName, 'far:file-pdf #e74c3c' );

        $head_left_actions->add($button_new);
        $head_left_actions->add($button_refresh);
        $head_left_actions->add($button_clear_filters);

        $head_right_actions->add($dropdown_button_export);

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        if(empty($param['target_container']))
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
        TSession::setValue(__CLASS__.'_filter_data', NULL);
        TSession::setValue(__CLASS__.'_filters', NULL);

        $this->onReload(['offset' => 0, 'first_page' => 1]);

        $this->datagrid_form->clear();
    }

    /**
     * Register the filter in the session
     */
    public function onSearch($param = null)
    {
        // get the search form data
        $data = $this->datagrid_form->getData();
        $filters = [];

        TSession::setValue(__CLASS__.'_filter_data', NULL);
        TSession::setValue(__CLASS__.'_filters', NULL);

        if (isset($data->id) AND ( (is_scalar($data->id) AND $data->id !== '') OR (is_array($data->id) AND (!empty($data->id)) )) )
        {

            $filters[] = new TFilter('id', '=', $data->id);// create the filter 
        }

        if (isset($data->name) AND ( (is_scalar($data->name) AND $data->name !== '') OR (is_array($data->name) AND (!empty($data->name)) )) )
        {

            $filters[] = new TFilter('name', 'like', "%{$data->name}%");// create the filter 
        }
        if (isset($data->connection_name) AND ( (is_scalar($data->connection_name) AND $data->connection_name !== '') OR (is_array($data->connection_name) AND (!empty($data->connection_name)) )) )
        {

            $filters[] = new TFilter('connection_name', 'like', "%{$data->connection_name}%");// create the filter 
        }
        if (isset($data->email) AND ( (is_scalar($data->email) AND $data->email !== '') OR (is_array($data->email) AND (!empty($data->email)) )) )
        {

            $filters[] = new TFilter('email', 'like', "%{$data->email}%");// create the filter 
        }
        if (isset($data->cep) AND ( (is_scalar($data->cep) AND $data->cep !== '') OR (is_array($data->cep) AND (!empty($data->cep)) )) )
        {

            $filters[] = new TFilter('cep', 'like', "%{$data->cep}%");// create the filter 
        }
        if (isset($data->rua) AND ( (is_scalar($data->rua) AND $data->rua !== '') OR (is_array($data->rua) AND (!empty($data->rua)) )) )
        {

            $filters[] = new TFilter('rua', 'like', "%{$data->rua}%");// create the filter 
        }
        if (isset($data->numero) AND ( (is_scalar($data->numero) AND $data->numero !== '') OR (is_array($data->numero) AND (!empty($data->numero)) )) )
        {

            $filters[] = new TFilter('numero', 'like', "%{$data->numero}%");// create the filter 
        }
        if (isset($data->bairro) AND ( (is_scalar($data->bairro) AND $data->bairro !== '') OR (is_array($data->bairro) AND (!empty($data->bairro)) )) )
        {

            $filters[] = new TFilter('bairro', 'like', "%{$data->bairro}%");// create the filter 
        }
        if (isset($data->complemento) AND ( (is_scalar($data->complemento) AND $data->complemento !== '') OR (is_array($data->complemento) AND (!empty($data->complemento)) )) )
        {

            $filters[] = new TFilter('complemento', 'like', "%{$data->complemento}%");// create the filter 
        }
        if (isset($data->cnpj) AND ( (is_scalar($data->cnpj) AND $data->cnpj !== '') OR (is_array($data->cnpj) AND (!empty($data->cnpj)) )) )
        {

            $filters[] = new TFilter('cnpj', 'like', "%{$data->cnpj}%");// create the filter 
        }
        if (isset($data->telefone01) AND ( (is_scalar($data->telefone01) AND $data->telefone01 !== '') OR (is_array($data->telefone01) AND (!empty($data->telefone01)) )) )
        {

            $filters[] = new TFilter('telefone01', 'like', "%{$data->telefone01}%");// create the filter 
        }
        if (isset($data->telefone02) AND ( (is_scalar($data->telefone02) AND $data->telefone02 !== '') OR (is_array($data->telefone02) AND (!empty($data->telefone02)) )) )
        {

            $filters[] = new TFilter('telefone02', 'like', "%{$data->telefone02}%");// create the filter 
        }
        if (isset($data->logo) AND ( (is_scalar($data->logo) AND $data->logo !== '') OR (is_array($data->logo) AND (!empty($data->logo)) )) )
        {

            $filters[] = new TFilter('logo', 'like', "%{$data->logo}%");// create the filter 
        }
        if (isset($data->longitude) AND ( (is_scalar($data->longitude) AND $data->longitude !== '') OR (is_array($data->longitude) AND (!empty($data->longitude)) )) )
        {

            $filters[] = new TFilter('longitude', 'like', "%{$data->longitude}%");// create the filter 
        }
        if (isset($data->latitude) AND ( (is_scalar($data->latitude) AND $data->latitude !== '') OR (is_array($data->latitude) AND (!empty($data->latitude)) )) )
        {

            $filters[] = new TFilter('latitude', 'like', "%{$data->latitude}%");// create the filter 
        }
        if (isset($data->system_entidade_id) AND ( (is_scalar($data->system_entidade_id) AND $data->system_entidade_id !== '') OR (is_array($data->system_entidade_id) AND (!empty($data->system_entidade_id)) )) )
        {

            $filters[] = new TFilter('system_entidade_id', '=', $data->system_entidade_id);// create the filter 
        }
        if (isset($data->cidade_id) AND ( (is_scalar($data->cidade_id) AND $data->cidade_id !== '') OR (is_array($data->cidade_id) AND (!empty($data->cidade_id)) )) )
        {

            $filters[] = new TFilter('cidade_id', '=', $data->cidade_id);// create the filter 
        }

        // fill the form with data again
        $this->datagrid_form->setData($data);

        // keep the search data in the session
        TSession::setValue(__CLASS__.'_filter_data', $data);
        TSession::setValue(__CLASS__.'_filters', $filters);

        if (isset($param['static']) && ($param['static'] == '1') )
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
        if(isset($param['delete']) && $param['delete'] == 1)
        {
            try
            {
                // get the paramseter $key
                $key = $param['key'];
                // open a transaction with database
                TTransaction::open($this->database);

                // instantiates object
                $object = new SystemUnit($key, FALSE); 

                // deletes the object from the database
                $object->delete();

                // close the transaction
                TTransaction::close();

                // reload the listing
                $this->onReload( $param );
                // shows the success message
                new TMessage('info', AdiantiCoreTranslator::translate('Record deleted'));
            }
            catch (Exception $e) // in case of exception
            {
                // shows the exception error message
                new TMessage('error', $e->getMessage());
                // undo all pending operations
                TTransaction::rollback();
            }
        }
        else
        {
            // define the delete action
            $action = new TAction(array($this, 'onDelete'));
            $action->setParameters($param); // pass the key paramseter ahead
            $action->setParameter('delete', 1);
            // shows a dialog to the user
            new TQuestion(AdiantiCoreTranslator::translate('Do you really want to delete ?'), $action);   
        }
    }

    /**
     * Load the datagrid with data
     */
    public function onReload($param = NULL)
    {
        try
        {
            // open a transaction with database 'permission'
            TTransaction::open($this->database);

            // creates a repository for SystemUnit
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

            $criteria->setProperties($param); // order, offset
            $criteria->setProperty('limit', $this->limit);

            if($filters = TSession::getValue(__CLASS__.'_filters'))
            {
                foreach ($filters as $filter) 
                {
                    $criteria->add($filter);       
                }
            }

            // load the objects according to criteria
            $objects = $repository->load($criteria, FALSE);

            $this->datagrid->clear();
            if ($objects)
            {
                // iterate the collection of active records
                foreach ($objects as $object)
                {

                    $row = $this->datagrid->addItem($object);
                    $row->id = "row_{$object->id}";

                }
            }

            // reset the criteria for record count
            $criteria->resetProperties();
            $count= $repository->count($criteria);

            $this->pageNavigation->setCount($count); // count of records
            $this->pageNavigation->setProperties($param); // order, page
            $this->pageNavigation->setLimit($this->limit); // limit

            // close the transaction
            TTransaction::close();
            $this->loaded = true;

            return $objects;
        }
        catch (Exception $e) // in case of exception
        {
            // shows the exception error message
            new TMessage('error', $e->getMessage());
            // undo all pending operations
            TTransaction::rollback();
        }
    }

    public function onShow($param = null)
    {

    }

    /**
     * method show()
     * Shows the page
     */
    public function show()
    {
        // check if the datagrid is already loaded
        if (!$this->loaded AND (!isset($_GET['method']) OR !(in_array($_GET['method'],  $this->showMethods))) )
        {
            if (func_num_args() > 0)
            {
                $this->onReload( func_get_arg(0) );
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
