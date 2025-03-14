<?php
/**
 * File
 *
 * @version    8.0
 * @package    model
 * @subpackage tracker
 * @author     Cristiano Mozena
 */ 
class File extends TRecord
{
    const TABLENAME = 'file';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('owner');
        parent::addAttribute('name');
        parent::addAttribute('hash');
        parent::addAttribute('size');
        parent::addAttribute('type');
        parent::addAttribute('cover');
        parent::addAttribute('torrent');
        parent::addAttribute('description');
        parent::addAttribute('created_at');
        parent::addAttribute('updated_at');
    }
    public function delete($id = null)
    {
        $id = isset($id) ? $id : $this->id;
        
        File::where('id', '=', $this->id)->delete();
        
        parent::delete($id);
    }
}