<?php
include_once( "../util/dba.php" );
include_once( "../util/tree.php" );
include_once( "../util/brancheTreeNew.php" );
include_once( "../util/user.php" );

session_start();
$dba = new dba();
$user = new user( $dba );
if( !$user->isLogged() ) die("<script>top.document.location.href='log.php';</script>");

$root = 1;
$branche = '';
if($_GET['branche']) $branche = $_GET['branche'];
if($_POST['branche']) $branche =  $_POST['branche'];
if(!$branche) { $branche ='general'; }

$tree = new brancheTree2( $dba, session_id(), $branche );

//get parameters
if( !$id ) $id = $_POST["id"];
if( !$action ) $action = $_POST["action"];
if( !$newNodeName ) $newNodeName = $_POST["newNodeName"];
if( !$movingNode ) $movingNode = $_POST["movingNode"];
if( !$move ) $move = $_GET["move"];
if( !$where ) $where = $_GET["where"];
if( !$PHP_SELF ) $PHP_SELF = $_SERVER["PHP_SELF"];
if( !$selected_overview ) $selected_overview = $_POST["selected_overview"];

$tree->toggle( $_REQUEST['toggle'] );

$tree->addBygningsElement($_REQUEST['add'],$_REQUEST['element_id']);
$tree->editBygningsElement($_REQUEST['edit'],$_REQUEST['element_id']);

$tree->remove( $_REQUEST['remove'] );
$move   = $tree->move( $move, $where );
$brancher = $tree->getBrancher();
?>
<html>
	<head>
		<title>Site tree</title>
		<link href="../style/style.css" rel="stylesheet" rev="stylesheet" type="text/css"/>
		<script language="javascript" src="../scripts/global_funcs.js"></script>
        <style>
            .nodeName { color:#000; }
        </style>
        <?php
            $baseurl = sprintf('http%s://%s%s',
                          (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == TRUE ? 's': ''),
                          $_SERVER['HTTP_HOST'],
                          $_SERVER['REQUEST_URI']
                        );
            $u = explode("/",$baseurl);
            $f = array_pop($u);
            $ff = explode("?",$f);
            $script_name = $ff[0];
            $baseurl = '';
            for($i=0;$i<count($u);$i++) 
            {
                if($baseurl!='') $baseurl.='/';
                $baseurl.= $u[$i];
            }
            $baseurl = str_replace('/admin/brancher','',$baseurl);
        ?>
        <script>
            var GB_ROOT_DIR = '<?=$baseurl?>/tema/greybox/';
            var SERVER_NAME = '<?=$baseurl?>';
            var SCRIPT_NAME = '<?=$script_name?>';

            var current_node = 0;
            var current_action ='';
            var current_branche = '<?=$branche?>';
            
            function adding(node_id)
            {
               current_node = node_id; 
               current_action = 'add';
               var url = SERVER_NAME +'//admin/brancher/select_tree2.php'; 
               GB_show('Valgt bygningsdel',url,500,600);
            }

            function housing()
            {
               var url = SERVER_NAME +'/admin/brancher/housing.php?current_branche=<?=$branche?>'; 
               GB_show('Redigere Branche Huset',url,700,580);
            }

            function editing(node_id)
            {
               current_node = node_id; 
               current_action = 'edit';
               var url = SERVER_NAME +'//admin/brancher/select_tree2.php'; 
               GB_show('Valgt bygningsdel',url,500,600);
            }
            function nodeSelected(element_id)
            {
                GB_hide();
                var url ='';
                if(current_action =='add') var url ='tree.php?element_id='+ element_id +'&add='+ current_node +'&branche='+ current_branche;    
                if(current_action =='edit') var url ='tree.php?element_id='+ element_id +'&edit='+ current_node +'&branche='+ current_branche;    
                document.location.href=url;
            }
            function updateBranche(list)
            {
                document.tree.branche.value =list.options[list.selectedIndex].value;
                document.tree.submit();
            }

        </script>
        <script type="text/javascript" src="<?=$baseurl?>/tema/greybox/AJS.js"></script>
        <script type="text/javascript" src="<?=$baseurl?>/tema/greybox/AJS_fx.js"></script>
        <script type="text/javascript" src="<?=$baseurl?>/tema/greybox/gb_scripts.js"></script>
        <link href="<?=$baseurl?>/tema/greybox/gb_styles.css" rel="stylesheet" type="text/css" />
	</head>
  <body class="content_body" bgcolor="#FFFFFF">

		<form type="submit" name="tree" action="<?=$PHP_SELF?>" method="POST">
        <table cellpadding="0" cellspacing="0" border="0">
              <tr> <td><img src="../graphics/transp.gif" width="10" height="10" /></td> 
                    <td><img src="../graphics/horisontal_button/left_selected.gif"></td>
                    <td class="faneblad_selected">Brancher</td>
                    <td><img src="../graphics/horisontal_button/right_selected.gif"></td>
                    <td><img src="../graphics/transp.gif" width="4"></td>
              </tr>
        </table>
			<input type="hidden" name="toggle">
			<input type="hidden" name="remove">
			<input type="hidden" name="rename" value="<?=$rename?>">
			<input type="hidden" name="move" value="<?=$move?>">
            <input type="hidden" name="branche" value="<?=$branche?>">
			<?php
				$nodes =  $tree->getNodeArray();
				$n = count( $nodes );
			?>
        <table width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td width="1"> <img src="../graphics/transp.gif" border="0" width="1" height="350"> </td>
                <td class="tdborder_content" valign="top" style="border-right:4px solid #736e72">

                        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-top:20px;margin-bottom:20px">
                            <tr>
                                <td class="tabelText">
                                    Administration af
                                    <select name="branche_valg" onchange="updateBranche(this)" style="width:180px;margin-left:5px;margin-right:5px;">
                                      <?for( $i = 0; $i < count( $brancher ); $i++ ):?>
                                        <option value="<?=$brancher[$i]['name']?>" <?=( $brancher[$i]['name'] == $branche )?'selected':''?>><?=$brancher[$i]['label']?></option> 
                                      <?endfor?>
                                    </select>
                                </td>
                                <td align="right" style="padding-right:20px;">
                                    <a href="print.php?branche=<?=$branche?>" style="color:#90A0E0;font-size:10px;" 
                                        target="_blank">Udskriv tr&aelig;et<img align="absmiddle" border="0" src="print.gif" style="margin-bottom:3px;margin-left:5px"></a></td>
                            </tr>
                        </table>
                     
                      <div style="padding:10px;background-color:#e3e3e3">
                        <table width="98%" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td valign="top">

			<?for( $i = 0; $i < $n; $i++ ):?>
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td align="left" onmouseover="shownode( <?=$nodes[$i]["id"]?> )"  onmouseout="hidenode( <?=$nodes[$i]["id"]?> )">
						<table cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td width="<?=( $nodes[$i]["level"] ) * 10 ?>"><img src="graphics/space.gif" width="<?=( $nodes[$i]["level"] ) * 10 ?>" height="10" alt="space"\></td>

								<?//==================DISCLOSURE TRIANGLE CELL============================?>
								<td valign="top">
									<a href="javascript:toggling( <?=$nodes[$i]["id"]?> )" title="Toggle" onfocus="if(this.blur)this.blur();"><img src="graphics/<?=( $nodes[$i]["open"] )? "down": "up" ?><?=( $nodes[$i]["node"] )? "node":"leaf"?>.gif" alt="Toggle" border="0"\></a>
								</td>

								<td valign="top">
									<?
                                          $iconPostFix='';
                        
                                          if( $filter[ $nodes[$i]["id"] ]["state"] )
                                          {
                                            if( $selected_overview != 'docs' ) $iconPostFix.="_gray";
                                          }
                                          else
                                          {
                                            if( $selected_overview == 'docs' ) $iconPostFix.="_gray";
                                          }
                                    ?>
									
									<?if( $nodes[$i]["moving"] ):?>
										<a href="<?=$PHP_SELF?>" class="nodeName_gray" 
                                            onfocus="if(this.blur)this.blur();"><img src="graphics/doc_gray.gif" 
                                            border="0"/></a>
									<?elseif( $move ):?>
										<a href="<?=$PHP_SELF?>?branche=<?=$branche?>&move=<?=$move?>&where=<?=$nodes[$i]["id"]?>" 
                                            class="nodeName" onfocus="if(this.blur)this.blur();"><img src="graphics/doc.gif" 
                                            border="0"/></a>
									<?elseif( $nodes[$i]['id'] != 1 ):?>
										<a href="javascript:editing('<?=$nodes[$i]["id"]?>')" 
                                            title="Link til et bygningselement" class="nodeName" 
                                            onclick="parent.resize('normal');" 
                                            onfocus="if(this.blur)this.blur();"><img 
                                            src="graphics/doc<?=$iconPostFix?>.gif" border="0"/></a>
                  <?else:?>
                    <img src="graphics/doc<?=$iconPostFix?>.gif" alt="Link til et bygningselement" border="0"/>
									<?endif?>
								</td>
								<?//==================SPACER CELL============================?>
								<td><img src="graphics/space.gif" width="5" height="10" alt="space"\></td>
								<?//==================ITEM NAME CELL============================?>
								<td valign="top">
									<?if( $nodes[$i]["moving"] ):?>
										<a href="<?=$PHP_SELF?>" class="nodeName_gray" 
                                            onfocus="if(this.blur)this.blur();"><?=$nodes[$i]["name"] ?></a>
									<?elseif( $move ):?>
										<a href="<?=$PHP_SELF?>?branche=<?=$branche?>&move=<?=$move?>&where=<?=$nodes[$i]["id"]?>" 
                                            class="nodeName" onfocus="if(this.blur)this.blur();"><?=$nodes[$i]["name"] ?></a>
									<?else:?>
											<a href="javascript:editing('<?=$nodes[$i]['id']?>')" class="nodeName"><?=$nodes[$i]["name"] ?></span>
									<?endif?>
								</td>
							</tr>
						</table>
					</td>
					<?//==================ACTIONS TABLE CELL============================?>
					<td onmouseover="shownode( <?=$nodes[$i]["id"]?> )"  onmouseout="hidenode( <?=$nodes[$i]["id"]?> )">
						<table border="0" cellpadding="0" cellspacing="0" class="hide" id="<?=$nodes[$i]["id"]?>">
							<tr>
								<td>&nbsp;&nbsp;</td>
								<?if( !$move ):?>
                                    <td <?=( $nodes[$i]["id"] == $root )?"colspan=\"3\"":""?>>
                                            <a href="javascript:adding(<?=$nodes[$i]["id"]?>)" 
                                                title="Tilf&oslash;j"
                                                onmouseover="lightup('add', <?=$nodes[$i]["id"]?>)" 
                                                onmouseout="grayout('add', <?=$nodes[$i]["id"]?>)" 
                                                onfocus="if(this.blur)this.blur();"><img src="graphics/add_off.gif" 
                                                name="add<?=$nodes[$i]["id"]?>" border="0"></a>
                                    </td>
                                    <?if( $nodes[$i]["id"] != $root ):?>
                                        <td>
                                                <a href="javascript:removing( <?=$nodes[$i]["id"]?> )" 
                                                    title="Slet"
                                                    onmouseover="lightup('delete', <?=$nodes[$i]["id"]?>)" 
                                                    onmouseout="grayout('delete', <?=$nodes[$i]["id"]?>)" 
                                                    onfocus="if(this.blur)this.blur();"><img src="graphics/delete_off.gif" 
                                                    name="delete<?=$nodes[$i]["id"]?>" border="0" ></a>
                                        </td>
                                    <?endif?>
                                <?endif?>
								<td>&nbsp;&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<?endfor?>
                
                </td>
                <td valign="top" align="right">
                      <a href="javascript:housing()"><img src="../../tema/graphics/houses/<?=$branche?>.jpg" 
                            width="250"  border="0" hspace="20"></a>
                </td>
                </tr>
                </table>
                </div>

                <br /><br />
                </td>
       </tr>
      </table>

		</form>
	</body>
</html>
