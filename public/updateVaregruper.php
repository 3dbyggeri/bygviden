<?php
require_once('admin/util/dba.php');
$dba = new dba();

$sql = "SELECT  p.id,p.varegruppe_id
        FROM  dev_products  as p,
        dev_varegrupper as vg
        where p.varegruppe_id = vg.id";

$result = $dba->exec( $sql );
$n      = $dba->getN( $result );
$products = array();
for( $i = 0; $i < $n; $i++ )
{
  $rec = $dba->fetchArray($result); 
  $products[$rec['id']] = $rec['varegruppe_id'];

}
echo count($products)."<br>";
foreach( $products as $k=>$v) 
{
  $sql = "INSERT INTO dev_product2varegruppe (product_id,varegruppe_id)
          VALUES(".$k.",".$v.")";
  
  $dba->exec($sql);
  echo $sql."<br>";
}
?>
