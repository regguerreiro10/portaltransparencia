<?php

class TipoSystemDocumentos extends TRecord
{
    const TABLENAME  = 'tipo_system_documentos';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const DELETEDAT  = 'deleted_at';
    const CREATEDAT  = 'created_at';
    const UPDATEDAT  = 'updated_at';

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('created_at');
        parent::addAttribute('updated_at');
        parent::addAttribute('deleted_at');
        parent::addAttribute('descricao');
            
    }

    /**
     * Method getSystemDocumentoss
     */
    public function getSystemDocumentoss()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('tipo_system_documentos_id', '=', $this->id));
        return SystemDocumentos::getObjects( $criteria );
    }

    public function set_system_documentos_system_unit_to_string($system_documentos_system_unit_to_string)
    {
        if(is_array($system_documentos_system_unit_to_string))
        {
            $values = SystemUnit::where('id', 'in', $system_documentos_system_unit_to_string)->getIndexedArray('name', 'name');
            $this->system_documentos_system_unit_to_string = implode(', ', $values);
        }
        else
        {
            $this->system_documentos_system_unit_to_string = $system_documentos_system_unit_to_string;
        }

        $this->vdata['system_documentos_system_unit_to_string'] = $this->system_documentos_system_unit_to_string;
    }

    public function get_system_documentos_system_unit_to_string()
    {
        if(!empty($this->system_documentos_system_unit_to_string))
        {
            return $this->system_documentos_system_unit_to_string;
        }
    
        $values = SystemDocumentos::where('tipo_system_documentos_id', '=', $this->id)->getIndexedArray('system_unit_id','{system_unit->name}');
        return implode(', ', $values);
    }

    public function set_system_documentos_tipo_system_documentos_to_string($system_documentos_tipo_system_documentos_to_string)
    {
        if(is_array($system_documentos_tipo_system_documentos_to_string))
        {
            $values = TipoSystemDocumentos::where('id', 'in', $system_documentos_tipo_system_documentos_to_string)->getIndexedArray('id', 'id');
            $this->system_documentos_tipo_system_documentos_to_string = implode(', ', $values);
        }
        else
        {
            $this->system_documentos_tipo_system_documentos_to_string = $system_documentos_tipo_system_documentos_to_string;
        }

        $this->vdata['system_documentos_tipo_system_documentos_to_string'] = $this->system_documentos_tipo_system_documentos_to_string;
    }

    public function get_system_documentos_tipo_system_documentos_to_string()
    {
        if(!empty($this->system_documentos_tipo_system_documentos_to_string))
        {
            return $this->system_documentos_tipo_system_documentos_to_string;
        }
    
        $values = SystemDocumentos::where('tipo_system_documentos_id', '=', $this->id)->getIndexedArray('tipo_system_documentos_id','{tipo_system_documentos->id}');
        return implode(', ', $values);
    }

    /**
     * Method onBeforeDelete
     */
    public function onBeforeDelete()
    {
            

        if(SystemDocumentos::where('tipo_system_documentos_id', '=', $this->id)->first())
        {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    
    }

    
}

