<?php
include_once( "../admin/util/dba.php" );
include_once( "../admin/util/tree.php" );
include_once( "../admin/util/buildingTree.php" );

session_start();

if(!$_SESSION['authenticated']) header('Location:index.php');

$dba = new dba();

$root = 1;
$tree = new buildingTree( $dba, session_id(), 'buildingelements' );
$tree->fullOpen = true;

function load_salesman()
{
  global $dba;
  $sql = "SELECT * FROM ".$dba->getPrefix() ."banner_salesman ";
  $result = $dba->exec( $sql );
  $n      = $dba->getN( $result );
  $salesman= Array();
  for( $i = 0; $i < $n; $i++ )
  {
    $rec = $dba->fetchArray( $result );
    $salesman[$rec['id']] = $rec;
  }
  return $salesman;
}

function load_customers()
{
  global $dba;
  $sql = "SELECT * FROM ".$dba->getPrefix() ."banner_advertiser";
  $result = $dba->exec( $sql );
  $n      = $dba->getN( $result );
  $advertiser = Array();
  for( $i = 0; $i < $n; $i++ )
  {
    $rec = $dba->fetchArray( $result );
    $advertiser[$rec['id']] = $rec;
  }
  return $advertiser;
}

function load_banners()
{
  global $dba;
  $sql = "SELECT * FROM ".$dba->getPrefix() ."banner_management";
  $result = $dba->exec( $sql );
  $n      = $dba->getN( $result );
  $banner = Array();
  for( $i = 0; $i < $n; $i++ )
  {
    $rec = $dba->fetchArray( $result );
    $banner[$rec['construction_id']] = $rec;
  }
  return $banner;
}
$banners = load_banners();
$customers = load_customers();
$salesman = load_salesman();

$extra_nodes = array(
                      array('id'=>'alle_fag', 'name'=>'Bygningsdel - Alle fag', 'level'=>0),
                      array('id'=>'trae_fag', 'name'=>'Bygningsdel - Træfagene', 'level'=>0),
                      array('id'=>'murer_fag', 'name'=>'Bygningsdel - Murerfaget', 'level'=>0),
                      array('id'=>'struktorer_fag', 'name'=>'Bygningsdel - Struktører', 'level'=>0),
                      array('id'=>'maler_fag', 'name'=>'Bygningsdel - Malerfaget', 'level'=>0),
                      array('id'=>'kloak_fag', 'name'=>'Bygningsdel - Kloakfaget', 'level'=>0),
                      array('id'=>'soeg', 'name'=>'Søg', 'level'=>0),
                      array('id'=>'bibliotek', 'name'=>'Bibliotek', 'level'=>0),
                      array('id'=>'produkter', 'name'=>'Produkter', 'level'=>0)
                    );
?>
<html>
	<head>
		<title>Term oversigt</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <style>
            body, td { font-size:11px;font-family:verdana,sans-serif;color:#333; }
            #overview { background-color:#fff; }
            #overview .odd { background-color:#e3e3e3; }
            #overview .even { background-color:#cdcdcd; }
            .header_group { background-color:#999;}
            .header { background-color:#999;}
            .header a { color:#333;text-decoration:none;font-weight:900; }
            .sold { background-color:#008000 }
            .reserved { background-color:#EAAB12}

            #overview .even,.odd {cursor:pointer}
            #overview .even .sold { background-color:#96B896; }
            #overview .odd .sold{ background-color:#A6C8A6; }

            #overview .even .reserved{ background-color:#D5C49A; }
            #overview .odd .reserved{ background-color:#E5D4AA; }

            h1 { border-bottom:1px solid #999;font-weight:100;font-size:18px;padding-bottom:5px;margin-bottom:25px; }
            h1 #settings { font-size:11px;float:right; }
        </style>
	</head>
  <body bgcolor="#FFFFFF" >
        <?php
            if(! $_SESSION['nodes'] || $_REQUEST['flush']=='1' )
            {
                $nodes =  $tree->getNodeArray();
                $_SESSION['nodes'] = $nodes;
            }
            else
            {
                $nodes = $_SESSION['nodes'];
                echo "<!--cache mache-->";
            }

            $nodes = array_merge($extra_nodes,$nodes);
            $n = count( $nodes );
            
        ?>
        <h1>
            <span id="settings">
                Velkomen <?=$_SESSION['name']?> | 
                <a href="index.php">log-ud</a> 
                <?if($_SESSION['uid']==1):?>
                    | <a href="users.php">Bruger administration</a>
                <?endif?>
            </span>

            Oversigt   
        </h1>
        <table cellpadding="4" cellspacing="1" border="0" id="overview">
            <tr>
                <td style="background-color:#999" rowspan="2" valign="bottom">Term</td>
                <td class="sold" colspan="4" align="center">Solgt</td>
                <td class="reserved" colspan="4" align="center">Reserveret</td>
            </tr>
            <tr class="header">
                <!--<td><a href="#">Term</a></td>-->

                <td class="sold">Annonc&oslash;r</td>
                <td class="sold">S&aelig;lger</td>
                <td class="sold" nowrap>Start periode</td>
                <td class="sold" nowrap>Slut periode</td>

                <td class="reserved">Annonc&oslash;r</td>
                <td class="reserved">S&aelig;lger</td>
                <td class="reserved" nowrap>Start periode</td>
                <td class="reserved" nowrap>Slut periode</td>
            </tr>
            <script>
                function select_term(id,name,banner_id)
                {
                    var props = 'width=600,height=600,toolbar=no,directories=no,status=no';
                    var w = window.open('term.php?element_id='+ id +'&banner_id='+ banner_id +'&name='+ name,'term',props);
                    w.focus();
                }
            </script>
            <?for( $i = 0; $i < $n; $i++ ):?>
                <?if($i== 0) continue?>
                <?
                    $banner_id = '0';
                    $customer_sold = '';
                    $salesman_sold = '';
                    $start_sold='';
                    $end_sold='';
                    $class_sold = '';

                    $customer_reserved='';
                    $salesman_reserved ='';
                    $start_reserved='';
                    $end_reserved='';
                    $class_reserved='';

                    if(array_key_exists($nodes[$i]['id'],$banners))
                    {
                        $banner = $banners[$nodes[$i]['id']];
                        $banner_id = $banner['id'];

                        if(array_key_exists($banner['sold_to'],$customers))
                        {
                            $customer_sold = $customers[$banner['sold_to']]['name'];
                            $salesman_sold = $salesman[$banner['sold_by']]['name'];
                            $start_sold=$banner['sold_period_start'];
                            $end_sold=$banner['sold_period_end'];
                            $class_sold="sold";
                        }

                        if(array_key_exists($banner['reserved_to'],$customers))
                        {
                            $customer_reserved= $customers[$banner['reserved_to']]['name'];
                            $salesman_reserved= $salesman[$banner['reserved_by']]['name'];
                            $start_reserved=$banner['reserved_period_start'];
                            $end_reserved=$banner['reserved_period_end'];
                            $class_reserved="reserved";
                        }
                    }
                ?>
                <tr class="<?=($i%2==0)?'odd':'even'?>"
                    onclick="select_term('<?=$nodes[$i]['id']?>','<?=$nodes[$i]['name']?>','<?=$banner_id?>')"
                    >
                    <td style="padding-left:<?=( $nodes[$i]["level"] ) * 10 ?>px">
                        <a name="element_<?=$nodes[$i]['id']?>" />
                        <?=$nodes[$i]["name"] ?>
                    </td>


                    <td class="<?=$class_sold?>"><?=$customer_sold?></td>
                    <td class="<?=$class_sold?>"><?=$salesman_sold?></td>
                    <td class="<?=$class_sold?>"><?=$start_sold?></td>
                    <td class="<?=$class_sold?>"><?=$end_sold?></td>

                    <td class="<?=$class_reserved?>"><?=$customer_reserved?></td>
                    <td class="<?=$class_reserved?>"><?=$salesman_reserved?></td>
                    <td class="<?=$class_reserved?>"><?=$start_reserved?></td>
                    <td class="<?=$class_reserved?>"><?=$end_reserved?></td>
                </tr>
            <?endfor?>
        </table>
        <?if($_REQUEST['element_id']):?>
            <script>
                document.location.hash ='element_<?=$_REQUEST['element_id']?>';
            </script>
        <?endif?>
	</body>
</html>
