<?php
/**
 * EmendaForm Registration
 * @author  Sávio Batista <saviobatista@email.com>
 */
class EmendaForm extends TPage
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
        $this->setActiveRecord('Emenda');     // defines the active record
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_Emenda');
        $this->form->setFormTitle('Emenda');
        

        // create the form fields
        $id = new THidden('id');
        $dia = new TDate('dia');
        $referencia = new TEntry('referencia');
        $publicacao_id = new TDBCombo('publicacao_id', 'db', 'Publicacao', 'id', 'nome');
        $texto = new TText('texto');


        // add the fields
        $this->form->addFields( [ new TLabel('') ], [ $id ] );
        $this->form->addFields( [ new TLabel('Data') ], [ $dia ] );
        $this->form->addFields( [ new TLabel('Referencia') ], [ $referencia ] );
        $this->form->addFields( [ new TLabel('Publicacao Id') ], [ $publicacao_id ] );
        $this->form->addFields( [ new TLabel('Texto') ], [ $texto ] );

        $publicacao_id->addValidation('Publicacao Id', new TRequiredValidator);


        // set sizes
        $id->setSize('100%');
        $dia->setSize('100%');
        $referencia->setSize('100%');
        $publicacao_id->setSize('100%');
        $texto->setSize('100%');


        
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
