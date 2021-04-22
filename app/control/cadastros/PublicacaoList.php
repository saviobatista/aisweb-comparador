<?php
/**
 * PublicacaoList Listing
 * @author  Sávio Batista <saviobatista@email.com>
 */
class PublicacaoList extends TPage
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
        $this->setActiveRecord('Publicacao');   // defines the active record
        $this->setDefaultOrder('id', 'asc');         // defines the default order
        $this->setLimit(50);
        // $this->setCriteria($criteria) // define a standard filter

        $this->addFilterField('nome', 'like', 'nome'); // filterField, operator, formField
        $this->addFilterField('categoria_id', '=', 'categoria_id'); // filterField, operator, formField
        $this->addFilterField('local_id', '=', 'local_id'); // filterField, operator, formField
        $this->addFilterField('origem_id', '=', 'origem_id'); // filterField, operator, formField

        $this->form = new TForm('form_search_Publicacao');
        
        $nome = new TEntry('nome');
        $categoria_id = new TDBCombo('categoria_id', 'db', 'Categoria', 'id', 'nome');
        $local_id = new TDBCombo('local_id', 'db', 'Local', 'id', 'nome');
        $origem_id = new TDBCombo('origem_id', 'db', 'Origem', 'id', 'nome');

        $nome->exitOnEnter();

        $nome->setSize('100%');
        $categoria_id->setSize('100%');
        $local_id->setSize('100%');
        $origem_id->setSize('100%');

        $nome->tabindex = -1;
        $categoria_id->tabindex = -1;
        $local_id->tabindex = -1;
        $origem_id->tabindex = -1;

        $nome->setExitAction( new TAction([$this, 'onSearch'], ['static'=>'1']) );
        $categoria_id->setChangeAction( new TAction([$this, 'onSearch'], ['static'=>'1']) );
        $local_id->setChangeAction( new TAction([$this, 'onSearch'], ['static'=>'1']) );
        $origem_id->setChangeAction( new TAction([$this, 'onSearch'], ['static'=>'1']) );
        
        // creates a DataGrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->style = 'width: 100%';
        // $this->datagrid->enablePopover('Popover', 'Hi <b> {name} </b>');
        

        // creates the datagrid columns
        $column_nome = new TDataGridColumn('nome', 'Nome', 'left');
        $column_categoria_id = new TDataGridColumn('{categoria->nome}', 'Categoria', 'left');
        $column_local_id = new TDataGridColumn('{local->nome}', 'Local', 'left');
        $column_origem_id = new TDataGridColumn('{origem->nome}', 'Origem', 'left');


        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_nome);
        $this->datagrid->addColumn($column_categoria_id);
        $this->datagrid->addColumn($column_local_id);
        $this->datagrid->addColumn($column_origem_id);

        
        $action1 = new TDataGridAction(['PublicacaoForm', 'onEdit'], ['id'=>'{id}']);
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
        $tr->add( TElement::tag('td', $nome));
        $tr->add( TElement::tag('td', $categoria_id));
        $tr->add( TElement::tag('td', $local_id));
        $tr->add( TElement::tag('td', $origem_id));

        $this->form->addField($nome);
        $this->form->addField($categoria_id);
        $this->form->addField($local_id);
        $this->form->addField($origem_id);

        // keep form filled
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data'));
        
        // create the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction([$this, 'onReload']));
        
        $panel = new TPanelGroup('Publicacao');
        $panel->add($this->form);
        $panel->addFooter($this->pageNavigation);
        
        // header actions
        $dropdown = new TDropDown(_t('Export'), 'fa:list');
        $dropdown->setPullSide('right');
        $dropdown->setButtonClass('btn btn-default waves-effect dropdown-toggle');
        $dropdown->addAction( _t('Save as CSV'), new TAction([$this, 'onExportCSV'], ['register_state' => 'false', 'static'=>'1']), 'fa:table blue' );
        $dropdown->addAction( _t('Save as PDF'), new TAction([$this, 'onExportPDF'], ['register_state' => 'false', 'static'=>'1']), 'far:file-pdf red' );
        $panel->addHeaderWidget( $dropdown );
        
        $panel->addHeaderActionLink( _t('New'),  new TAction(['PublicacaoForm', 'onEdit'], ['register_state' => 'false']), 'fa:plus green' );
        
        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($panel);
        
        parent::add($container);
    }
}
