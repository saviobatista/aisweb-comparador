<?php
/**
 * Emenda Active Record
 * @author  Sávio Batista <saviobatista@email.com>
 */
class Emenda extends TRecord
{
    const TABLENAME = 'emenda';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}
    
    
    private $publicacao;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('dia');
        parent::addAttribute('referencia');
        parent::addAttribute('publicacao_id');
        parent::addAttribute('texto');
    }

    
    /**
     * Method set_publicacao
     * Sample of usage: $emenda->publicacao = $object;
     * @param $object Instance of Publicacao
     */
    public function set_publicacao(Publicacao $object)
    {
        $this->publicacao = $object;
        $this->publicacao_id = $object->id;
    }
    
    /**
     * Method get_publicacao
     * Sample of usage: $emenda->publicacao->attribute;
     * @returns Publicacao instance
     */
    public function get_publicacao()
    {
        // loads the associated object
        if (empty($this->publicacao))
            $this->publicacao = new Publicacao($this->publicacao_id);
    
        // returns the associated object
        return $this->publicacao;
    }
    


}
