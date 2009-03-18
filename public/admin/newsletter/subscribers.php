<?php
require_once("Subscriber.php");
require_once("../../chimp/inc/MCAPI.class.php");
$subscriber = new subscriber( $dba );

if( $_POST['add'] ) 
{
    $id = $subscriber->add($_POST['add']);
	$api = new MCAPI('rim@danskbyggeri.dk','danskbyggeri');

	// Fetch mailing list id
	$lists = $api->lists();
	$list_id = $lists[0]['id'];

    for($i = 0; $i < count($lists);$i++)
    {
        if($lists[$i]['name'] == 'Bygviden Nyhedsbrev') $list_id = $lists[$i]['id'];
    }

	if($api->listSubscribe($list_id, $_POST['add'], '') === true) {
		// It worked!	
		$msg ='Brugeren er blevet tilmeld';
	}else{
		// An error ocurred, return error message	
		$msg ='Teknisk fejl: ' . $api->errorMessage;
	}
}
if( is_numeric($_GET['edit']))
{
    $subscriber->update($_GET['edit'],$_GET['email']);
}
if( is_numeric( $_GET['delete'] ) ) 
{
    $subscriber->remove( $_GET['delete'] );

	$api = new MCAPI('rim@danskbyggeri.dk','danskbyggeri');

	// Fetch mailing list id
	$lists = $api->lists();
	$list_id = $lists[0]['id'];

    for($i = 0; $i < count($lists);$i++)
    {
        if($lists[$i]['name'] == 'Bygviden Nyhedsbrev') $list_id = $lists[$i]['id'];
    }

	if($api->listUnSubscribe($list_id, $_GET['delete'],true) === true) {
		// It worked!	
		$msg ='Brugeren er blevet sletet';
	}else{
		// An error ocurred, return error message	
		$msg ='Teknisk fejl: ' . $api->errorMessage;
	}
}

if( $_GET['state'] && is_numeric( $_GET['id'] ) ) 
{
    $subscriber->setActive( $_GET['id'],$_GET['state'] );

    if($_GET['state'] =='n')
    {
	    $api = new MCAPI('rim@danskbyggeri.dk','danskbyggeri');
        // Fetch mailing list id
        $lists = $api->lists();
        $list_id = $lists[0]['id'];

        for($i = 0; $i < count($lists);$i++)
        {
            if($lists[$i]['name'] == 'Bygviden Nyhedsbrev') $list_id = $lists[$i]['id'];
        }

        if($api->listUnSubscribe($list_id, $_GET['delete'],false,false,false) === true) {
            // It worked!	
            $msg ='Brugeren er blevet deaktiveret';
        }else{
            // An error ocurred, return error message	
            $msg ='Teknisk fejl: ' . $api->errorMessage;
        }
    }
}

$_SESSION['offset'] = is_numeric( $_GET['offset'] )?  $_GET['offset']: ( ( $_SESSION['offset'] )? $_SESSION['offset']:0 );
$_SESSION['row_number'] = $_GET['row_number']?  $_GET['row_number']: ( $_SESSION['row_number']? $_SESSION['row_number']:10 );
$_SESSION['sub_sorting_order'] =   $_GET['sub_sorting_order']?  $_GET['sub_sorting_order']: ( $_SESSION['sub_sorting_order']? $_SESSION['sub_sorting_order']:'desc' );
$_SESSION['sub_sorting_column'] =   $_GET['sub_sorting_column']?  $_GET['sub_sorting_column']: ( $_SESSION['sub_sorting_column']? $_SESSION['sub_sorting_column']:'subscribed' );
  
if(!$_GET['searching'] )
{
    $subscribers = $subscriber->all( $_SESSION['offset'], $_SESSION['row_number'], $_SESSION['sub_sorting_order'], $_SESSION['sub_sorting_column']  );
}
else
{
    $subscribers = $subscriber->find($_GET['search']);
}

$total_brugerne = $subscriber->total();
?>
<script>
    function editing(id,mail)
    {
       var mail = prompt('Rediger abonnent email',mail); 
       document.location.href='?pane=<?=$pane?>&edit='+ id +'&email='+ mail;
    }
</script>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr>
    <td colspan="5"><img src="../graphics/transp.gif" height="30"></td>
  </tr>
  <tr>
    <td colspan="5">					
      
      <table width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td width="50%" style="font-size:11px;padding-left:10px;">
                  <?if(!$_GET['searching']):?>
                    Total antal aktiv abonnenter:<?=$total_brugerne?>
                  <?endif?>
                
            </td>
            <td width="50%" align="right" style="padding-right:10px">
                  <form method="post" name="myform" action="index.php">
                      <input type="text" name="add" id="add">
                      <input type="hidden" name="pane" value="<?=$pane?>" />
                      <input type="submit" value="Tilf&oslash;j abonnent" class="knap" style="width:150px">
                  </form>
            </td>
        </tr>
      </table>

    </td>
  </tr>
  <tr>
    <td colspan="6"><img src="../graphics/transp.gif" height="15"></td>
  </tr>
  <tr bgcolor="#e3e3e3">
    <? 
      $pil = '<img src="../graphics/';
      $pil.= ( $_SESSION['sub_sorting_order'] == 'asc' )?'down':'up';
      $pil.= '_arrow.gif" width="10" height="10" border="0">';
    ?>
    <td>
      <?if(!$_GET['searching']):?>
        <a href="<?=$_SERVER['PHP_SELF']?>?pane=<?=$pane?>&sub_sorting_column=id&sub_sorting_order=<?=( $_SESSION['sub_sorting_order'] == 'asc' )?'desc':'asc'?>" class="tabelText">Id</a>
        <?=( $_SESSION['sub_sorting_column'] == 'id' )? $pil:'' ?>
      <?else:?>
        <span class="tabelText">Id</span>
      <?endif?>
    </td>
    <td>
      <?if(!$_GET['searching']):?>
        <a href="<?=$_SERVER['PHP_SELF']?>?pane=<?=$pane?>&sub_sorting_column=email&sub_sorting_order=<?=( $_SESSION['sub_sorting_order'] == 'asc' )?'desc':'asc'?>" 
            class="tabelText">Email</a>
        <?=( $_SESSION['sub_sorting_column'] == 'email' )? $pil:'' ?>
      <?else:?>
        <span class="tabelText">Email</span>
      <?endif?>
    </td>
    <td>
      <?if(!$_GET['searching']):?>
        <a href="<?=$_SERVER['PHP_SELF']?>?pane=<?=$pane?>&sub_sorting_column=active&sub_sorting_order=<?=( $_SESSION['sub_sorting_order'] == 'asc' )?'desc':'asc'?>" class="tabelText">Aktiv</a>
        <?=( $_SESSION['sub_sorting_column'] == 'active' )? $pil:'' ?>
      <?else:?>
        <span class="tabelText">Aktiv</span>
      <?endif?>
    </td>
    <td>
      <?if(!$_GET['searching']):?>
        <a href="<?=$_SERVER['PHP_SELF']?>?pane=<?=$pane?>&sub_sorting_column=subscribed&sub_sorting_order=<?=( $_SESSION['sub_sorting_order'] == 'asc' )?'desc':'asc'?>" class="tabelText">Tilmeld</a>
        <?=( $_SESSION['sub_sorting_column'] == 'subscribed' )? $pil:'' ?>
      <?else:?>
        <span class="tabelText">Tilmeld</span>
      <?endif?>
    </td>
    <td>&nbsp;</td>
  </tr>
  <?
    $csv = '';
  ?>
  <?for( $i = 0; $i< count( $subscribers); $i++ ):?>
  <tr class="<?=($i%2==0)?"color1":"color2"?>" style="padding-top:3px;padding-bottom:3px;">
    <td>
      <span class="tabelText"><?=$subscribers[$i]['id']?></span>
    </td>
    <td>
      <!--<a href="javascript:editing(<?=$subscribers[$i]["id"]?>,'<?=$subscribers[$i]['email']?>')" title="Rediger Email" 
        class="tabelText" style="padding-top:3px;padding-bottom:3px"><?=$subscribers[$i]['email']?></a>-->
    <?
        $csv.= $subscribers[$i]['email']."\n";
    ?>

      <a href="mailto:<?=$subscribers[$i]['email']?>" class="tabelText" style="padding-top:3px;padding-bottom:3px"><?=$subscribers[$i]['email']?></a>
    </td>
    <td class="tabelText">
      <a href="<?=$_SERVER['PHP_SELF']?>?pane=<?=$pane?>&id=<?=$subscribers[$i]["id"]?>&state=<?=($subscribers[$i]['active'] == 'y')?'n':'y'?>"
         title="<?=($subscribers[$i]['active'] == 'y')?'Deaktivere':'Aktivere'?>"><img 
        src="../graphics/<?=( $subscribers[$i]['active'] == 'y' )?'checked':'un_checked'?>.gif" border="0" width="9" height="9"></a>
    </td>
    <td class="tabelText">
      <a href="?pane=<?=$pane?>&edit=<?=$subscribers[$i]["subscribed"]?>" class="tabelText" style="padding-top:3px;padding-bottom:3px"><?=$subscribers[$i]['subscribed']?></a>
    </td>
    <td align="right">
      <a href="<?=$_SERVER['PHP_SELF']?>?pane=<?=$pane?>&delete=<?=$subscribers[$i]["id"]?>" class="tabelText" title="Slet">[x]</a>
      <img src="../graphics/transp.gif" width="5">
    </td>
  </tr>
  <?endfor?>

  <?if(!$_GET['searching']):?>
  <tr>
    <td colspan="6" class="tabelText" align="center">
      <?
        // find number of pages
        $antal_sider = floor($total_brugerne / $_SESSION['row_number']);

        // show 20 pages at a time
        $MAX_PAGE_NUMBER = 16;

        // current record offset
        $current_record = $_SESSION['offset']; 

        $start = 0;
        $c = 0;
        
        $c_off = $_SESSION['offset'] * $_SESSION['row_number']; 
        if($_SESSION['offset'] > $_SESSION['row_number'])
        {
          $page_number = floor($_SESSION['offset'] / $_SESSION['row_number'] ) + 1;
          $start = $page_number - ($MAX_PAGE_NUMBER - ($MAX_PAGE_NUMBER / 2));
          if($start <= 0 ) $start= 0;
        }
      ?>
      <br>
      <?for( $i = $start; $i < $antal_sider; $i++ ):?>
        <?$c++?>
        <?if(  $_SESSION['offset'] == ( $i * $_SESSION['row_number'] ) ):?>
          (<?=( $i + 1 )?>) |
        <?else:?>
          <a href="<?=$_SERVER['PHP_SELF']?>?pane=<?=$pane?>&offset=<?=( $_SESSION['row_number'] * $i )?>"><?=( $i + 1 )?></a> |
        <?endif?>
        <?if($c>=$MAX_PAGE_NUMBER) break?>
      <?endfor?>

    </td>
  </tr>
  <?endif?>

  <tr>
    <form name="myform" action="<?=$_SERVER['PHP_SELF']?>" method="get">
      <input type="hidden" name="pane" value="<?=$pane?>">
    <td colspan="5" class="tabelText">
       
       
      <table width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
          <td class="tabelText">
            Antal per side: 
            <? $rows = array( 10,20,50,75,100,200,300,400,500,700,1000 ) ?>
            <select name="row_number" onchange="document.myform.submit()" 
              style="font-size:11px">
              <?for( $i = 0; $i < count( $rows ); $i++ ):?>
                <option name="<?=$rows[$i]?>" <?=( $rows[$i] == $_SESSION['row_number'] )?'selected':''?>><?=$rows[$i]?></option>
              <?endfor?>
            </select>
          </td>
          <td align="right" class="tabelText" style="padding-right:10px">
            <input type="text" name="search" value="<?=$_GET['search']?>" 
              style="width:150px;font-size:11px">
            <input type="submit" class="knap" name="searching" value="S&oslash;g">
          </td>
        </tr>
      </table>
    </td>
    </form>
  </tr>
</table>
<textarea style="width:400px;height:200px">
    <?=$csv?>
</textarea>
