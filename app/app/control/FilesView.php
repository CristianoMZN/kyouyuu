<?php
/**
 * FilesView
 *
 * @version    8.0
 * @package    control
 * @author     Cristiano Mozena
 */
class FilesView extends TPage
{
    private $file;
    private $s3handler;
    public function __construct($param = null)
    {
        parent::__construct();
        $this->s3handler = new S3Service;
        TTransaction::open('tracker');
        $this->file = new File($param['id']);
        TTransaction::close();
        $coverImage     = $this->s3handler->fileGetTempLink($this->file->cover);
        $torrentFile    = $this->s3handler->fileGetTempLink($this->file->torrent);
        $html = new THtmlRenderer('app/view/fileView.html');
        $replaces['name']      = $this->file->name;
        $replaces['type']      = $this->file->type;
        $replaces['description']    = $this->file->description;
        $replaces['cover']          = $coverImage;
        $replaces['torrent']        = $torrentFile;
        $replaces['created_at']    = $this->file->created_at;
        $html->enableSection('main', $replaces);
        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        //$vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add($html);

        parent::add($vbox);  
    }
    public function onShow($param)
    {
        
    }
    
    
}