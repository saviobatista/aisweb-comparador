<?php
/**
 * EmendaList Listing
 * @author  Sávio Batista <saviobatista@email.com>
 */
class EmendaList extends TPage
{
    protected $form;     // registration form
    protected $datagrid; // listing
    protected $pageNavigation;
    
    use Adianti\base\AdiantiStandardListTrait;
    
    /**
     * Page constructor
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->setDatabase('db');            // defines the database
        $this->setActiveRecord('Emenda');   // defines the active record
        $this->setDefaultOrder('id', 'asc');         // defines the default order
        $this->setLimit(50);
        // $this->setCriteria($criteria) // define a standard filter

        $this->addFilterField('dia', 'like', 'data'); // filterField, operator, formField
        $this->addFilterField('referencia', 'like', 'referencia'); // filterField, operator, formField
        $this->addFilterField('publicacao_id', '=', 'publicacao_id'); // filterField, operator, formField

        $this->form = new TForm('form_search_Emenda');
        
        $dia = new TDate('dia');
        $referencia = new TEntry('referencia');
        $publicacao_id = new TDBCombo('publicacao_id', 'db', 'Publicacao', 'id', 'nome');

        $dia->exitOnEnter();
        $referencia->exitOnEnter();

        $dia->setSize('100%');
        $referencia->setSize('100%');
        $publicacao_id->setSize('100%');

        $dia->tabindex = -1;
        $referencia->tabindex = -1;
        $publicacao_id->tabindex = -1;

        $dia->setExitAction( new TAction([$this, 'onSearch'], ['static'=>'1']) );
        $referencia->setExitAction( new TAction([$this, 'onSearch'], ['static'=>'1']) );
        $publicacao_id->setChangeAction( new TAction([$this, 'onSearch'], ['static'=>'1']) );
        
        // creates a DataGrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->style = 'width: 100%';
        // $this->datagrid->enablePopover('Popover', 'Hi <b> {name} </b>');
        

        // creates the datagrid columns
        $column_dia = new TDataGridColumn('dia', 'Data', 'left');
        $column_referencia = new TDataGridColumn('referencia', 'Referencia', 'left');
        $column_publicacao_id = new TDataGridColumn('{publicacao->nome}', 'Publicacao', 'left');
        $column_local_id = new TDataGridColumn('{publicacao->local->nome}', 'Localidade', 'left');


        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_dia);
        $this->datagrid->addColumn($column_referencia);
        $this->datagrid->addColumn($column_publicacao_id);
        $this->datagrid->addColumn($column_local_id);


        // creates the datagrid column actions
        $column_dia->setAction(new TAction([$this, 'onReload']), ['order' => 'dia']);
        $column_referencia->setAction(new TAction([$this, 'onReload']), ['order' => 'referencia']);
        $column_publicacao_id->setAction(new TAction([$this, 'onReload']), ['order' => 'publicacao_id']);

        // define the transformer method over image
        $column_dia->setTransformer( function($value, $object, $row) {
            if ($value)
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
            return $value;
        });


        
        $action1 = new TDataGridAction(['EmendaForm', 'onEdit'], ['id'=>'{id}']);
        $action2 = new TDataGridAction([$this, 'onDelete'], ['id'=>'{id}']);
        
        $this->datagrid->addAction($action1, _t('Edit'),   'far:edit blue');
        $this->datagrid->addAction($action2 ,_t('Delete'), 'far:trash-alt red');
        
        // create the datagrid model
        $this->datagrid->createModel();
        
        // add datagrid inside form
        $this->form->add($this->datagrid);
        
        // create row with search inputs
        $tr = new TElement('tr');
        $this->datagrid->prependRow($tr);
        
        $tr->add( TElement::tag('td', ''));
        $tr->add( TElement::tag('td', ''));
        $tr->add( TElement::tag('td', $dia));
        $tr->add( TElement::tag('td', $referencia));
        $tr->add( TElement::tag('td', $publicacao_id));

        $this->form->addField($dia);
        $this->form->addField($referencia);
        $this->form->addField($publicacao_id);

        // keep form filled
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data'));
        
        // create the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction([$this, 'onReload']));
        
        $panel = new TPanelGroup('Emenda');
        $panel->add($this->form);
        $panel->addFooter($this->pageNavigation);
        
        // header actions
        $dropdown = new TDropDown(_t('Export'), 'fa:list');
        $dropdown->setPullSide('right');
        $dropdown->setButtonClass('btn btn-default waves-effect dropdown-toggle');
        $dropdown->addAction( _t('Save as CSV'), new TAction([$this, 'onExportCSV'], ['register_state' => 'false', 'static'=>'1']), 'fa:table blue' );
        $dropdown->addAction( _t('Save as PDF'), new TAction([$this, 'onExportPDF'], ['register_state' => 'false', 'static'=>'1']), 'far:file-pdf red' );
        $panel->addHeaderWidget( $dropdown );
        
        $panel->addHeaderActionLink( _t('New'),  new TAction(['EmendaForm', 'onEdit'], ['register_state' => 'false']), 'fa:plus green' );
        
        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($panel);
        
        parent::add($container);
    }
}
