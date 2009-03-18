<?php
class paragraph
{
  function paragraph($dba)
  {
     $this->dba = $dba;
     $this->p   = $this->dba->getPrefix();
     $this->table = $this->p .'paragraph';
  }
  function getByName($name)
  {
    $sql = "SELECT * FROM ".$this->table." WHERE name='".addslashes($name)."'";
    return $this->load($sql);
  }
  function getById($id)
  {
    $sql = "SELECT * FROM ".$this->table." WHERE id=".$id;
    return $this->load($sql);
  }
  function load($sql)
  {
      $props = $this->dba->singleArray( $sql );
      if($props)
      {
          $props['name'] = stripslashes($props['name']);
          $props['body'] = stripslashes($props['body']);
      }
      return $props;
  }
  function save($name,$body)
  {
    $para = $this->getByName($name);
    if(!$para) return $this->create($name,$body);
    $this->update($para['id'],$body);
  } 
  function update( $id,$body)
  {
    $sql = "UPDATE 
                ". $this->table ." 
            SET 
                body = '". addslashes( $body) ."' 
            WHERE id=". $id;
    $this->dba->exec( $sql );
  }
  function create($name='',$body='')
  {
    $sql = "INSERT INTO ".$this->table." (name,body) VALUES ('". addslashes($name)."','". addslashes($body)."')";
    $this->dba->exec( $sql );
    return $this->dba->last_inserted_id();
  }
  function isTemaEditor()
  {
    if(!$_SESSION['bruger_id']) return false;

    $bruger = new bruger( new dba() );
    $bruger->setId( $_SESSION['bruger_id'] );
    $props = $bruger->loadBruger();
    return ($props['temaeditor'] == 'y');
  }
  function isEditor() { return ($_SESSION['admin_id'] || $this->isTemaEditor()); }
  function editable($name)
  {
    if(!$this->isEditor()) return;
    return '<a href="javascript:editing(\''.$name.'\')" class="editing"><img align="absmiddle" border="0"  src="tema/graphics/edit.gif"> Rediger "'.$name.'"</a>';
  }
  function body($name)
  {
    $str= $this->editable($name);
    $para = $this->getByName($name);
    if($para) $str.= '<div id="para">'.$para['body'].'</div>';
    return $str;
  }
   
}
?>
