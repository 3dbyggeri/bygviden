<?php
require_once("../util/dba.php");
$dba = new dba();
$prefix = $p =  $dba->getPrefix();

$dba->exec("DROP TABLE IF EXISTS dev_branche_tree");
$sql = "
CREATE TABLE  `dev_branche_tree` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `parent` int(11) default NULL,
  `element_id` int(11) default NULL,
  `branche_id` int(11) default NULL,
  `position` int(11) default NULL,
  `level` int(11) default NULL,
  `path` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
)";
$dba->exec( $sql );
$status.= $p."branche_tree table created <br />\n";

//insert roots
$sql = " INSERT INTO `dev_branche_tree` (parent,branche_id,path,level) 
         VALUES (0,1,'',0), (0,2,'',0), (0,3,'',0), (0,4,'',0), (0,5,'',0), (0,6,'',0)";
$dba->exec( $sql );
$status.= $p."branche_tree table created <br />\n";

function childrens($table,$parent,$branche,$level,$path,$new_parent)
{
    global $dba;
    $sql = "SELECT * FROM $table WHERE parent=$parent ORDER BY position";
    echo($sql."<br>");
    $result = $dba->exec( $sql );
    $n      = $dba->getN( $result );

    $child = array();
    for( $i = 0; $i < $n; $i++ )
    {
        $child[$i] = $dba->fetchArray( $result );
    }

    foreach($child as $key=>$value) 
    {
        if(!is_numeric($value['element_id'])) continue;
        $sql = "INSERT INTO dev_branche_tree (parent,element_id,branche_id,level,path,position)
                VALUES($new_parent,".$value['element_id'].",$branche,$level,'$path','".$value['position']."')
               ";
        echo($sql."<br>");

        $dba->exec( $sql );
        $new_id = $dba->last_inserted_id();
        $new_path = ($path!='')? $path.','.$new_id:$new_id;
        childrens($table,$value['id'],$branche,($level + 1),$new_path,$new_id);
    }
}

childrens('dev_general',1,1,1,'',1);
childrens('dev_trae',1,2,1,'',2);
childrens('dev_mur',1,3,1,'',3);
childrens('dev_beton',1,4,1,'',4);
childrens('dev_maling',1,5,1,'',5);
childrens('dev_kloak',1,6,1,'',6);

/*
SELECT
    bt2.*,
    b.id,
    b.name 
FROM 
    dev_branche_tree as bt1,
    dev_branche_tree as bt2,
    dev_buildingelements as b
WHERE 
    bt1.id=136 
AND
    bt2.element_id = b.id
AND
    bt2.id in (96,116,133,134,135)
                (bt1.path)
*/
?>
