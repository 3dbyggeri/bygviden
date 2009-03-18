<?php
/*
  insert into the stats db the following values:
  - sessid         # the session id
  - machine_id     # the machine id
  - user_id        # if the user is logged, the user id
  - element_id     # if the page_type is 'tree' and there is and element id
  - publication_id # add by display_document
  - branche_id     # the selected branche id
  - referer_id     # id of the last inserted hit
  - page_type      # can be tree,search,library,document
*/
$sessid = session_id();
$machine_id = $_COOKIE['machine_id'];
$user_id = ($_SESSION['bruger_id'])? $_SESSION['bruger_id']:'NULL';
$element_id = ($_SESSION['element'] && $current_page =='elements')? $_SESSION['element']:'NULL';
$publication_id = (is_numeric($publication_id))? $publication_id:'NULL';
$branche_id = ($_SESSION['branche'])? "'".$_SESSION['branche']."'":'NULL';
$referer_id = ($_SESSION['referer_id'])? $_SESSION['referer_id']:'NULL';
$page_type = ($current_page)? "'".$current_page."'":'NULL';
$browser_ip = $_SERVER['REMOTE_ADDR']; 


if($current_page=='search' && trim($_GET['query']) )
{
  $query = $_GET['query'];
  $n_results = 0;
  foreach( $publikationer as $key=>$value ) $n_results+= count($value);
  $page_type = 'results';

  $sql = "INSERT INTO 
            dev_stats
          ( 
            sessid,
            machine_id,
            user_id,
            element_id,
            publication_id,
            branche_id,
            referer_id,
            page_type,
            query_terms,
            results,
            browser_ip
          )
          VALUES
          (
            '$sessid',
            '$machine_id',
            $user_id,
            $element_id,
            $publication_id,
            $branche_id,
            $referer_id,
            '$page_type',
            '$query',
            $n_results,
            '$browser_ip'
          )";
}
else
{
  $sql = "INSERT INTO 
            dev_stats
          ( 
            sessid,
            machine_id,
            user_id,
            element_id,
            publication_id,
            branche_id,
            referer_id,
            page_type,
            browser_ip
          )
          VALUES
          (
            '$sessid',
            '$machine_id',
            $user_id,
            $element_id,
            $publication_id,
            $branche_id,
            $referer_id,
            $page_type,
            '$browser_ip'
          )";
}
//don't register spiders from bygviden
$dba = new dba();
if($browser_ip != '193.201.39.207' && $browser_ip !='193.201.39.187') $dba->exec($sql);
$_SESSION['referer_id'] = $dba->last_inserted_id();
?>
