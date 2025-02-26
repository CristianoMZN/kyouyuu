<?php
/**
 * FilesForm
 *
 * @version    8.0
 * @package    control
 * @author     Cristiano Mozena
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */

class FilesForm extends TPage
{
    protected $form;
    use Adianti\Base\AdiantiStandardFormTrait;
    public function __construct()
    {
        parent::__construct();
        $this->form = new BootstrapFormBuilder;
        $this->form->generateAria();
        $id = new TEntry('id');
        $name = new TEntry('name');
        $type = new TEntry('type');
        $cover = new TImageCropper('cover');
        $description = new TText('description');
        $description->setSize('100%', 50);
        $cover->setCropSize(200, 200);
        $cover->setAllowedExtensions(['png', 'jpg', 'jpeg', 'gif']);
        $this->form->addFields( [new TLabel('Id')],  [$id]);
        $this->form->addFields( [new TLabel('Nome')],  [$name]);
        $this->form->addFields( [new TLabel('Tipo')],  [$type]);
        $this->form->addFields( [new TLabel('Imagem')],  [$cover]);
        $this->form->addFields( [new TLabel('Id')],  [$description]);
        $cover->setTip('Select a file');
        $cover->enableFileHandling();
        $this->form->addAction('Salvar', new TAction([$this, 'onSave']), 'far:check-circle green');
        // wrap the page content using vertical box
        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add($this->form);

        parent::add($vbox);

    }
    public function onEdit($param)
    {
        
    }
    public function onShow($param)
    {

    }
    public function onSave($param)
    {
        try {
            $this->form->validate();
            
            TTransaction::open('tracker');
            $object = new File;
            $object->fromArray( (array) $this->form->getData());
            $object->store();
            TTransaction::close();
            new TMessage('info', 'Registro salvo com sucesso', NULL, "Tudo certo!");
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
    public function onDelete($param)
    {

    }
}