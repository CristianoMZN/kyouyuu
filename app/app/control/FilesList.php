<?php
/**
 * FilesList
 *
 * @version    8.0
 * @package    control
 * @author     Cristiano Mozena
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */

class FilesList extends TPage
{
    protected $form;
    use Adianti\Base\AdiantiStandardFormTrait;
    public function __construct()
    {
        parent::__construct();
        TTransaction::open('tracker');
        $file_repository = new TRepository('File');
        $files = $file_repository->load();
        TTransaction::close();
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->style = 'width:100%';

        $id     = new TDataGridColumn('id',    'ID',    'center', '10%');
        $name   = new TDataGridColumn('name',    'Name',    'center', '90%');
        $edit_action   = new TDataGridAction(['FilesForm', 'onEdit'], ['id'=>'{id}']);
        $delete_action = new TDataGridAction([$this, 'onDelete'], ['id'=>'{id}']);
        $view_action   = new TDataGridAction(['FilesView', 'onShow'], ['id'=>'{id}']);
        $this->datagrid->addAction($edit_action, 'Edit', 'fa:edit blue');
        $this->datagrid->addAction($delete_action, 'Delete', 'fa:trash red');
        $this->datagrid->addAction($view_action, 'View', 'fa:search green');

        $this->datagrid->addColumn($id);
        $this->datagrid->addColumn($name);
        $this->datagrid->createModel();

        $this->datagrid->addItems($files); 
        $panel = new TPanelGroup('Todos os arquivos');
        $panel->add( $this->datagrid );
        $panel->addFooter('Todos os torrents');

        $link = new TAction(['FilesForm', 'onShow'], ['register_state' => 'false']);
        $panel->addHeaderActionLink('Novo', $link, 'fa:plus green');

        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add($panel);

        parent::add($vbox);
    }
    public function onDelete($param)
    {
        try{
            TTransaction::open('tracker');
            $object = new File($param['id']);
            $object->delete();
            TTransaction::close();
            new TMessage('info', 'Registro deletado com sucesso', NULL, "Tudo certo!");
            $this->onReload($param);
        }catch(Exception $e){
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
    public function onReload($param)
    {
        TTransaction::open('tracker');
        $file_repository = new TRepository('File');
        $files = $file_repository->load();
        TTransaction::close();
        $this->datagrid->clear();
        $this->datagrid->addItems($files);
    }
    public function onShow($param)
    {
        $this->onReload($param);
    }
    

}