<?php
session_start();
include_once( "../util/dba.php" );
include_once( "../util/user.php" );
include_once( "../util/house.php" );

$dba = new dba();
$user = new user( $dba );
$housing = new house($dba);

if( !$user->isLogged() ) die("<script>top.document.location.href='log.php';</script>");
$current_branche = $_REQUEST['current_branche'];
$id = $_REQUEST['id'];

if($_REQUEST['save'])
{
    $housing->save($id,
                 $_REQUEST['x'],
                 $_REQUEST['y'],
                 $current_branche,
                 $_REQUEST['label'],
                 $_REQUEST['link'],
                 $_REQUEST['pointer']);
    $id='';
}
$housing->removing($_REQUEST['removing']);

$entry = array();
if(is_numeric($id) && $id > 0 ) $entry = $housing->props($id);
$entries = $housing->listing($current_branche);
?>
<html>
    <head>
        <title>Tree</title>
		<link href="../style/style.css" rel="stylesheet" rev="stylesheet" type="text/css"/>
        <script language="javascript" src="../scripts/global_funcs.js"></script>
        <style>
            #entries {font-size:10px;font-family:verdana,sans-serif;background-color:#999;}
            #entries input,select {font-size:10px;font-family:verdana,sans-serif;}
            #entries th {font-weight:100;background-color:#666;color:#fff;text-align:left;}
            #entries td { background-color:#fff; }


            #hus {position:relative;width:424px;height:451px;}
            #hus img { position:absolute;top:0;left:0; }
            #hus .marker {
                        background-color:#24446A;color:#fff;
                        width:110px;
                        position:absolute;
                        font-size:11px; 
                        padding:3px;
                        padding-top:5px;
                        padding-bottom:5px;
                        font-family:arial,sans-serif;
                        text-align:center;
                        border-right:1px solid #999;
                        border-bottom:1px solid #999;
                     }
            #hus .pointer {
                position:absolute;
            }
            #marker_edit { top:190px;left:190px;}
        </style>
        <script>
            var pointers = {};
            <?foreach($housing->arrows as $key=>$value):?>
            pointers[<?=$key?>] = {'x':<?=$value['x']?>,'y':<?=$value['y']?>,'img':'<?=$housing->arrowImg($key)?>'};
            <?endforeach?>
            function add()
            {
                document.getElementById("id").value = '-1'; 
                document.myform.submit();
            }
            function updatePosition()
            {
                var x = document.getElementById('x');
                var y = document.getElementById('y');
                if(isNaN(parseInt(x.value))) x.value = 190; 
                if(isNaN(parseInt(y.value))) y.value = 190; 

                var el = document.getElementById('marker_edit');
                el.style.left = x.value +'px';
                el.style.top = y.value +'px';
                updateOrientation(); 
            }
            function findPos(obj) 
            {
                var curleft = curtop = 0;
                if (obj.offsetParent) {
                    curleft = obj.offsetLeft
                    curtop = obj.offsetTop
                    while (obj = obj.offsetParent) {
                        curleft += obj.offsetLeft
                        curtop += obj.offsetTop
                    }
                }
                return [curleft,curtop];
            }
            function updateLabel(field)
            {
                var el = document.getElementById('marker_edit');
                el.innerHTML = field.value;
            }
            function saving()
            {
                document.myform.save.value='1';
                document.myform.submit();
            }
            function editing(id)
            {
                document.myform.id.value=id;
                document.myform.submit();
            }
            function removing(id)
            {
               document.myform.removing.value = id;
               document.myform.submit();
            }
            function updateOrientation()
            {
                var list = document.getElementById('pointer');
                var pointer_id =  parseInt(list.options[list.selectedIndex].value);

                var el = document.getElementById('pointer_edit');
                var img = document.getElementById('pointer_img');
                img.src = '../../graphics/transp.gif';
                
                if(pointer_id == 0 ) return;
                var d = pointers[pointer_id];
                if(!d) return;
                var src = '../../tema/graphics/arrows/'+ d['img']; 
                img.src = src;
                var x = parseInt(document.getElementById('x').value);
                var y = parseInt(document.getElementById('y').value);
                el.style.left = (x + d['x'] ) +'px';
                el.style.top = (y + d['y'] ) +'px';
            }
            function getPosition(e)
            {
                var posx = 0;
                var posy = 0;
                if (!e) var e = window.event;
                if (e.pageX || e.pageY) 	{
                    posx = e.pageX;
                    posy = e.pageY;
                }
                else if (e.clientX || e.clientY) 	{
                    posx = e.clientX + document.body.scrollLeft
                        + document.documentElement.scrollLeft;
                    posy = e.clientY + document.body.scrollTop
                        + document.documentElement.scrollTop;
                }
                var h = document.getElementById('hus');
                pos = findPos(h);
                
                var x = posx - pos[0];
                var y = posy - pos[1];
                if(!document.getElementById('x')) return;
                document.getElementById('x').value = x;
                document.getElementById('y').value = y;
                updatePosition();
            }
        </script>
    </head>
    <body bgcolor="#FFFFFF" style="margin:0;padding:0">
    <form method="post" name="myform">
        <input type="hidden" name="current_branche" value="<?=$current_branche?>" />
        <input type="hidden" id="id" name="id" value="<?=$id?>"/>
        <input type="hidden" name="save"/>
        <input type="hidden" name="removing"/>

        <div style="width:100%;height:470px" onclick="getPosition()">
        <center> 
            <div id="hus">
                <img src="../../tema/graphics/houses/<?=$current_branche?>.jpg" border="0">

                <?if(is_numeric($id)):?>
                    <? 
                        $style ='';
                        $pointer_style ='';
                        $label = 'Ny Link Boks';
                        $img = '../../graphics/transp.gif';

                        if($entry) 
                        {
                            $img = '../../tema/graphics/arrows/'.$housing->arrowImg($entry['pointer']); 
                            $pointer_style = 'style="top:'.($entry['y'] + $housing->arrows[$entry['pointer']]['y']).'px;';
                            $pointer_style.='left:'.($entry['x'] + $housing->arrows[$entry['pointer']]['x']).'px"';
                            $style= 'style="top:'.$entry['y'].'px;left:'.$entry['x'].'px"';
                            $label = $entry['label'];
                        }
                    ?>

                    <div class="pointer" id="pointer_edit" <?=$pointer_style?> ><img id="pointer_img" src="<?=$img?>" /></div>
                    <div id="marker_edit" class="marker" <?=$style?>><?=$label?></div>
                <?endif?>
                <?for($i=0;$i<count($entries);$i++):?>

                    <?if($id == $entries[$i]['id']) continue;?>
                    <?
                        $img = '../../graphics/transp.gif';
                        $p =$entries[$i]['pointer']; 
                        $delta_x = 0;
                        $delta_y = 0;
                        $left = $entries[$i]['x'];
                        $top = $entries[$i]['y'];

                        if($p > 0)
                        {
                            $img = '../../tema/graphics/arrows/'.$housing->arrowImg($p); 
                            $delta_x = $housing->arrows[$p]['x'];
                            $delta_y = $housing->arrows[$p]['y'];
                            $left = $left + $delta_x;
                            $top = $top + $delta_y;
                        }
                        $edit = 'onclick="javascript:editing('.$entries[$i]['id'].')"';
                        $non_edit_style='cursor:pointer;';
                        if(is_numeric($id))
                        {
                            $edit = '';
                            $non_edit_style = 'cursor:normal;background-color:#5B738F;';
                        }
                    ?>
                    <div class="pointer"
                        id="pointer_<?=$entries[$i]['id']?>" 
                        style="left:<?=$left?>px;top:<?=$top?>px"
                        ><img src="<?=$img?>" /></div>
                    <div id="marker_<?=$entries[$i]['id']?>" 
                        <?=$edit?> 
                        style="left:<?=$entries[$i]['x']?>px;top:<?=$entries[$i]['y']?>px;<?=$non_edit_style?>"
                        class="marker"><?=$entries[$i]['label']?></div>
                <?endfor?>
            </div>
        </center>
        </div>
    
        <div id="list"> 
                    <table id="entries" cellpadding="3" cellspacing="1" width="100%" border="0" style="margin-left:10px">
                        <tr>
                            <th> Retning </th>
                            <th> X </th>
                            <th> Y </th>
                            <th> Text </th>
                            <th> Link </th>
                            <th style="text-align:right"><a href="javascript:add()" title="Tilf&oslash;j"><img src="../../tema/graphics/plus.gif" border="0"></a></th>
                       </tr>
                       <?if(is_numeric($id)):?>
                           <tr>
                            <td>
                                <select name="pointer" id="pointer" onchange="updateOrientation()">
                                    <?for($i=0;$i<13;$i++):?>
                                        <?
                                            $sel = '';
                                            if($entry['pointer']==$i) $sel = 'selected';
                                        ?>
                                        <option value="<?=$i?>" <?=$sel?>><?=$i?></option>
                                    <?endfor?>
                                </select>
                            </td>
                            <?
                                $x =(is_numeric($entry['x']))? $entry['x']:'190';
                                $y =(is_numeric($entry['y']))? $entry['y']:'190';
                                $label =($entry['label'])? $entry['label']:'Ny Link Boks';
                                $link =($entry['link'])? $entry['link']:'';
                            ?>
                            <td><input type="text" name="x" id="x" size="4" value="<?=$x?>" onchange="updatePosition()" onkeypress="updatePosition()"></td>
                            <td><input type="text" name="y" id="y" size="4" value="<?=$y?>" onchange="updatePosition()" onkeypress="updatePosition()"></td>
                            <td><input type="text" style="width:180px" name="label" value="<?=$label?>" onkeypress="updateLabel(this)" onchange="updateLabel(this)"></td>
                            <td><input type="text" name="link" style="width:180px" value="<?=$link?>"></td>
                            <td><a href="javascript:saving()" title="Gemt"><img src="../../tema/graphics/icon_save.gif" border="0"></a></td>
                           </tr>
                       <?endif?>
                       <?for($i=0;$i<count($entries);$i++):?>
                            <?if($id == $entries[$i]['id']) continue;?>
                            <tr>
                                <td><?=$entries[$i]['pointer']?></td>
                                <td><?=$entries[$i]['x']?></td>
                                <td><?=$entries[$i]['y']?></td>
                                <td><?=stripslashes($entries[$i]['label'])?></td>
                                <td><?=stripslashes($entries[$i]['link'])?></td>
                                <td align="right" nowrap>
                                    <a href="javascript:editing(<?=$entries[$i]['id']?>)"><img src="../../tema/graphics/edit.png" border="0"></a>
                                    <a href="javascript:removing(<?=$entries[$i]['id']?>)"><img src="../graphics/delete.png" border="0"></a>
                                </td>
                            </tr>
                       <?endfor?>
                      </table>



        </div>
    </form>
    </body>
</html>
