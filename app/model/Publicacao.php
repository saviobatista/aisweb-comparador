<?php
/**
 * Publicacao Active Record
 * @author  Sávio Batista <saviobatista@email.com>
 */
class Publicacao extends TRecord
{
    const TABLENAME = 'publicacao';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}
    
    
    private $categoria;
    private $local;
    private $origem;
    private $emendas;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
        parent::addAttribute('oficial');
        parent::addAttribute('categoria_id');
        parent::addAttribute('local_id');
        parent::addAttribute('origem_id');
    }

    
    /**
     * Method set_categoria
     * Sample of usage: $publicacao->categoria = $object;
     * @param $object Instance of Categoria
     */
    public function set_categoria(Categoria $object)
    {
        $this->categoria = $object;
        $this->categoria_id = $object->id;
    }
    
    /**
     * Method get_categoria
     * Sample of usage: $publicacao->categoria->attribute;
     * @returns Categoria instance
     */
    public function get_categoria()
    {
        // loads the associated object
        if (empty($this->categoria))
            $this->categoria = new Categoria($this->categoria_id);
    
        // returns the associated object
        return $this->categoria;
    }
    
    
    /**
     * Method set_local
     * Sample of usage: $publicacao->local = $object;
     * @param $object Instance of Local
     */
    public function set_local(Local $object)
    {
        $this->local = $object;
        $this->local_id = $object->id;
    }
    
    /**
     * Method get_local
     * Sample of usage: $publicacao->local->attribute;
     * @returns Local instance
     */
    public function get_local()
    {
        // loads the associated object
        if (empty($this->local))
            $this->local = new Local($this->local_id);
    
        // returns the associated object
        return $this->local;
    }
    
    
    /**
     * Method set_origem
     * Sample of usage: $publicacao->origem = $object;
     * @param $object Instance of Origem
     */
    public function set_origem(Origem $object)
    {
        $this->origem = $object;
        $this->origem_id = $object->id;
    }
    
    /**
     * Method get_origem
     * Sample of usage: $publicacao->origem->attribute;
     * @returns Origem instance
     */
    public function get_origem()
    {
        // loads the associated object
        if (empty($this->origem))
            $this->origem = new Origem($this->origem_id);
    
        // returns the associated object
        return $this->origem;
    }
    
    
    /**
     * Method addEmenda
     * Add a Emenda to the Publicacao
     * @param $object Instance of Emenda
     */
    public function addEmenda(Emenda $object)
    {
        $this->emendas[] = $object;
    }
    
    /**
     * Method getEmendas
     * Return the Publicacao' Emenda's
     * @return Collection of Emenda
     */
    public function getEmendas()
    {
        return $this->emendas;
    }

    /**
     * Reset aggregates
     */
    public function clearParts()
    {
        $this->emendas = array();
    }

    /**
     * Load the object and its aggregates
     * @param $id object ID
     */
    public function load($id)
    {
        $this->emendas = parent::loadComposite('Emenda', 'publicacao_id', $id);
    
        // load the object itself
        return parent::load($id);
    }

    /**
     * Store the object and its aggregates
     */
    public function store()
    {
        // store the object itself
        parent::store();
    
        parent::saveComposite('Emenda', 'publicacao_id', $this->id, $this->emendas);
    }

    /**
     * Delete the object and its aggregates
     * @param $id object ID
     */
    public function delete($id = NULL)
    {
        $id = isset($id) ? $id : $this->id;
        parent::deleteComposite('Emenda', 'publicacao_id', $id);
    
        // delete the object itself
        parent::delete($id);
    }
    
    public function get_atual()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('publicacao_id', '=', $this->id));
        $criteria->setProperty('limit',1);
        $criteria->setProperties(['offset'=>1,'order'=>'dia','direction'=>'desc']);
        $repo = Emenda::getObjects( $criteria );
        if($repo)
            return $repo[0]->texto;
        else
            return '-';
    }
    
    public function get_proximo()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('publicacao_id', '=', $this->id));
        $criteria->setProperties(['offset'=>0,'order'=>'dia','direction'=>'desc']);
        $criteria->setProperty('limit',1);
        $repo = Emenda::getObjects( $criteria );
        if($repo)
            return $repo[0]->texto;
        else
            return '-';
    }
    
    public function get_anterior()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('publicacao_id', '=', $this->id));
        $criteria->setProperties(['offset'=>2,'order'=>'dia','direction'=>'desc']);
        $criteria->setProperty('limit',1);
        $repo = Emenda::getObjects( $criteria );
        if($repo)
            return $repo[0]->texto;
        else
            return '-';
    }



}