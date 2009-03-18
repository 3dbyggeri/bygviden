<?php
require_once('../admin/util/bruger.php');
require_once('../admin/util/dba.php');
require_once('../admin/util/tema.php');

include_once('../admin/util/tree.php');
include_once('../admin/util/page.php');
include_once('../admin/util/brancheTreeNew.php');

require_once('../config.php');
$brancher['tolerancer'] = 'Tolerancer';
session_start();        

$id = $_REQUEST['id'];
$tema = new temaDoc(new dba());
$tema->load($_REQUEST['tema_id']);

if(!$tema->isOwner())
{
    if(!$tema->isEditor() ) die('<script>top.GB_hide()</script>');
}

if($_POST['saving']=='1')
{
    $bygning_id = $tema->saveBuilding($id,$_POST['tema_id'],$_POST['node_id']);
    $add = 'top.newListItem(\'bygningsdel\','.$bygning_id.',escape(\''.$_POST['node_name'].'\'));';
    $edit ='top.updateListItem(\'bygningsdel\',escape(\''.$_POST['node_name'].'\'));';

    $str = '<script>';
    $str.= ($id == '-1')? $add:$edit;
    $str.= 'top.GB_hide()</script>';
    die($str);
}


function checkBrancheValg()
{
  if(!$_SESSION['branche'] && $_COOKIE['branche'] ) $_SESSION['branche'] = $_COOKIE['branche'];

  if($_GET['fag'])
  {
      $_SESSION['branche'] = $_GET['fag'];
      unset($_SESSION['element']);
      return;
  }
  if(!$_SESSION['branche']) $_SESSION['branche'] ='general';
}

function enforceSingleOpenLevel($tree)
{
    if(!$_REQUEST['level']) return;
    if(!$_REQUEST['toggle']) return;

    if(!is_array($_SESSION['openlevels'])) $_SESSION['openlevels'] = array();
    $levelID = $_SESSION['openlevels'][$_GET['level']];
    if(is_numeric($levelID) && $levelID != $_GET['toggle']) $tree->toggle($levelID);
    $_SESSION['openlevels'][$_GET['level']] = $_GET['toggle'];
}

checkBrancheValg();
$branche = ($_SESSION['branche'])? $_SESSION['branche']:'general';
//$tree = new brancheTree( new dba(),session_id(),$branche);

$tree = new brancheTree2( new dba(), session_id(), $branche );
if($_REQUEST['toggle']) $tree->toggle($_REQUEST['toggle']);
if($_REQUEST['element']==1 || !$_REQUEST['element']) $tree->clearState();
enforceSingleOpenLevel($tree);
$nodes =  $tree->getNodeArray();
?>
<html>
    <head>
        <title>Edit Page</title>
        <style>
            body { font-size:12px;font-family:arial,sans-serif; }
            #navitree
            {
                padding-left:15px;
                padding-top:10px;
                padding-bottom:10px;
                padding-right:10px;
                width:450px;
            }
            .node a { color:#000;text-decoration:none;font-size:12px; }
            .node a.chooser 
            { 
               display:inline;
               padding-left:10px; 
            }
            .node
            {
                font-size: 11px;
                line-height: 14px;
                padding-left:13px;
            }
            .nodeactive
            {
                font-size:11px;
                font-weight:bold;
                line-height: 14px;
                padding-left:13px;
            }
            .imgnode
            {
                padding-top:3px;
                line-height: 14px;
            }

            #fag_chooser 
            {
                font-size:11px;
                padding:3px;
                border-top:1px solid #fff;
                border-left:1px solid #fff;
                border-bottom:1px outset #D9D9D9;
                border-right:1px outset #D9D9D9;
                background-color:#e3e3e3;
                text-decoration:none;
                width:90%;
                display:block;
                font-weight:900;
                font-family:verdana,sans-serif;
                padding-top:4px;padding-bottom:4px;
                color:#333;
                padding-left:10px;
            }
            #fag_chooser:hover
            {
                border-bottom:1px outset #333;
                border-right:1px outset #333;
                background-color:#D9D9D9;
                color:#000;
            }
        </style>
        <script>
            function chooseBuilding(node_id,node_name)
            {
                document.myform.node_id.value = node_id;
                document.myform.node_name.value= node_name;
                document.myform.submit(); 
            }
        </script>
    </head>
    <body bgcolor="#FFFFFF">
        <form name="myform" method="post">
            <input type="hidden" name="saving" value="1" />
            <input type="hidden" name="tema_id" value="<?=$_REQUEST['tema_id']?>" />
            <input type="hidden" name="id" value="<?=$_REQUEST['id']?>" />
            <input type="hidden" name="node_id" />
            <input type="hidden" name="node_name" />
        </form>
        <?foreach($brancher as $key=>$value):?>
            <a id="fag_chooser" href="?fag=<?=$key?>&tema_id=<?=$_REQUEST['tema_id']?>&id=<?=$_REQUEST['id']?>"><?=$value?></a>
            <?if($key==$_SESSION['branche']):?>
                <div id="navitree">
                <?  $n = count( $nodes );?>
                <?for($i=0;$i< $n;$i++):?>
                    <?
                        $node = $nodes[$i];
                        if($node['id'] == 1) continue;

                        $link = '?element='.$node['element_id'];
                        $link.= '&branche='.$_SESSION['branche'];
                        $link.= '&node='.$node['id'];
                        $link.= ($node['open'])? '':'&level='.$node['level'];

                        if($node['node']) 
                        {
                            $link.='&toggle='.$node['id'];
                            $iconLink = '<a href="?toggle='.$node['id'];
                            $iconLink.='&element='.$node['element_id'];
                            $iconLink.= ($node['open'])? '':'&level='.$node['level'];
                            $iconLink.='&branche='.$_SESSION['branche'].'">';
                            $iconLinkEnd = '</a>';
                            $stateIcon = ($node['open'])? 'minus':'plus';
                            $stateLabel = ($node['open'])? 'Gem':'Vis';
                        }
                        else
                        {
                            $iconLink = '';
                            $iconLinkEnd = '';
                            $stateIcon = 'transp';
                            $stateLabel = '';
                        }
                    ?>
                    <div style="padding-left:<?=(($node['level'] - 1 ) * 10)?>px">
                        <div>
                            <a href="<?=$link?>&id=<?=$_REQUEST['id']?>&tema_id=<?=$_REQUEST['tema_id']?>" class="lk"><img src="../graphics/<?=$stateIcon?>.gif" class="imgnode" 
                               alt="<?=$stateLabel?>" height="9" width="9" border="0" align="left" /></a>
                        </div>
                        <div class="node">
                             <a href="<?=$link?>&id=<?=$_REQUEST['id']?>&tema_id=<?=$_REQUEST['tema_id']?>" style="display:inline"><?=$node['name']?></a>
                             <a href="javascript:chooseBuilding('<?=$node['id']?>','<?=$node['name']?>')" 
                                class="chooser" title="Valg"><img src="../tema/graphics/redarrowsmall.gif" border="0"></a>
                        </div>
                    </div>
                 <?endfor?>
                </div>
            <?endif?>
        <?endforeach?>
    </body>
</html>
