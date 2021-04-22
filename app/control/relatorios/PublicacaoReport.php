<?php
/**
 * PublicacaoReport Listing
 * @author  Sávio Batista <saviobatista@email.com>
 */
class PublicacaoReport extends TPage
{
    protected $form;     // registration form
    protected $datagrid; // listing
    protected $pageNavigation;
    protected $formgrid;
    protected $deleteButton;
    
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
        $this->setLimit(20);
        // $this->setCriteria($criteria) // define a standard filter

        $this->addFilterField('id', '=', 'id'); // filterField, operator, formField
        $this->addFilterField('local_id', '=', 'local_id'); // filterField, operator, formField
        $this->addFilterField('categoria_id', '=', 'categoria_id'); // filterField, operator, formField
        $this->addFilterField('origem_id', '=', 'origem_id'); // filterField, operator, formField
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_search_Publicacao');
        $this->form->setFormTitle('Publicacao');
        

        // create the form fields
        $id = new TDBCombo('id', 'db', 'Publicacao', 'id', 'nome');
        $local_id = new TDBCombo('local_id', 'db', 'Local', 'id', 'nome');
        $categoria_id = new TDBCombo('categoria_id', 'db', 'Categoria', 'id', 'nome');
        $origem_id = new TDBCombo('origem_id', 'db', 'Origem', 'id', 'nome');


        // add the fields
        $this->form->addFields( [ new TLabel('Publicação') ], [ $id ] );
        $this->form->addFields( [ new TLabel('Local') ], [ $local_id ] );
        $this->form->addFields( [ new TLabel('Categoria') ], [ $categoria_id ] );
        $this->form->addFields( [ new TLabel('Origem') ], [ $origem_id ] );


        // set sizes
        $id->setSize('100%');
        $local_id->setSize('100%');
        $categoria_id->setSize('100%');
        $origem_id->setSize('100%');

        
        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );
        
        // add the search form actions
        $btn = $this->form->addAction(_t('Find'), new TAction([$this, 'onSearch']), 'fa:search');
        $btn->class = 'btn btn-sm btn-primary';
        // creates a Datagrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->style = 'width: 100%';
        //$this->datagrid->datatable = 'true';
        //$this->datagrid->enablePopover('Popover', '<b> {nome} - {local->nome} - {categoria->nome} - {origem->nome} </b>');
        

        // creates the datagrid columns
        $column_comparador = new TDataGridColumn('id', 'Comparador', 'left');
        $column_anterior = new TDataGridColumn('anterior', 'Anterior', 'left');
        $column_atual = new TDataGridColumn('atual', 'Atual', 'left');
        $column_proximo = new TDataGridColumn('proximo', 'Futura', 'left');
        $column_oficial = new TDataGridColumn('oficial', 'Oficial', 'left');
        
        $column_anterior->setTransformer(function($value){
            return nl2br($value);
        });
        $column_comparador->setTransformer(function($value,$obj,$row){
            $old = base64_encode(utf8_decode($obj->atual));
            $new = base64_encode(utf8_decode($obj->proximo));
            return "<h2>{$obj->local->nome} - {$obj->categoria->nome} - {$obj->nome} (Fonte:{$obj->origem->nome})</h2><div id=\"comparador_{$obj->id}\" style=\"height:300px\"></div><script>".<<<JS
require.config({ paths: { vs: 'app/resources/monaco' } });
require(['vs/editor/editor.main'], function () {
monaco.editor.createDiffEditor(document.getElementById('comparador_{$obj->id}')).setModel({
	original: monaco.editor.createModel(atob("$old"), 'text'),
	modified: monaco.editor.createModel(atob("$new"), 'text')
});
			});
JS
."</script>";
        });
        $column_proximo->setTransformer(function($value){
            return nl2br($value);
        });
        $column_oficial->setTransformer(function($value){
            return nl2br($value);
        });

        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_comparador);
        /*$this->datagrid->addColumn($column_anterior);
        $this->datagrid->addColumn($column_atual);
        $this->datagrid->addColumn($column_proximo);
        $this->datagrid->addColumn($column_oficial);*/
        
        // create the datagrid model
        $this->datagrid->createModel();
        
        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction([$this, 'onReload']));
        
        $panel = new TPanelGroup('', 'white');
        $panel->add($this->datagrid);
        $panel->addFooter($this->pageNavigation);
        
        // header actions
        $dropdown = new TDropDown(_t('Export'), 'fa:list');
        $dropdown->setPullSide('right');
        $dropdown->setButtonClass('btn btn-default waves-effect dropdown-toggle');
        $dropdown->addAction( _t('Save as CSV'), new TAction([$this, 'onExportCSV'], ['register_state' => 'false', 'static'=>'1']), 'fa:table blue' );
        $dropdown->addAction( _t('Save as PDF'), new TAction([$this, 'onExportPDF'], ['register_state' => 'false', 'static'=>'1']), 'far:file-pdf red' );
        //$panel->addHeaderWidget( $dropdown );
        
        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);
        $container->add($panel);
        
        parent::add($container);
    }
}
