<?php
/**
 * Categoria Active Record
 * @author  Sávio Batista <saviobatista@email.com>
 */
class Categoria extends TRecord
{
    const TABLENAME = 'categoria';
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
        $criteria->add(new TFilter('categoria_id', '=', $this->id));
        return Publicacao::getObjects( $criteria );
    }
    


}
