<?php
include_once( "../util/dba.php" );
include_once( "../util/tree.php" );
//include_once( "../util/brancheTree.php" );
include_once('../util/brancheTreeNew.php');
include_once( "../util/user.php" );

session_start();
$dba = new dba();
$user = new user( $dba );
$brancher = array();

function loadBrancher()
{
  global $dba;
  global $brancher;
  if(count($brancher) == 0)
  {
      $result = $dba->exec("SELECT id, name, label FROM dev_brancher ");
      $n = $dba->getN( $result );

      for( $i = 0; $i < $n; $i++ )
      {
        $brancher[$i] = $dba->fetchArray( $result );
      }
  }
}

function branche_id($branche_name)
{
  global $brancher;
  loadBrancher();
  for($i=0;$i<count($brancher);$i++)
  {
      if($brancher[$i]['name'] == $branche_name) return $brancher[$i]['id'];
  }
  return 0;
}


function getElements($parent)
{
  global $dba;
  $sql = "SELECT
            dev_branche_tree.id AS 'id',
            dev_branche_tree.path AS 'path',
            dev_branche_tree.level AS 'level',
            dev_buildingelements.name AS 'name',
            dev_buildingelements.id AS 'element_id'
         FROM
            dev_branche_tree,
            dev_buildingelements
         WHERE
            dev_branche_tree.parent =". $parent."
         AND
            dev_branche_tree.element_id = dev_buildingelements.id
         ORDER BY
            dev_branche_tree.position";
  $result = $dba->exec( $sql );
  $n = $dba->getN( $result );

  for( $i = 0; $i < $n; $i++ )
  {
    $node  = $dba->fetchArray( $result );
    $node["name"] = stripslashes ( $node["name"] );
  
     echo '<element id="'. $node['id'].'" name="'. $node['name'].'">';
     getElements($node['id']);
     echo '</element>';
  }
}
header('Content-type:text/xml');
loadBrancher();
echo '<?xml version="1.0" encoding="ISO-8859-1"?>';
echo '<brancher>';
for($i=0;$i<count($brancher);$i++)
{
  $id = $dba->singleQuery("SELECT id FROM dev_branche_tree WHERE branche_id='".$brancher[$i]['id']."' AND parent=0");
  echo '<branche id="'.$brancher[$i]['id'].'" name="'.$brancher[$i]['name'].'">';
  getElements($brancher[$i]['id']);
  echo '</branche>';
}
echo '</brancher>';
?>
