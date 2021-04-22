<?php
/**
 * PublicacaoForm Registration
 * @author  Sávio Batista <saviobatista@email.com>
 */
class PublicacaoForm extends TPage
{
    protected $form; // form
    
    use Adianti\Base\AdiantiStandardFormTrait; // Standard form methods
    
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    function __construct()
    {
        parent::__construct();
        
        parent::setTargetContainer('adianti_right_panel');

        $this->setDatabase('db');              // defines the database
        $this->setActiveRecord('Publicacao');     // defines the active record
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_Publicacao');
        $this->form->setFormTitle('Publicacao');
        

        // create the form fields
        $id = new THidden('id');
        $nome = new TEntry('nome');
        $categoria_id = new TDBCombo('categoria_id', 'db', 'Categoria', 'id', 'nome');
        $local_id = new TDBCombo('local_id', 'db', 'Local', 'id', 'nome');
        $origem_id = new TDBCombo('origem_id', 'db', 'Origem', 'id', 'nome');
        $oficial = new TText('oficial');


        // add the fields
        $this->form->addFields( [ new TLabel('') ], [ $id ] );
        $this->form->addFields( [ new TLabel('Nome') ], [ $nome ] );
        $this->form->addFields( [ new TLabel('Categoria') ], [ $categoria_id ] );
        $this->form->addFields( [ new TLabel('Local') ], [ $local_id ] );
        $this->form->addFields( [ new TLabel('Origem') ], [ $origem_id ] );
        $this->form->addFields( [ new TLabel('Oficial') ], [ $oficial ] );

        $nome->addValidation('Nome', new TRequiredValidator);


        // set sizes
        $id->setSize('100%');
        $nome->setSize('100%');
        $categoria_id->setSize('100%');
        $local_id->setSize('100%');
        $origem_id->setSize('100%');
        $oficial->setSize('100%');


        
        if (!empty($id))
        {
            $id->setEditable(FALSE);
        }
        
        /** samples
         $fieldX->addValidation( 'Field X', new TRequiredValidator ); // add validation
         $fieldX->setSize( '100%' ); // set size
         **/
         
        // create the form actions
        $btn = $this->form->addAction(_t('Save'), new TAction([$this, 'onSave']), 'fa:save');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addActionLink(_t('New'),  new TAction([$this, 'onEdit']), 'fa:eraser red');
        
        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);
        
        parent::add($container);
    }
}
