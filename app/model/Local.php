<?php
/**
 * Local Active Record
 * @author  Sávio Batista <saviobatista@email.com>
 */
class Local extends TRecord
{
    const TABLENAME = 'local';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('id');
        parent::addAttribute('nome');
    }

    
    /**
     * Method getPublicacaos
     */
    public function getPublicacaos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('local_id', '=', $this->id));
        return Publicacao::getObjects( $criteria );
    }
    


}
