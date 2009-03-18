<?php 
/*********************************************************************/
/*   nav.php                                                         */
/*                                                                   */
/*********************************************************************/
require_once("util/dba.php");
require_once("util/user.php");
session_start();
$user = new user( new dba() );
if( !$user->isLogged() ) die("<script language=\"JavaScript\">top.document.location.href='log.php'</script>");

$menu = array(
              array('name'=>'kildestyring','label'=>'Kildestyring','url'=>'kildestyring/index_root.php'),
              array('name'=>'bygningsdele','label'=>'Bygningsdele','url'=>'building/index_root.php'),
              array('name'=>'brancher2','label'=>'Brancher','url'=>'brancher/tree.php'),
              array('name'=>'bruger','label'=>'Bruger','url'=>'bruger/index.php'),
              array('name'=>'produkter','label'=>'Producenter','url'=>'products/index.php'),
              array('name'=>'newsletter','label'=>'Nyhedsbrev','url'=>'newsletter/index.php')
             );
         
?>
<html>
    <head>
        <title>navigation</title>
        <link rel="stylesheet" href="style/style.css" type="text/css">
        <style>
          #top_menu a { font-size:10px }
        </style>
    </head>
		<script language="javascript">
		
		var currentPane = 'home';
		function changePage(activePane) // TO usee call onclick="changePage('endusers')"
		{
			if (activePane != currentPane)
			{
                if ( activePane == 'kildestyring' ) top.treefrm.document.location.href ='kildetree/tree.php';
                if ( activePane == 'agenter' ) top.treefrm.document.location.href ='agenttree/tree.php';
                if ( activePane == 'home' ) top.treefrm.document.location.href ='about:blank';
                if ( activePane == 'forbrug' ) top.treefrm.document.location.href ='about:blank';
                if ( activePane == 'bruger' ) top.treefrm.document.location.href ='about:blank';
                if ( activePane == 'bygningsdele' ) top.treefrm.document.location.href ='buildingtree/tree.php';
                if ( activePane == 'brancher' ) top.treefrm.document.location.href ='branchetree/tree.php';
                if ( activePane == 'brancher2' ) top.treefrm.document.location.href ='about:blank';
                if ( activePane == 'produkter' ) top.treefrm.document.location.href ='products/tree.php';
        
				activeTD = activePane+'TD';
				lastTD = currentPane+'TD';
        
				document.getElementById(activeTD).style.background = "#FFFFFF";
				document.getElementById(lastTD).style.background = "#CC6600";
				document.getElementById(activePane).className = "top_nav_link_on";
				document.getElementById(currentPane).className = "top_nav_link";
				currentPane = activePane;
			}
		}
		
		</script>
    <body class="color3" style="margin-left:40px">
<table id="top_menu" height="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td nowrap id="homeTD" 
      style="padding-left:10px;padding-right:10px;background:#FFFFFF;#"><a 
      href="home/index.php" id="home" target="contentfrm" 
      class="top_nav_link_on" 
      onfocus="if(this.blur)this.blur();" onclick="changePage('home')">Hjem</a></td>
    <?for($i=0;$i<count($menu);$i++):?>
      <td nowrap id="<?=$menu[$i]['name']?>TD" 
        style="padding-left:10px;padding-right:10px;"><a 
        href="<?=$menu[$i]['url']?>" id="<?=$menu[$i]['name']?>" 
        target="contentfrm" class="top_nav_link" 
        onfocus="if(this.blur)this.blur();" 
        onclick="changePage('<?=$menu[$i]['name']?>')"><?=$menu[$i]['label']?></a></td>
    <?endfor?>
		<td nowrap id="fakeTD" 
      style="visibility:hidden;"><a href="#" id="fake">&nbsp;</a></td>
	</tr>
</table>
    </body>
</html>
