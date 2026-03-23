<?php

class SystemDocumentos extends TRecord
{
    const TABLENAME  = 'system_documentos';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const DELETEDAT  = 'deleted_at';
    const CREATEDAT  = 'created_at';
    const UPDATEDAT  = 'updated_at';

    private SystemUnit $system_unit;
    private TipoSystemDocumentos $tipo_system_documentos;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('created_at');
        parent::addAttribute('updated_at');
        parent::addAttribute('deleted_at');
        parent::addAttribute('system_unit_id');
        parent::addAttribute('data');
        parent::addAttribute('mes');
        parent::addAttribute('ano');
        parent::addAttribute('arquivo');
        parent::addAttribute('tipo_system_documentos_id');
            
    }

    /**
     * Method set_system_unit
     * Sample of usage: $var->system_unit = $object;
     * @param $object Instance of SystemUnit
     */
    public function set_system_unit(SystemUnit $object)
    {
        $this->system_unit = $object;
        $this->system_unit_id = $object->id;
    }

    /**
     * Method get_system_unit
     * Sample of usage: $var->system_unit->attribute;
     * @returns SystemUnit instance
     */
    public function get_system_unit()
    {
    
        // loads the associated object
        if (empty($this->system_unit))
            $this->system_unit = new SystemUnit($this->system_unit_id);
    
        // returns the associated object
        return $this->system_unit;
    }
    /**
     * Method set_tipo_system_documentos
     * Sample of usage: $var->tipo_system_documentos = $object;
     * @param $object Instance of TipoSystemDocumentos
     */
    public function set_tipo_system_documentos(TipoSystemDocumentos $object)
    {
        $this->tipo_system_documentos = $object;
        $this->tipo_system_documentos_id = $object->id;
    }

    /**
     * Method get_tipo_system_documentos
     * Sample of usage: $var->tipo_system_documentos->attribute;
     * @returns TipoSystemDocumentos instance
     */
    public function get_tipo_system_documentos()
    {
    
        // loads the associated object
        if (empty($this->tipo_system_documentos))
            $this->tipo_system_documentos = new TipoSystemDocumentos($this->tipo_system_documentos_id);
    
        // returns the associated object
        return $this->tipo_system_documentos;
    }

    
}

