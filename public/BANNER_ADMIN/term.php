<?php
include_once( "../admin/util/dba.php" );
session_start();
if(!$_SESSION['authenticated']) die('');
$dba = new dba();
$banner_id = $_REQUEST['banner_id'];
if(!is_numeric($id)) $id ='0';
$element_id = $_REQUEST['element_id'];
$element_name = $_REQUEST['name'];


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
function add_customer($name)
{
    global $dba;

    //check if the name exists
    $sql = "SELECT * FROM ".$dba->getPrefix()."banner_advertiser WHERE name='".addslashes(trim($name))."'";
    $customer = $dba->singleQuery($sql);
    if($customer) return $customer['id'];

    $sql = "INSERT INTO ".$dba->getPrefix()."banner_advertiser
               ( name ) VALUES ( '".addslashes(trim($name))."')";
   $dba->exec($sql);
   return $dba->last_inserted_id();
}
function update_reserved($id,$customer,$salesman,$start,$end)
{
    global $dba;
    $sql = "UPDATE ".$dba->getPrefix() ."banner_management 
            SET 
                reserved_to='$customer',
                reserved_by='$salesman',
                reserved_period_start='$start',
                reserved_period_end='$end'
            WHERE
                id=$id";
    $dba->exec($sql);
}
function update_sold($id,$customer,$salesman,$start,$end)
{
    global $dba;
    $sql = "UPDATE ".$dba->getPrefix() ."banner_management 
            SET 
                sold_to='$customer',
                sold_by='$salesman',
                sold_period_start='$start',
                sold_period_end='$end'
            WHERE
                id=$id";
    $dba->exec($sql);
}
function update_banner()
{
    global $dba;
    $banner_id = $_POST['banner_id'];
    $element_id = $_POST['element_id'];
    $element_name = $_POST['element_name'];

    $customer_sold = trim($_POST['banner_advertiser_sold']);
    $salesman_sold = trim($_POST['banner_salesman_sold']);
    $start_sold = trim($_POST['start_sold']);
    $end_sold = trim($_POST['end_sold']);

    $customer_reserved = trim($_POST['banner_advertiser_reserved']);
    $salesman_reserved = trim($_POST['banner_salesman_reserved']);
    $start_reserved = trim($_POST['start_reserved']);
    $end_reserved = trim($_POST['end_reserved']);

    if($banner_id=='0')
    {
       $sql = "INSERT INTO ".$dba->getPrefix()."banner_management
               ( construction_id, construction_name )
               VALUES ( '$element_id','".addslashes($element_name)."')";
       
       $dba->exec($sql);
       $banner_id = $dba->last_inserted_id();
    }

    if($customer_sold=='-1' && $customer_reserved=='-1')  //no customers, remove the entry
    {
        $sql = "DELETE FROM ".$dba->getPrefix()."banner_management WHERE id=".$banner_id;
        $dba->exec($sql);
        return $banner_id;
    }

    
    if(!is_numeric($customer_sold)) $customer_sold = add_customer($customer_sold);

    if(!is_numeric($customer_reserved)) $customer_reserved = add_customer($customer_reserved);

    update_sold($banner_id,$customer_sold,$salesman_sold,$start_sold,$end_sold);
    update_reserved($banner_id,$customer_reserved,$salesman_reserved,$start_reserved,$end_reserved);
    return $banner_id;
}

function load_banner()
{
    global $dba;
    $id = $_GET['banner_id'];
    if(!is_numeric($id)) return array();
    $sql = "SELECT * FROM ".$dba->getPrefix()."banner_management where id=".$id;
    $banner = $dba->singleArray($sql);
    if(!$banner) return array();
    return $banner;
}


if($_POST['save'])
{
    update_banner();
    die('<script>opener.location.href="overview.php?element_id='.$element_id.'";window.close();</script>');
}

$users = load_salesman();
$advertisers = load_customers();

$banner = load_banner();
?>
<html>
    <head>
        <title><?=$element_name?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link rel="stylesheet" type="text/css" media="all" href="calendar/calendar-win2k-cold-1.css" title="win2k-cold-1" />
        <SCRIPT SRC="calendar/calendar.js" TYPE="text/javascript"></SCRIPT>
        <script src="calendar/calendar-setup.js" type="text/javascript"></script>
        <script type="text/javascript" src="calendar/lang/calendar-en.js"></script>
        <style>
            body, td { font-size:11px;font-family:verdana,sans-serif;color:#333; }
            body { margin:30px }
            h1 { border-bottom:1px solid #999;font-weight:100;font-size:18px;padding-bottom:5px;margin-bottom:20px; }
            h2 { font-weight:100;font-size:12px; }
            .sold { background-color:#A6C8A6;padding:10px; }
            .reserved { background-color:#E5D4AA;padding:10px; }
            select { width:250px }
            input { width:250px }

            #actions { float:right;margin-right:50px; }
            #actions input { width:100px }
        </style>
        <script>
            function select_banner_advertiser_sold(list)
            {
                var val = list.options[list.selectedIndex].value;
                if(val =='add')
                {
                    add_advertiser(list);
                }
            }
            function add_advertiser(list)
            {
                var advertiser = prompt('Navn','');
                if(!advertiser || advertiser == null ) 
                {
                    list.options[0].selected = true; 
                    return;
                }
                var n = list.options.length; 
                list.options[n]= new Option(advertiser,advertiser);
                list.options[n].selected = true;
            }
            function select_banner_advertiser_reserved(list)
            {
                var val = list.options[list.selectedIndex].value;
                if(val =='add')
                {
                    add_advertiser(list);
                }
            }
        </script>
    </head>
    <body bgcolor="#FFFFFF">
        <h1><?=$element_name?></h1>

        <form name="myform" method="post" action="term.php">
        <input type="hidden" name="save" value="1">
        <input type="hidden" name="banner_id" value="<?=$banner_id?>">
        <input type="hidden" name="element_id" value="<?=$element_id?>">

        <h2>Solgt</h2>
        <div class="sold">

            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td>
                       Annonc&oslash;r 
                    </td>
                    <td>
                       <select name="banner_advertiser_sold" onchange="select_banner_advertiser_sold(this)">
                        <option value="-1">---</option>

                        <?foreach( $advertisers as $aid => $advertiser):?>
                            <option 
                                <?=($banner['sold_to']==$aid)?'selected':''?>
                                value="<?=$aid?>"><?=$advertiser['name']?></option>
                        <?endforeach?>

                        <option value="add">+ Tilf&oslash;j Annonc&oslash;r</option>
                       </select>
                    </td>
                </tr>
                
                <tr>
                    <td>
                       S&aelig;lger
                    </td>
                    <td>
                       <select name="banner_salesman_sold" >

                        <?foreach( $users as $uid => $user):?>
                            <option 
                                <?
                                    if($banner['sold_by'])
                                    {
                                        echo ($uid==$banner['sold_by'])?'selected':'';
                                    }
                                    else
                                    {
                                        echo ($uid==$_SESSION['uid'])?'selected':'';
                                    }
                                ?>
                                value="<?=$uid?>"><?=$user['name']?></option>
                        <?endforeach?>
                       </select>
                    </td>
                </tr>

                <tr>
                    <td>
                       Start
                    </td>
                    <td>
                        <input readonly name="start_sold" type="text" 
                            class="calendardatepicker" value="<?=$banner['sold_period_start']?>" id="start_sold">
                        <img src="calendar/img.gif" id="start_sold_img" style="cursor: pointer;" title="Date selector" />
    
                        <script type="text/javascript">
                            Calendar.setup(
                                {
                                    inputField : "start_sold",
                                    ifFormat : "%Y-%m-%d",
                                    button         :    "start_sold_img",  // trigger for the calendar (button ID)
                                    singleClick    :    true
                                }
                            );
                        </script>

                    </td>
                </tr>

                <tr>
                    <td>
                       Slut
                    </td>
                    <td>
                        <input readonly name="end_sold" type="text" 
                            class="calendardatepicker" value="<?=$banner['sold_period_end']?>" id="end_sold">
                        <img src="calendar/img.gif" id="end_sold_img" style="cursor: pointer;" title="Date selector" />
    
                        <script type="text/javascript">
                            Calendar.setup(
                                {
                                    inputField : "end_sold",
                                    ifFormat : "%Y-%m-%d",
                                    button         :    "end_sold_img",  // trigger for the calendar (button ID)
                                    singleClick    :    true
                                }
                            );
                        </script>

                    </td>
                </tr>
            </table>

        </div>

        <h2>Reserveret</h2>
        <div class="reserved">

            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td>
                       Annonc&oslash;r 
                    </td>
                    <td>
                       <select name="banner_advertiser_reserved" onchange="select_banner_advertiser_reserved(this)">
                        <option value="-1">---</option>

                        <?foreach( $advertisers as $aid => $advertiser):?>
                            <option 
                                <?=($banner['reserved_to']==$aid)?'selected':''?>
                                value="<?=$aid?>"><?=$advertiser['name']?></option>
                        <?endforeach?>

                        <option value="add">+ Tilf&oslash;j Annonc&oslash;r</option>
                       </select>
                    </td>
                </tr>
                
                <tr>
                    <td>
                       S&aelig;lger
                    </td>
                    <td>
                       <select name="banner_salesman_reserved" >
                        <?foreach( $users as $uid => $user):?>
                            <option 
                                <?
                                    if($banner['reserved_by'])
                                    {
                                        echo ($uid==$banner['reserved_by'])?'selected':'';
                                    }
                                    else
                                    {
                                        echo ($uid==$_SESSION['uid'])?'selected':'';
                                    }
                                ?>
                                value="<?=$uid?>"><?=$user['name']?></option>
                        <?endforeach?>
                       </select>
                    </td>
                </tr>

                <tr>
                    <td>
                       Start
                    </td>
                    <td>
                        <input name="start_reserved" type="text" 
                            class="calendardatepicker" value="<?=$banner['reserved_period_start']?>" id="start_reserved">
                        <img src="calendar/img.gif" id="start_reserved_img" style="cursor: pointer;" title="Date selector" />
    
                        <script type="text/javascript">
                            Calendar.setup(
                                {
                                    inputField : "start_reserved",
                                    ifFormat : "%Y-%m-%d",
                                    button         :    "start_reserved_img",  // trigger for the calendar (button ID)
                                    singleClick    :    true
                                }
                            );
                        </script>
                    </td>
                </tr>

                <tr>
                    <td>
                       Slut
                    </td>
                    <td>
                        <input name="end_reserved" type="text" class="calendardatepicker" 
                            value="<?=$banner['reserved_period_end']?>" id="end_reserved">
                        <img src="calendar/img.gif" id="end_reserved_img" style="cursor: pointer;" title="Date selector" />
    
                        <script type="text/javascript">
                            Calendar.setup(
                                {
                                    inputField : "end_reserved",
                                    ifFormat : "%Y-%m-%d",
                                    button         :    "end_reserved_img",  // trigger for the calendar (button ID)
                                    singleClick    :    true
                                }
                            );
                        </script>
                    </td>
                </tr>
            </table>
        </div>

        <br><br>
        <div id="actions" >
        <input type="button" value="Fortryd" onclick="window.close()" >
        <input type="submit" value="Gemt" >
        </div>

        </form>

    </body>
</html>
