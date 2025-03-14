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
use Adianti\Base\AdiantiStandardFormTrait;

class FilesForm extends TPage
{
    protected $form;
    protected $ovh;
    private $s3handler;
    public function __construct()
    {
        
        parent::__construct();
        
        $this->s3handler = new S3Service;
        
        $this->form = new BootstrapFormBuilder('form_files');
        $this->form->generateAria();
        $this->form->setFormTitle('Novo Torrent');
        $this->form->setFieldSizes('100%');
        $fieldID = new TEntry('id');
        $fieldID->setSize('100%');
        $fieldID->setEditable(FALSE);
        $fieldName = new TEntry('name');
        $fieldName->addValidation('Nome do Arquivo', new TRequiredValidator);
        $fieldName->setSize('100%');
        $fieldType = new TEntry('type');
        $fieldType->addValidation('Tipo de arquivo', new TRequiredValidator);
        $fieldType->setSize('100%');
        $fieldTorrent = new TFile('torrentfile');
        $fieldTorrent->addValidation('Selecione o Arquivo Torrent', new TRequiredValidator);
        $fieldTorrent->setSize('100%');
        $fieldTorrent->enableFileHandling();
        $fieldTorrent->setAllowedExtensions(['torrent']);
        $fieldTorrent->setTip('Selecione um Arquivo Torrent');
        $fieldCover = new TImageCropper('cover');
        $fieldCover->addValidation('Imagem de Capa', new TRequiredValidator);
        $fieldCover->setCropSize(540, 800);
        $fieldCover->setAllowedExtensions(['png', 'jpg', 'jpeg', 'gif']);
        $fieldCover->setTip('Selecione uma Imagem de Capa');
        $fieldCover->enableFileHandling();
        $fieldDescription = new TText('description');
        $fieldDescription->addValidation('Descrição', new TRequiredValidator);
        $fieldDescription->setSize('100%');
        
        $row1 = $this->form->addFields(
            [new TLabel('ID'), $fieldID],
            [new TLabel('Nome do Arquivo'), $fieldName],
            [new TLabel('Tipo de arquivo'), $fieldType],
            [new TLabel('Selecione o Arquivo Torrent'), $fieldTorrent],
            [new TLabel('Imagem de Capa'), $fieldCover],
            [new TLabel('Descrição'), $fieldDescription],
        );
        $row1->layout = ['col-12', 'col-12', 'col-12', 'col-12', 'col-12', 'col-12'];
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
        try {
            TTransaction::open('tracker');
            $object = new File($param['id']);
            $this->form->setData($object);
            TTransaction::close();
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
    public function onShow($param)
    {

    }
    public function onSave($param)
    {
        try {
            $this->form->validate();
            $formData       =   $this->form->getData();
            $fileTorrent    =   json_decode(urldecode($formData->torrentfile));
            $fileTorrentKey =   explode('/', $fileTorrent->newFile);
            $fileTorrentKey =   "torrents/".end($fileTorrentKey);
            $fileCover      =   json_decode(urldecode($formData->cover));
            $fileCoverKey  =   explode('/', $fileCover->newFile);
            $fileCoverKey  =   "covers/".end($fileCoverKey);


            $this->s3handler->fileUploadSse($fileTorrent->newFile, $fileTorrentKey);
            $this->s3handler->fileUploadSse($fileCover->newFile, $fileCoverKey);
            
            TTransaction::open('tracker');
            $object = new File;
            $object->fromArray( (array) $this->form->getData()  );
            $object->cover = $fileCoverKey;
            $object->torrent = $fileTorrentKey;
            $object->store();
            TForm::sendData('form_files', $object->toArray());
            TTransaction::close();


            new TMessage('info', 'Registro salvo com sucesso', NULL, "Tudo certo!");
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
    
    public function onDownload($param)
    {
        try{
            $expiration = '+2 hours';
            $cmd = $this->s3handler->getCommand('GetObject', [
                'Bucket' => $this->ovh['bucket'],
                'Key' => $param['file']
            ]);
            
            $request = $this->s3handler->createPresignedRequest($cmd, $expiration);
            $presignedUrl = (string) $request->getUri();
            

            echo "<p>URL temporária para download: {$presignedUrl}</p>";
            echo "<p>URL temporária para download: {$presignedUrl2}</p>";
        }catch(Exception $e){
            new TMessage('error', $e->getMessage());
        }

    }
}
