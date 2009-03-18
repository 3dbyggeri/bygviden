<?php
class blog
{
  var $dba;
  var $p;
  var $table;
  var $id;
  var $properties;

  function listing($publish=0)
  {
    $sql="SELECT * FROM ".$this->table; 
    if($publish) $sql.= " WHERE publish='y' ";
    $sql.=" ORDER BY id DESC ";

    $result = $this->dba->exec( $sql );
    $n      = $this->dba->getN( $result );
    
    $posts =array(); 
    for( $i = 0; $i < $n; $i++ )
    {
      $posts[$i] = $this->dba->fetchArray( $result );
      $posts[$i]['title'] = stripslashes($posts[$i]['title']);
      $posts[$i]['post'] = stripslashes($posts[$i]['post']);
    }
    return $posts;
  }

  function blog($dba)
  {
     $this->dba = $dba;
     $this->p   = $this->dba->getPrefix();
     $this->table = $this->p .'blog';
  }

  function add()
  {
    $sql = "INSERT INTO ". $this->table ." ( title,created,edited ) VALUES('Ny post',NOW(),NOW())";
    $this->dba->exec( $sql );
    return  $this->dba->last_inserted_id();
  }

  function remove($id )
  {
    $sql = "DELETE FROM ". $this->table." WHERE id=$id";
    $this->dba->exec( $sql );
  }
  function load($id)
  {
      $sql = "SELECT * FROM ".$this->table ." WHERE id = ". $id;
      $props = $this->dba->singleArray( $sql );

      $props['title'] = stripslashes($props['title']);
      $props['post'] = stripslashes($props['post']);
      return $props;
  }
  function update( $id,$title,$post,$publish=0)
  {
    $sql = "UPDATE ". $this->table ." 
           SET title= '". addslashes( $title) ."'
           ,edited=NOW()
           ,post= '". addslashes( $post) ."' ";

    if($publish) $sql.= ",publish='y' ";

    $sql.= " WHERE id = ". $id;
    $this->dba->exec( $sql );
  }
  function publish_state($id,$publish)
  {
    $sql = "UPDATE ". $this->table ." SET publish='$publish'";
    $sql.= " WHERE id = ". $id;
    $this->dba->exec( $sql );
  }

}
?>
