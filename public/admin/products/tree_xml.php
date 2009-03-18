<?php 
require("../util/dba.php");

$dba  = new dba();
$root = 133;

function getElements($parent)
{
  global $dba;
  $sql = "SELECT * FROM dev_varegrupper WHERE parent=".$parent." ORDER BY position,name";
  $result = $dba->exec( $sql );
  $n = $dba->getN( $result );

  for( $i = 0; $i < $n; $i++ )
  {
    $node  = $dba->fetchArray( $result );
    $node["name"] = stripslashes ( $node["name"] );
  
     echo '<category id="'. $node['id'].'" >';
     echo '<name><![CDATA['.$node['name'].']]></name>';
     echo '<children>'.getElements($node['id']).'</children>';
     echo '</category>';
  }
}
header('Content-type:text/xml');
echo '<?xml version="1.0" encoding="ISO-8859-1"?>';
echo '<categories>';
getElements($root);
echo '</categories>';
?>
