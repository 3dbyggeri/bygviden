<?php

$_SESSION['prod_offset'] = is_numeric( $_GET['prod_offset'] )?  $_GET['prod_offset']: ( ( $_SESSION['prod_offset'] )? $_SESSION['prod_offset']:0 );
$_SESSION['prod_row_number'] = $_GET['prod_row_number']?  $_GET['prod_row_number']: ( $_SESSION['prod_row_number']? $_SESSION['prod_row_number']:25 );
$_SESSION['prod_sorting_order'] =   $_GET['prod_sorting_order']?  $_GET['prod_sorting_order']: ( $_SESSION['prod_sorting_order']? $_SESSION['prod_sorting_order']:'desc' );
$_SESSION['prod_sorting_column'] =   $_GET['prod_sorting_column']?  $_GET['prod_sorting_column']: ( $_SESSION['prod_sorting_column']? $_SESSION['prod_sorting_column']:'name' );

  if( $_GET['delete'] ) $producenter->removeProducent( $_GET['delete'] );
  $producentList = $producenter->getProducenter( $_SESSION['prod_offset'], $_SESSION['prod_row_number'], $_SESSION['prod_sorting_order'],$_SESSION['prod_sorting_column'] );
?>
<script>
  function deleting( producentId, name )
  {
    if(!confirm('Er du sikker?') ) return;
    document.location.href='<?=$_SERVER['PHP_SELF']?>?delete='+ producentId +'&pane=<?=$pane?>';
  }
</script>
<form name="my_form" action="<?=$_SERVER['PHP_SELF']?>" method="post">
<input type="hidden" name="pane" value="<?=$pane?>">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr><td><img src="../graphics/transp.gif" height="20"></td></tr> 
  <tr>
    <td class="header">
      <span class="alert_message"><?=$message?></span>
    </td>
	</tr>
  <tr>
    <td>

        <table width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td style="padding-left:10px;font-size:10px">
                      Antal per side:
                      <select name="prod_row_number" style="width:50px" 
                        onchange="document.location.href='<?=$_SERVER['PHP_SELF']?>?pane=<?=$pane?>&prod_row_number='+ this.options[ this.selectedIndex ].value">
                        <?for( $i = 1; $i < 15; $i++ ):?>
                          <option value="<?=( $i * 25 )?>" <?=( $_SESSION['prod_row_number'] == ( $i * 25 ) )?'selected':''?> ><?=($i * 25 )?></option>
                        <?endfor?>
                      </select>
                    
                </td>
                <td align="right" style="padding-right:10px">
                    <input type="text" name="search" value="<?=$_GET['search']?>" 
                      style="width:150px;font-size:11px">
                    <input type="submit" class="knap" name="searching" value="Find">
                </td>
           </tr>
        </table>
    </td>
	</tr>
  <tr><td><img src="../graphics/transp.gif" height="15"></td></tr>
  <tr> 
    <td>

      <? 
        $pil = '<img src="../graphics/';
        $pil.= ( $_SESSION['prod_sorting_order'] == 'asc' )?'down':'up';
        $pil.= '_arrow.gif" width="10" height="10" border="0">';
      ?>
        <table class="color1" width="100%" cellpadding="3" cellspacing="0" border="0">
          <tr style="background-color:#e3e3e3">
            <td class="tdpadtext" >
              <a href="<?=$_SERVER['PHP_SELF']?>?pane=<?=$pane?>&prod_sorting_column=name&prod_sorting_order=<?=( $_SESSION['prod_sorting_order'] == 'asc' )?'desc':'asc'?>" 
              class="tabelText">Navn</a>
              <?=($_SESSION['prod_sorting_column'] == 'name')? $pil:''?>
            </td>
            <td class="tdpadtext" >
              <a href="<?=$_SERVER['PHP_SELF']?>?pane=<?=$pane?>&prod_sorting_column=admin_name&prod_sorting_order=<?=( $_SESSION['prod_sorting_order'] == 'asc' )?'desc':'asc'?>" 
              class="tabelText">Kontakt Person</a>
              <?=($_SESSION['prod_sorting_column'] == 'admin_name')? $pil:''?>
            </td>

            <td class="tdpadtext" >
              <a href="<?=$_SERVER['PHP_SELF']?>?pane=<?=$pane?>&prod_sorting_column=advertise_deal&prod_sorting_order=<?=( $_SESSION['prod_sorting_order'] == 'asc' )?'desc':'asc'?>" 
              class="tabelText">Annonc&oslash;rer aftale</a>
              <?=($_SESSION['prod_sorting_column'] == 'advertise_deal')? $pil:''?>
            </td>
            <td>&nbsp;</td>
          </tr>
          <!--producent liste-->
          <?if( !count( $producentList ) ):?>
            <tr class="color2">
              <td class="tdpadtext" align="center">Ingen producenter</td>
            </tr>
          <?endif?>
          <?for( $i = 0; $i < count( $producentList ); $i++ ):?>
            <?$color = ( $i % 2 == 0 )?'color1':'color2';?>
            <tr class="<?=$color?>">
              <td class="tdpadtext"><a class="tabelText" 
                href="<?=$_SERVER['PHP_SELF']?>?pcr_id=<?=$producentList[$i]['id']?>&pane=producer" 
                class=""><?=$producentList[$i]['name'] ?></a>
              </td>
              <td class="tdpadtext"><a class="tabelText" 
                href="<?=$_SERVER['PHP_SELF']?>?pcr_id=<?=$producentList[$i]['id']?>&pane=producer" 
                class=""><?=$producentList[$i]['admin_name'] ?><?if($producentList[$i]['admin_telefon']):?> [<?=$producentList[$i]['admin_telefon']?>]<?endif?> 
                </a>
              </td>
              <td class="tdpadtext"><a class="tabelText" 
                href="<?=$_SERVER['PHP_SELF']?>?pcr_id=<?=$producentList[$i]['id']?>&pane=producer" 
                class="">
                    <?
                        $label = 'Ingen';
                        if($producentList[$i]['advertise_deal']=='active') 
                        {
                            $label='Forhandlet '.$producentList[$i]['advertise_signup'];
                        }
                        if($producentList[$i]['advertise_deal']=='trial') 
                        {
                            $label='Pr&oslash;vetid ('.$producentList[$i]['signup'].' dag)';
                        }

                    ?>
                    <?=$label?>
                </a>
              </td>
              <td class="tdpadtext" align="right" 
                style="padding-right:20px">

                    <a class="tabelText" title="Redigere"
                        href="<?=$_SERVER['PHP_SELF']?>?pcr_id=<?=$producentList[$i]['id']?>&pane=producer"><img src="../../tema/graphics/edit.png" border="0" /></a>
                    <a class="tabelText" title="Slet"
                        href="javascript:deleting(<?=$producentList[$i]['id']?>,'<?=$producentList[$i]['name'] ?>')" ><img src="../graphics/delete.png" border="0" /></a></td>
            </tr>
          <?endfor?>
          <!--producent liste-->
           <tr>
                <td colspan="2" class="plainText" align="center">
              <?
                $total = $producenter->getTotal();
                $antal_sider = $total / $_SESSION['prod_row_number'];
              ?>
              <?for( $i = 0; $i < $antal_sider; $i++ ):?>
                <?if(  $_SESSION['prod_offset'] == ( $i * $_SESSION['prod_row_number'] ) ):?>
                  (<?=( $i + 1 )?>) |
                <?else:?>
                  <a href="<?=$_SERVER['PHP_SELF']?>?pane=<?=$pane?>&prod_offset=<?=( $_SESSION['prod_row_number'] * $i )?>"><?=( $i + 1 )?></a> |
                <?endif?>
              <?endfor?>
                </td>
           </tr>
        </table>
    </td>
  </tr>
  <tr><td>&nbsp;</td></tr>
  <tr>
    <td>
		    <table cellpadding="0" cellspacing="0" border="0" width="325">
          <tr>
            <td class="tdpadtext">&nbsp;</td>
            <td  align="right">
              <input type="submit" value="Oprett producent" name="add" class="knap" style="width:150px"> 
            </td>
           </tr>
				</table>
      </td>
    </tr>
    <tr><td><img src="../graphics/transp.gif" height="15"></td><tr>
  </table>
  </form>
