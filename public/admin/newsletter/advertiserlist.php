<?php
$_SESSION['offset'] = is_numeric( $_GET['offset'] )?  $_GET['offset']: ( ( $_SESSION['offset'] )? $_SESSION['offset']:0 );
$_SESSION['row_number'] = $_GET['row_number']?  $_GET['row_number']: ( $_SESSION['row_number']? $_SESSION['row_number']:10 );
$_SESSION['ad_sorting_order'] =   $_GET['ad_sorting_order']?  $_GET['ad_sorting_order']: ( $_SESSION['ad_sorting_order']? $_SESSION['ad_sorting_order']:'desc' );
$_SESSION['ad_sorting_column'] =   $_GET['ad_sorting_column']?  $_GET['ad_sorting_column']: ( $_SESSION['ad_sorting_column']? $_SESSION['ad_sorting_column']:'created' );
  
if(!$_GET['searching'] )
{
    $advertisers = $advertiser->all( $_SESSION['offset'], $_SESSION['row_number'], $_SESSION['ad_sorting_order'], $_SESSION['ad_sorting_column']  );
}
else
{
    if(!is_numeric($edit)) $advertisers = $advertiser->find($_GET['search']);
}
?>
<script>
function removing(id)
{
    if(!confirm("Er du Sikkert?")) return;
    document.location.href='<?=$_SERVER['PHP_SELF']?>?pane=<?=$pane?>&delete='+ id;
}
</script>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr>
    <td><img src="../graphics/transp.gif" height="30"></td>
  </tr>
  <tr>
    <td>					
      
      <table width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td width="50%" style="font-size:11px;padding-left:10px;">
                    Antal annonc&oslash;rer:
                    <?=$total['A']?> aktiv,
                    <?=$total['T']?> p&aring; pr&oslash;ve,
                    <?=$total['P']?> inaktiv
            </td>
            <td width="50%" align="right" style="padding-right:10px">
                  <form method="post" name="myform" action="index.php">
                      <input type="hidden" name="add" value="1">
                      <input type="hidden" name="pane" value="<?=$pane?>" />
                      <input type="submit" value="Tilf&oslash;j annonc&oslash;rer" class="knap" style="width:150px">
                  </form>
            </td>
        </tr>
      </table>

    </td>
  </tr>
  <tr>
    <td><img src="../graphics/transp.gif" height="15"></td>
  </tr>
  <tr>
    <td>

<table width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr bgcolor="#e3e3e3">
    <? 
      $pil = '<img src="../graphics/';
      $pil.= ( $_SESSION['ad_sorting_order'] == 'asc' )?'down':'up';
      $pil.= '_arrow.gif" width="10" height="10" border="0">';
    ?>
    <td>
      <?if(!$_GET['searching']):?>
        <a href="<?=$_SERVER['PHP_SELF']?>?pane=<?=$pane?>&ad_sorting_column=id&ad_sorting_order=<?=( $_SESSION['ad_sorting_order'] == 'asc' )?'desc':'asc'?>" 
            class="tabelText">Id</a>
        <?=( $_SESSION['ad_sorting_column'] == 'id' )? $pil:'' ?>
      <?else:?>
        <span class="tabelText">Id</span>
      <?endif?>
    </td>
    <td>
      <?if(!$_GET['searching']):?>
        <a href="<?=$_SERVER['PHP_SELF']?>?pane=<?=$pane?>&ad_sorting_column=company_name&ad_sorting_order=<?=($_SESSION['ad_sorting_order']=='asc')?'desc':'asc'?>" 
            class="tabelText">Firma Navn</a>
        <?=( $_SESSION['ad_sorting_column'] == 'company_name' )? $pil:'' ?>
      <?else:?>
        <span class="tabelText">Firma Navn</span>
      <?endif?>
    </td>
    <td>
      <?if(!$_GET['searching']):?>
        <a href="<?=$_SERVER['PHP_SELF']?>?pane=<?=$pane?>&ad_sorting_column=contact_name&ad_sorting_order=<?=($_SESSION['ad_sorting_order']=='asc')?'desc':'asc'?>" 
            class="tabelText">Kontakt Person</a>
        <?=( $_SESSION['ad_sorting_column'] == 'contact_name' )? $pil:'' ?>
      <?else:?>
        <span class="tabelText">Kontakt Person</span>
      <?endif?>
    </td>

    <td>
      <?if(!$_GET['searching']):?>
        <a href="<?=$_SERVER['PHP_SELF']?>?pane=<?=$pane?>&ad_sorting_column=contact_telefon&ad_sorting_order=<?=($_SESSION['ad_sorting_order']=='asc')?'desc':'asc'?>" 
            class="tabelText">Kontakt Person tlf.</a>
        <?=( $_SESSION['ad_sorting_column'] == 'contact_telefon' )? $pil:'' ?>
      <?else:?>
        <span class="tabelText">Kontakt Person tlf.</span>
      <?endif?>
    </td>
    <td>
      <?if(!$_GET['searching']):?>
        <a href="<?=$_SERVER['PHP_SELF']?>?pane=<?=$pane?>&ad_sorting_column=status&ad_sorting_order=<?=( $_SESSION['ad_sorting_order'] == 'asc' )?'desc':'asc'?>" class="tabelText">Status</a>
        <?=( $_SESSION['ad_sorting_column'] == 'status' )? $pil:'' ?>
      <?else:?>
        <span class="tabelText">Status</span>
      <?endif?>
    </td>
    <td>&nbsp;</td>
  </tr>
  <?for( $i = 0; $i< count( $advertisers); $i++ ):?>
  <tr class="<?=($i%2==0)?"color1":"color2"?>" style="padding-top:3px;padding-bottom:3px;">
    <td>
      <span class="tabelText"><?=$advertisers[$i]['id']?></span>
    </td>
    <td>
      <a href="?pane=<?=$pane?>&id=<?=$advertisers[$i]["id"]?>" 
        class="tabelText" style="padding-top:3px;padding-bottom:3px"><?=$advertisers[$i]['company_name']?></a>
    </td>
    <td>
      <a href="?pane=<?=$pane?>&id=<?=$advertisers[$i]["id"]?>" 
        class="tabelText" style="padding-top:3px;padding-bottom:3px"><?=$advertisers[$i]['contact_name']?></a>
    </td>
    <td>
      <a href="?pane=<?=$pane?>&id=<?=$advertisers[$i]["id"]?>" 
        class="tabelText" style="padding-top:3px;padding-bottom:3px"><?=$advertisers[$i]['contact_telefon']?></a>
    </td>
    <td>
      <a href="?pane=<?=$pane?>&id=<?=$advertisers[$i]["id"]?>" 
        class="tabelText" style="padding-top:3px;padding-bottom:3px"><?=$advertiser->status[$advertisers[$i]['status']]?> 
        <?if($advertisers[$i]['status']=='T'):?>(<?=$advertisers[$i]['triald']?>)<?endif?></a>
    </td>
    <td align="right">
      <a href="?pane=<?=$pane?>&id=<?=$advertisers[$i]["id"]?>" class="tabelText" title="Rediger">[Rediger]</a> 
      <a href="javascript:removing('<?=$advertisers[$i]["id"]?>')" class="tabelText" title="Slet">[x]</a>
      <img src="../graphics/transp.gif" width="5">
    </td>
  </tr>
  <?endfor?>
        </table>
    </td>
  </tr>


  <?if(!$_GET['searching']):?>
      <tr>
        <td class="tabelText" align="center">
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
    <td class="tabelText">
       
       
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
