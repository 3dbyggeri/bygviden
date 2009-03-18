<?php
  require_once("../util/producenter.php");
  $producenter = new producenter( $dba );
  if( !$pane ) $pane = $_GET['pane'];
  if( !$pane ) $pane = $_POST['pane'];

  if( $_POST['add'] )
  {
    $id = $producenter->addProducent();
    require_once("producent.php");
    exit();
  }


  if( $id || $_GET['id'] || $_POST['id'] )
  {
    if( !$id ) $id = $_GET['id'];
    if( !$id ) $id = $_POST['id'];
    require_once("producent.php");
    exit();
  }

  $_SESSION['offset_producenter'] =   is_numeric( $_GET['offset_producenter'] )? 
                          $_GET['offset_producenter']:
                          ( ( $_SESSION['offset_producenter'] )? $_SESSION['offset_producenter']:0 );

  $_SESSION['antal'] = $_GET['antal']? 
                          $_GET['antal']: 
                          ( $_SESSION['antal']? $_SESSION['antal']:25 );

  $_SESSION['sort_producenter'] = $_GET['sort_producenter']? 
                                     $_GET['sort_producenter']:
                                     ( $_SESSION['sort_producenter']? $_SESSION['sort_producenter']:'asc' );

  if( $_GET['delete'] ) $producenter->removeProducent( $_GET['delete'] );
  $producentList = $producenter->getProducenter( $_SESSION['offset_producenter'],
                                                 $_SESSION['antal'],
                                                 $_SESSION['sort_producenter'] );
?>
<script>
  function deleting( producentId, name )
  {
    if( confirm('Er du sikker på at du vil slette producent '+ name ) )
    {
      document.location.href='<?=$_SERVER['PHP_SELF']?>?delete='+ producentId +'&pane=<?=$pane?>';
    }
  }
</script>
<form name="my_form" action="<?=$_SERVER['PHP_SELF']?>" method="post">
<input type="hidden" name="pane" value="<?=$pane?>">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr> 
    <td><img src="../graphics/transp.gif" height="20"></td>
  </tr> 
  <tr>
    <td class="header">Producent oversigt  <span class="alert_message"><?=$message?></span>
</td>
	</tr>
  <tr>
    <td align="center" style="padding-top:20px;font-size:10px">
      Antal records per side:
      <select name="antal" style="width:50px" 
        onchange="document.location.href='<?=$_SERVER['PHP_SELF']?>?pane=<?=$pane?>&antal='+ this.options[ this.selectedIndex ].value">
        <?for( $i = 1; $i < 15; $i++ ):?>
          <option value="<?=( $i * 25 )?>" <?=( $_SESSION['antal'] == ( $i * 25 ) )?'selected':''?> ><?=($i * 25 )?></option>
        <?endfor?>
      </select>
    </td>
	</tr>
  <tr> 
    <td><img src="../graphics/transp.gif" height="15"></td>
  </tr>
  <tr> 
    <td>
        <table class="color1" width="100%" cellpadding="3" cellspacing="0" border="0">
          <tr >
            <td class="tdpadtext">
              <a href="<?=$_SERVER['PHP_SELF']?>?pane=<?=$pane?>&sort_producenter=<?=( $_SESSION['sort_producenter'] == 'asc' )?'desc':'asc'?>" 
              class="tabelText">Navn</a>
              <? 
                $pil = '<img src="../graphics/';
                $pil.= ( $_SESSION['sort_producenter'] == 'asc' )?'down':'up';
                $pil.= '_arrow.gif" width="10" height="10" border="0">';
              ?>
              <?=$pil?>
            </td>
          </tr>
          <!--producent liste-->
          <?if( !count( $producentList ) ):?>
            <tr class="color2">
              <td class="tdpadtext" align="center">Ingen producenter</td>
            </tr>
          <?endif?>
          <?for( $i = 0; $i < count( $producentList ); $i++ ):?>
            <?$color = ( $i % 2 == 0 )?'color2':'color3';?>
            <tr class="<?=$color?>">
              <td class="tdpadtext"><a class="tabelText" href="<?=$_SERVER['PHP_SELF']?>?id=<?=$producentList[$i]['id']?>&pane=<?=$pane?>" class=""><?=$producentList[$i]['name'] ?></a></td>
              <td class="tdpadtext" align="right" style="padding-right:20px"><a class="tabelText" href="javascript:deleting(<?=$producentList[$i]['id']?>,'<?=$producentList[$i]['name'] ?>')" >Fjern</a></td>
            </tr>
          <?endfor?>
          <!--producent liste-->
           <tr>
                <td colspan="2" class="plainText" align="center">
              <?
                $total = $producenter->getTotal();

                // pager
                $antal_sider = $total / $_SESSION['antal'];
              ?>
              <?for( $i = 0; $i < $antal_sider; $i++ ):?>
                <?if(  $_SESSION['offset_producenter'] == ( $i * $_SESSION['antal'] ) ):?>
                  (<?=( $i + 1 )?>) |
                <?else:?>
                  <a href="<?=$_SERVER['PHP_SELF']?>?pane=<?=$pane?>&offset_producenter=<?=( $_SESSION['antal'] * $i )?>"><?=( $i + 1 )?></a> |
                <?endif?>
              <?endfor?>
                </td>
           </tr>
        </table>
    </td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
  </tr>
  <tr>
    <td >
		    <table cellpadding="0" cellspacing="0" border="0" width="325">
          <tr>
            <td class="tdpadtext">
              <?if( $referer ):?>
                <a href="<?=$referer?>"><img align="middle" src="../graphics/back_arrow.gif" border="0"></a>
              <?endif?>
            </td>
            <td  align="right">
              <input type="submit" value="Tilføj producent" name="add" class="knap" style="width:150px"> 
            </td>
           </tr>


				</table>
      </td>
    </tr>
    <tr> 
        <td ><img src="../graphics/transp.gif" height="15"></td>
    <tr>
  </table>
  </form>
