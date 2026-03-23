<?php

class ContaCaixaReport extends TPage
{
    private $form; // form
    private $loaded;
    private static $database = 'minierp';
    private static $activeRecord = 'Conta';
    private static $primaryKey = 'id';
    private static $formName = 'form_ContaCaixaReport';

    /**
     * Class constructor
     * Creates the page, the form and the listing
     */
    public function __construct()
    {
        parent::__construct();

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);

        // define the form title
        $this->form->setFormTitle("Relatório caixa");

        $dt_pagamento = new TDate('dt_pagamento');
        $dt_pagamento_final = new TDate('dt_pagamento_final');

        $dt_pagamento->setSize(110);
        $dt_pagamento_final->setSize(110);

        $dt_pagamento->setMask('dd/mm/yyyy');
        $dt_pagamento_final->setMask('dd/mm/yyyy');

        $dt_pagamento->setDatabaseMask('yyyy-mm-dd');
        $dt_pagamento_final->setDatabaseMask('yyyy-mm-dd');

        $row1 = $this->form->addFields([new TLabel("Data de pagamento:", null, '14px', null, '100%'),$dt_pagamento,new TLabel("até", null, '14px', null),$dt_pagamento_final]);
        $row1->layout = ['col-sm-6'];

        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );

        $btn_ongeneratehtml = $this->form->addAction("Gerar HTML", new TAction([$this, 'onGenerateHtml']), 'far:file-code #ffffff');
        $this->btn_ongeneratehtml = $btn_ongeneratehtml;
        $btn_ongeneratehtml->addStyleClass('btn-primary'); 

        $btn_ongeneratepdf = $this->form->addAction("Gerar PDF", new TAction([$this, 'onGeneratePdf']), 'far:file-pdf #d44734');
        $this->btn_ongeneratepdf = $btn_ongeneratepdf;

        $btn_ongeneratexls = $this->form->addAction("Gerar XLS", new TAction([$this, 'onGenerateXls']), 'far:file-excel #00a65a');
        $this->btn_ongeneratexls = $btn_ongeneratexls;

        $btn_ongeneratertf = $this->form->addAction("Gerar RTF", new TAction([$this, 'onGenerateRtf']), 'far:file-alt #324bcc');
        $this->btn_ongeneratertf = $btn_ongeneratertf;

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        $container->add(TBreadCrumb::create(["Financeiro","Relatório caixa"]));
        $container->add($this->form);

        parent::add($container);

    }

    public function onGenerateHtml($param = null) 
    {
        $this->onGenerate('html');
    }
    public function onGeneratePdf($param = null) 
    {
        $this->onGenerate('pdf');
    }
    public function onGenerateXls($param = null) 
    {
        $this->onGenerate('xls');
    }
    public function onGenerateRtf($param = null) 
    {
        $this->onGenerate('rtf');
    }

    /**
     * Register the filter in the session
     */
    public function getFilters()
    {
        // get the search form data
        $data = $this->form->getData();

        $filters = [];

        TSession::setValue(__CLASS__.'_filter_data', NULL);
        TSession::setValue(__CLASS__.'_filters', NULL);

        if (isset($data->dt_pagamento) AND ( (is_scalar($data->dt_pagamento) AND $data->dt_pagamento !== '') OR (is_array($data->dt_pagamento) AND (!empty($data->dt_pagamento)) )) )
        {

            $filters[] = new TFilter('dt_pagamento', '>', $data->dt_pagamento);// create the filter 
        }
        if (isset($data->dt_pagamento_final) AND ( (is_scalar($data->dt_pagamento_final) AND $data->dt_pagamento_final !== '') OR (is_array($data->dt_pagamento_final) AND (!empty($data->dt_pagamento_final)) )) )
        {

            $filters[] = new TFilter('dt_pagamento', '<=', $data->dt_pagamento_final);// create the filter 
        }

        // fill the form with data again
        $this->form->setData($data);

        // keep the search data in the session
        TSession::setValue(__CLASS__.'_filter_data', $data);

        return $filters;
    }

    public function onGenerate($format)
    {
        try
        {
            $filters = $this->getFilters();
            // open a transaction with database 'minierp'
            TTransaction::open(self::$database);
            $param = [];
            // creates a repository for Conta
            $repository = new TRepository(self::$activeRecord);
            // creates a criteria
            $criteria = new TCriteria;

            $param['order'] = 'dt_pagamento';
            $param['direction'] = 'asc';

            $criteria->setProperties($param);

            if ($filters)
            {
                foreach ($filters as $filter) 
                {
                    $criteria->add($filter);       
                }
            }

            // load the objects according to criteria
            $objects = $repository->load($criteria, FALSE);

            if ($objects)
            {
                $widths = array(200,200,200,200,200);
                $reportExtension = 'pdf';
                switch ($format)
                {
                    case 'html':
                        $tr = new TTableWriterHTML($widths);
                        $reportExtension = 'html';
                        break;
                    case 'xls':
                        $tr = new TTableWriterXLS($widths);
                        $reportExtension = 'xls';
                        break;
                    case 'pdf':
                        $tr = new TTableWriterPDF($widths, 'L', 'A4');
                        $reportExtension = 'pdf';
                        break;
                    case 'htmlPdf':
                        $reportExtension = 'pdf';
                        $tr = new BTableWriterHtmlPDF($widths, 'L', 'A4');
                        break;
                    case 'rtf':
                        if (!class_exists('PHPRtfLite_Autoloader'))
                        {
                            PHPRtfLite::registerAutoloader();
                        }
                        $reportExtension = 'rtf';
                        $tr = new TTableWriterRTF($widths, 'L', 'A4');
                        break;
                }

                if (!empty($tr))
                {
                    // create the document styles
                    $tr->addStyle('title', 'Helvetica', '10', 'B',   '#000000', '#dbdbdb');
                    $tr->addStyle('datap', 'Arial', '10', '',    '#333333', '#f0f0f0');
                    $tr->addStyle('datai', 'Arial', '10', '',    '#333333', '#ffffff');
                    $tr->addStyle('header', 'Helvetica', '16', 'B',   '#5a5a5a', '#6B6B6B');
                    $tr->addStyle('footer', 'Helvetica', '10', 'B',  '#5a5a5a', '#A3A3A3');
                    $tr->addStyle('break', 'Helvetica', '10', 'B',  '#ffffff', '#9a9a9a');
                    $tr->addStyle('total', 'Helvetica', '10', 'I',  '#000000', '#c7c7c7');
                    $tr->addStyle('breakTotal', 'Helvetica', '10', 'I',  '#000000', '#c6c8d0');

                    $saldoAnterior = 0;
                    $data = $this->form->getData();

                    if(!$data->dt_pagamento || !$data->dt_pagamento_final)
                    {
                        throw new Exception('As datas são obrigatórias.');
                    }

                    $valoresReceber = Conta::where('dt_pagamento', '<', $data->dt_pagamento)->where('tipo_conta_id', '=', TipoConta::RECEBER)->sumBy('valor') ?? 0;
                    $valoresPagar = Conta::where('dt_pagamento', '<', $data->dt_pagamento)->where('tipo_conta_id', '=', TipoConta::PAGAR)->sumBy('valor') ?? 0;

                    $saldoAnterior = $valoresReceber - $valoresPagar;

                    // add titles row
                    $tr->addRow();
                    $tr->addCell("Tipo da conta", 'left', 'title');
                    $tr->addCell("Data de pagamento", 'left', 'title');
                    $tr->addCell("Saldo anterior", 'left', 'title');
                    $tr->addCell("Valor", 'left', 'title');
                    $tr->addCell("Saldo atual", 'left', 'title');

                    $grandTotal = [];
                    $breakTotal = [];
                    $breakValue = null;
                    $firstRow = true;

                    // controls the background filling
                    $colour = false;                
                    foreach ($objects as $object)
                    {
                        $style = $colour ? 'datap' : 'datai';

                        if($object->tipo_conta_id == TipoConta::RECEBER && $object->valor)
                        {
                            $object->saldo_atual = $saldoAnterior + $object->valor;    
                        }
                        elseif($object->tipo_conta_id == TipoConta::PAGAR && $object->valor)
                        {
                            $object->saldo_atual = $saldoAnterior - $object->valor;
                        }

                        $object->saldo_anterior = $saldoAnterior;

                        $column_mask_1 = $object->render('{saldo_anterior}');
                        $column_mask_2 = $object->render('{saldo_atual}');

                        $firstRow = false;

                        $object->dt_pagamento = call_user_func(function($value, $object, $row) 
                        {
                            if(!empty(trim((string) $value)))
                            {
                                try
                                {
                                    $date = new DateTime($value);
                                    return $date->format('d/m/Y');
                                }
                                catch (Exception $e)
                                {
                                    return $value;
                                }
                            }
                        }, $object->dt_pagamento, $object, null);

                        $column_mask_1 = call_user_func(function($value, $object, $row) 
                        {
                            if(!$value)
                            {
                                $value = 0;
                            }

                            if(is_numeric($value))
                            {
                                return "R$ " . number_format($value, 2, ",", ".");
                            }
                            else
                            {
                                return $value;
                            }
                        }, $column_mask_1, $object, null);

                        $column_mask_2 = call_user_func(function($value, $object, $row) 
                        {
                            if(!$value)
                            {
                                $value = 0;
                            }

                            if(is_numeric($value))
                            {
                                return "R$ " . number_format($value, 2, ",", ".");
                            }
                            else
                            {
                                return $value;
                            }
                        }, $column_mask_2, $object, null);

                        if($object->tipo_conta_id == TipoConta::RECEBER && $object->valor)
                        {
                            $saldoAnterior += $object->valor;    
                        }
                        elseif($object->tipo_conta_id == TipoConta::PAGAR && $object->valor)
                        {
                            $saldoAnterior -= $object->valor;
                        }

                        $tr->addRow();

                        $tr->addCell($object->tipo_conta->nome, 'left', $style);
                        $tr->addCell($object->dt_pagamento, 'left', $style);
                        $tr->addCell($column_mask_1, 'left', $style);
                        $tr->addCell($object->valor_real, 'left', $style);
                        $tr->addCell($column_mask_2, 'left', $style);

                        $colour = !$colour;

                    }

                    $file = 'report_'.uniqid().".{$reportExtension}";
                    // stores the file
                    if (!file_exists("app/output/{$file}") || is_writable("app/output/{$file}"))
                    {
                        $tr->save("app/output/{$file}");
                    }
                    else
                    {
                        throw new Exception(_t('Permission denied') . ': ' . "app/output/{$file}");
                    }

                    parent::openFile("app/output/{$file}");

                    // shows the success message
                    new TMessage('info', _t('Report generated. Please, enable popups'));
                }
            }
            else
            {
                new TMessage('error', _t('No records found'));
            }

            // close the transaction
            TTransaction::close();
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


}

