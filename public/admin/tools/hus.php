<?php
require_once("../util/dba.php");
$dba = new dba();
$prefix = $p =  $dba->getPrefix();

$dba->exec("DROP TABLE IF EXISTS dev_houses");
$sql = "
CREATE TABLE  `dev_houses` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `x` int(11) default NULL,
  `y` int(11) default NULL,
  `branche_id` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `pointer` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
)";
$dba->exec( $sql );
echo ("houses table created <br />\n");

?>
