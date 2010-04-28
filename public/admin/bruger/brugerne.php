<?php
  require_once("../util/bruger.php");
  
  $bruger = new bruger( $dba );

  if( $_GET['add'] ) 
  {
    $bruger_id = $bruger->addBruger();
    die("<script>document.location.href='index_bruger.php?bruger_id=$bruger_id';</script>'");
  }
  
  if( is_numeric( $_GET['delete'] ) ) $bruger->deleteBruger( $_GET['delete'] );

  $_SESSION['offset'] =   is_numeric( $_GET['offset'] )? 
                          $_GET['offset']:
                          ( ( $_SESSION['offset'] )? $_SESSION['offset']:0 );

  $_SESSION['row_number'] =   $_GET['row_number']? 
                              $_GET['row_number']: 
                              ( $_SESSION['row_number']? $_SESSION['row_number']:10 );

  $_SESSION['sorting_order'] =   $_GET['sorting_order']? 
                                 $_GET['sorting_order']:
                                 ( $_SESSION['sorting_order']? $_SESSION['sorting_order']:'asc' );

  $_SESSION['sorting_colum'] =   $_GET['sorting_colum']? 
                                 $_GET['sorting_colum']:
                                 ( $_SESSION['sorting_colum']? $_SESSION['sorting_colum']:'id' );
  
  if(!$_GET['searching'] )
  {
    $brugerne = $bruger->getBrugerne( $_SESSION['offset'], 
                                      $_SESSION['row_number'], 
                                      $_SESSION['sorting_order'],
                                      $_SESSION['sorting_colum']  );
  }
  else
  {
    $brugerne = $bruger->findBrugerne($_GET['search']);
  }

?>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr>
    <td colspan="6"><img src="../graphics/transp.gif" height="20"></td>
  </tr>
  <tr>
    <form name="myform" action="<?=$_SERVER['PHP_SELF']?>" method="get">
      <input type="hidden" name="pane" value="<?=$pane?>">
    <td colspan="6" class="tabelText">
       
       
      <table width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
          <td class="tabelText">
            Antal records
            <? $rows = array( 10,20,50,75,100,200,300,400,500,700,1000 ) ?>
            <select name="row_number" onchange="document.myform.submit()" 
              style="font-size:11px">
              <?for( $i = 0; $i < count( $rows ); $i++ ):?>
                <option name="<?=$rows[$i]?>" <?=( $rows[$i] == $_SESSION['row_number'] )?'selected':''?>><?=$rows[$i]?></option>
              <?endfor?>
            </select>
          </td>
          <td align="right" class="tabelText">
            <input type="text" name="search" value="<?=$_GET['search']?>" 
              style="width:150px;font-size:11px">
            <input type="submit" class="knap" name="searching" value="Søg">
          </td>
        </tr>
      </table>
    </td>
    </form>
  </tr>
  <tr>
    <td colspan="6"><img src="../graphics/transp.gif" height="15"></td>
  </tr>
  <tr bgcolor="#e3e3e3">
    <? 
      $pil = '<img src="../graphics/';
      $pil.= ( $_SESSION['sorting_order'] == 'asc' )?'down':'up';
      $pil.= '_arrow.gif" width="10" height="10" border="0">';
    ?>
    <td>
      <?if(!$_GET['searching']):?>
        <a href="<?=$_SERVER['PHP_SELF']?>?sorting_colum=id&sorting_order=<?=( $_SESSION['sorting_order'] == 'asc' )?'desc':'asc'?>" class="tabelText">Id</a>
        <?=( $_SESSION['sorting_colum'] == 'id' )? $pil:'' ?>
      <?else:?>
        <span class="tabelText">Id</span>
      <?endif?>
    </td>
    <td>
      <?if(!$_GET['searching']):?>
        <a href="<?=$_SERVER['PHP_SELF']?>?sorting_colum=bruger_navn&sorting_order=<?=( $_SESSION['sorting_order'] == 'asc' )?'desc':'asc'?>" class="tabelText">Login</a>
        <?=( $_SESSION['sorting_colum'] == 'bruger_navn' )? $pil:'' ?>
      <?else:?>
        <span class="tabelText">Login</span>
      <?endif?>
    </td>
    <td>
      <?if(!$_GET['searching']):?>
        <a href="<?=$_SERVER['PHP_SELF']?>?sorting_colum=navn&sorting_order=<?=( $_SESSION['sorting_order'] == 'asc' )?'desc':'asc'?>" class="tabelText">Navn</a>
        <?=( $_SESSION['sorting_colum'] == 'navn' )? $pil:'' ?>
      <?else:?>
        <span class="tabelText">Navn</span>
      <?endif?>
    </td>
    <td>
      <?if(!$_GET['searching']):?>
        <a href="<?=$_SERVER['PHP_SELF']?>?sorting_colum=active&sorting_order=<?=( $_SESSION['sorting_order'] == 'asc' )?'desc':'asc'?>" class="tabelText">Active</a>
        <?=( $_SESSION['sorting_colum'] == 'active' )? $pil:'' ?>
      <?else:?>
        <span class="tabelText">Active</span>
      <?endif?>
    </td>
    <td>
      <?if(!$_GET['searching']):?>
        <a href="<?=$_SERVER['PHP_SELF']?>?sorting_colum=gratist&sorting_order=<?=( $_SESSION['sorting_order'] == 'asc' )?'desc':'asc'?>" class="tabelText">Gratist</a>
        <?=( $_SESSION['sorting_colum'] == 'gratist' )? $pil:'' ?>
      <?else:?>
        <span class="tabelText">Gratist</span>
      <?endif?>
    </td>
    <td>
      <?if(!$_GET['searching']):?>
        <a href="<?=$_SERVER['PHP_SELF']?>?sorting_colum=parent&sorting_order=<?=( $_SESSION['sorting_order'] == 'asc' )?'desc':'asc'?>" class="tabelText">Mester</a>
        <?=( $_SESSION['sorting_colum'] == 'parent' )? $pil:'' ?>
      <?else:?>
        <span class="tabelText">Mester</span>
      <?endif?>
    </td>
    <td>
      <?if(!$_GET['searching']):?>
        <a href="<?=$_SERVER['PHP_SELF']?>?sorting_colum=temaeditor&sorting_order=<?=( $_SESSION['sorting_order'] == 'asc' )?'desc':'asc'?>" class="tabelText">Temaredaktør</a>
        <?=( $_SESSION['sorting_colum'] == 'temaeditor' )? $pil:'' ?>
      <?else:?>
        <span class="tabelText">Temaredaktør</span>
      <?endif?>
    </td>
    <td>&nbsp;</td>
  </tr>
  <?for( $i = 0; $i< count( $brugerne ); $i++ ):?>
  <tr class="<?=($i%2==0)?"color1":"color2"?>" style="padding-top:3px;padding-bottom:3px;">
    <td>
      <a href="index_bruger.php?bruger_id=<?=$brugerne[$i]["id"]?>" class="tabelText"><?=$brugerne[$i]['id']?></a>
    </td>
    <td>
      <a href="index_bruger.php?bruger_id=<?=$brugerne[$i]["id"]?>" class="tabelText" style="padding-top:3px;padding-bottom:3px"><?=$brugerne[$i]['bruger_navn']?></a>
    </td>
    <td >
      <?$fullname = $brugerne[$i]['firma'] .' '. $brugerne[$i]['navn'] .' '. $brugerne[$i]['titel']?>
      <a href="index_bruger.php?bruger_id=<?=$brugerne[$i]["id"]?>" class="tabelText" title="<?=$fullname?>"><?=( strlen( $fullname ) > 20 )? substr( $fullname, 0, 20 ).'...': $fullname?></a>
    </td>
    <td class="tabelText">
      <img src="../graphics/<?=( $brugerne[$i]['active'] == 'y' )?'checked':'un_checked'?>.gif" width="9" height="9">
    </td>
    <td class="tabelText">
      <img src="../graphics/<?=( $brugerne[$i]['gratist'] == 'y' )?'checked':'un_checked'?>.gif" width="9" height="9">
    </td>
    <td class="tabelText">
      <img src="../graphics/<?=( $brugerne[$i]['parent'] )?'un_checked':'checked'?>.gif" width="9" height="9">
    </td>
    <td class="tabelText">
      <img src="../graphics/<?=( $brugerne[$i]['temaeditor'] == 'y' )?'checked':'un_checked'?>.gif" width="9" height="9">
    </td>
    <td align="right">
      <a href="<?=$_SERVER['PHP_SELF']?>?delete=<?=$brugerne[$i]["id"]?>" onclick="return confirm('Er du sikker på at du vil slette denne bruger. Denne handling kan ikke fortrydes!')" class="tabelText">[x]</a>
      <img src="../graphics/transp.gif" width="5">
    </td>
  </tr>
  <?endfor?>

  <?if(!$_GET['searching']):?>
  <tr>
    <td colspan="7" class="tabelText" align="center">
      <?
        $total_brugerne = $bruger->getTotal();
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
      <br><br>
      Total antal bruger:<?=$total_brugerne?>
    </td>
  </tr>
  <?endif?>
  <tr>
    <td colspan="7"><img src="../graphics/transp.gif" height="15"></td>
  </tr>
  <tr>
    <td class="header" align="right" colspan="7">					
      <input onclick="document.location.href='<?=$_SERVER['PHP_SELF']?>?pane=<?=$pane?>&add=1'" type="button" value="Tilføj bruger" class="knap" style="width:100px" />
    </td>
  </tr>
  <tr>
    <td colspan="7"><img src="../graphics/transp.gif" height="15"></td>
  </tr>
</table>
