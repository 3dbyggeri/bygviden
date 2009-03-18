<?php
  require_once("../util/dba.php");
  require_once("../util/producenter.php");

  $producenter = new producenter( new dba() );
  $producentList = $producenter->getProducenter();

  if( $_GET['producent'] || $producent )
  {
    if( !$producent ) $producent = $_GET['producent'];
    $producenter->setProducent( $producent );
    $produkter = $producenter->getProducts();
  }
?>
<html>
  <head>
    <title>Vælg produkt </title>
        <link rel="stylesheet" href="../style/style.css" type="text/css">
        <script>
          function selectProduct(id)
          {
            opener.productSelected(id);
            window.close();
          }
        </script>
  </head>
  <body bgcolor="#FFFFFF">

<table class="color1" width="100%" cellpadding="3" cellspacing="0" border="0">
  <tr >
    <td class="tdpadtext">&nbsp;</td>
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
      <td class="tdpadtext"><a class="tabelText" href="<?=$_SERVER['PHP_SELF']?>?producent=<?=$producentList[$i]['id']?>" class=""><?=$producentList[$i]['name'] ?></a></td>
    </tr>
    <?if( $producentList[$i]['id'] == $producent ):?>
      <?for( $j=0;$j< count( $produkter ); $j++ ):?>
        <tr class="<?=$color?>">
          <td class="tdpadtext"><a href="#" onclick="selectProduct(<?=$produkter[$j]['id']?>)"><?=$produkter[$j]['name']?></a></td>
        </tr>
      <?endfor?>
    <?endif?>
  <?endfor?>
  <!--producent liste-->
  <tr >
    <td class="tdpadtext">&nbsp;</td>
  </tr>

  </body>
</html>
