<?php
  require_once('../util/products.php');
  require_once('../util/dba.php');
  $dba  = new dba();
  $producenter = new products( $dba );
  $producentList = $producenter->getProducenter( 0,0 );
?>
<html>
    <head>
        <title>Valgt producent</title>
        <link href="../style/style.css" rel="stylesheet" rev="stylesheet" type="text/css"/>
        <script language="javascript" src="../scripts/global_funcs.js"></script>
    </head>
    <body bgcolor="#FFFFFF" style="margin:0px;padding:0px"> 
    <table width="100%" cellpadding="4" cellspacing="0" border="0">
      <tr>
        <td bgcolor="#CC6600" align="right" valign="middle"><img src="graphics/space.gif" width="10" height="23"></td>
        <td bgcolor="#CC6600" align="right" valign="middle">
          <a href="javascript:window.close()" 
            style="font-family:verdana,sans-serif;text-decoration:none;font-size:12px;font-weight:900;color:#ffffff">[x]</a>

        </td>
     </tr>
    </table>
<script>
  function selecting( prod_id)
  {
    if(opener) opener.selectedProducent(prod_id);
    window.close();
  }
</script>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr><td><img src="../graphics/transp.gif" height="20"></td></tr> 
  <tr>
    <td class="header">Valgt producent</td>
	</tr>
  <tr><td><img src="../graphics/transp.gif" height="15"></td></tr>
  <tr> 
    <td>
        <table class="color1" width="100%" cellpadding="3" cellspacing="0" border="0">
          <!--producent liste-->
          <?if( !count( $producentList ) ):?>
            <tr class="color2">
              <td class="tdpadtext" align="center">Ingen producenter</td>
            </tr>
          <?endif?>
          <?for( $i = 0; $i < count( $producentList ); $i++ ):?>
            <?$color = ( $i % 2 == 0 )?'color2':'color3';?>
            <tr class="<?=$color?>">
              <td class="tdpadtext"><a class="tabelText" 
                href="javascript:selecting(<?=$producentList[$i]['id']?>)"><?=$producentList[$i]['name'] ?></a>
              </td>
            </tr>
          <?endfor?>
        </table>
    </td>
  </tr>
  <tr><td>&nbsp;</td></tr>
  </table>
  </body>
</html>
