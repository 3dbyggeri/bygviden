<?php
require_once('../admin/util/bruger.php');
require_once('../admin/util/dba.php');
require_once('../admin/util/tema.php');
require_once('../config.php');
session_start();        

$id = $_REQUEST['id'];
$dba = new dba();
$tema = new temaDoc($dba);
$tema->load($_REQUEST['tema_id']);

if(!$tema->isOwner())
{
    if(!$tema->isEditor() ) die('<script>top.GB_hide()</script>');
}

if($_POST['saving']=='1')
{
    $kilde_id = $tema->saveKilde($id,
                                 $_POST['tema_id'],
                                 $_POST['name'],
                                 $_POST['url'],
                                 $_POST['comment'],
                                 $_POST['kilde_type'],
                                 $_POST['bib_kilde_id']);

    $add = 'top.newListItem(\'kilder\','.$kilde_id.',escape(\''.$_POST['name'].'\'));';
    $edit ='top.updateListItem(\'kilder\',escape(\''.$_POST['name'].'\'));';

    $str = '<script>';
    $str.= ($id == '-1')? $add:$edit;
    $str.= 'top.GB_hide()</script>';
    die($str);
}


$props = $tema->kildeProperties($id);
?>
<html>
    <head>
        <title>Edit Kilde</title>
        <style>
            body { margin:20px;font-size:12px;font-family:arial,sans-serif; }

            .redbutton 
            { 
                background-color:#C8081D;
                padding:3px;
                color:#fff;text-decoration:none;
                padding-right:25px;
                font-size:10px;
                font-weight:900;
                background-image:url(../tema/graphics/redarrow.gif);
                background-repeat:no-repeat;
                background-position:right bottom;
            }

            .testlink 
            { 
               color:#C8081D;text-decoration:none;
               padding-right:12px;
               background-image:url(../tema/graphics/redarrowsmall.gif);
               background-repeat:no-repeat;
               background-position:right;
            }
            #bibliotek_kilde a { cursor:pointer;text-decoration:none;color:#333;display:block;margin-top:3px; }
            .category 
            {
                padding-left:20px;
            }

            #bibliotek_kilde a.lev { font-weight:900; }

            #bibliotek_kilde a.pub
            {
                padding-left:40px;
                color:#C8081D;text-decoration:none;
                background-image:url(../tema/graphics/redarrowsmall.gif);
                background-repeat:no-repeat;
                background-position:28px 0;
            }
        </style>
        <script>
            function testLink()
            {
                var url = document.getElementById('url').value;
                var w = window.open(url);
                w.focus();
            }
            function updateType(list)
            {
                var t = list.options[list.selectedIndex].value;
                var ex = document.getElementById('ekstern_kilde');
                var bib = document.getElementById('bibliotek_kilde');

                if(t=='extern')
                {
                    ex.style.display='';
                    bib.style.display='none';
                }
                else
                {
                    ex.style.display='none';
                    bib.style.display='';
                }
            }
            function togglechilds(link,id)
            {
                var el = document.getElementById('childrens_'+ id);
                el.style.display = (el.style.display=='none')?'':'none';
            }
            function selectkilde(bib_kilde_id,name)
            {
                document.myform.bib_kilde_id.value = bib_kilde_id;
                document.myform.name.value = name;
                document.myform.submit();
            }
        </script>
    </head>
    <body bgcolor="#FFFFFF" onload="document.getElementById('name').focus()">
        <form name="myform" method="post">
            <input type="hidden" name="saving" value="1">
            <input type="hidden" name="tema_id" value="<?=$_REQUEST['tema_id']?>">
            <input type="hidden" name="id" value="<?=$id?>">
            <input type="hidden" name="bib_kilde_id">
            
            <select name="kilde_type" onchange="updateType(this)" style="width:400px" id="kilde_type">
                <option value="extern">Ekstern kilde</option>
                <option value="bibliotek" <?=($props['is_bibliotek']=='y')?'selected':''?>>Kilde fra Bibliotek</option>
            </select>
            

            <div id="ekstern_kilde" style="margin-top:10px;<?=($props['is_bibliotek']=='y')?'display:none':''?>">
                Kilde navn<br>
                <input type="text" name="name" id="name" value="<?=$props['name']?>" style="width:400px" />
                <br>

                Kilde Adresse (url)<br>
                <input type="text" name="url" id="url" value="<?=$props['url']?>" style="width:400px" />
                <a href="javascript:testLink()" class="testlink">test</a>
                <br>
                
                Beskrivelse<br>
                <textarea name="comment" style="height:130px;width:400px"><?=$props['comment']?></textarea>
                <br><br> 

                <a href="javascript:document.myform.submit()" class="redbutton">Gemt</a>
            </div>

            <div id="bibliotek_kilde" style="margin-top:10px;<?=($props['is_bibliotek']=='y')?'':'display:none'?>">

            <?
            $sql ="SELECT 
                     publication.id AS pub_id,
                     publication.name AS pub_name,

                     kategory.id AS cat_id,
                     kategory.name AS cat_name,

                     videnlev.id AS lev_id,
                     videnlev.name AS lev_name
                   FROM 
                     ".$dba->getPrefix() ."kildestyring AS publication,
                     ".$dba->getPrefix() ."kildestyring AS kategory,
                     ".$dba->getPrefix() ."kildestyring AS videnlev 
                   WHERE
                     publication.parent = kategory.id
                   AND
                    videnlev.element_type = 'leverandor'
                   AND
                    publication.indholdsfortegnelse = 'y'
                   AND
                     kategory.parent = videnlev.id 
                   AND
                     ( publication.timepublish < NOW() OR publication.timepublish IS NULL )
                   AND
                     ( publication.timeunpublish > NOW() OR publication.timeunpublish IS NULL ) 
                   ORDER BY
                    lev_name,cat_name,pub_name";

            $result = $dba->exec( $sql );
            $n      = $dba->getN( $result );
            $leverandor = array();
            $open_lev = false;
            $open_cat = false;
            ?>
            <?for($i=0;$i<$n;$i++):?>
                <? 
                    $rec = $dba->fetchArray( $result );
                ?>
                <?if(!array_key_exists($rec['lev_id'],$leverandor)):?>
                    <?
                      if($open_lev) 
                      {
                        echo '</div>';
                        $open_lev = false;
                      }
                      if($open_cat) 
                      {
                        echo '</div>';
                        $open_cat= false;
                      }
                    ?>
                    <a class="lev" href="javascript:togglechilds(this,'<?=$rec['lev_id']?>')"><?=$rec['lev_name']?></a>
                    <?
                        $leverandor[$rec['lev_id']] = array(); 
                        $open_lev = true;
                    ?>
                    <div id="childrens_<?=$rec['lev_id']?>" style="display:none">
                <?endif?>

                <?if(!array_key_exists($rec['cat_id'],$leverandor[$rec['lev_id']])):?>
                    <?
                      if($open_cat) 
                      {
                        echo '</div>';
                        $open_cat= false;
                      }
                    ?>
                    <a href="javascript:togglechilds(this,'<?=$rec['cat_id']?>')" class="category"><?=$rec['cat_name']?></a>
                    <?
                        $leverandor[$rec['lev_id']][$rec['cat_id']] = array(); 
                        $open_cat = true;
                    ?>
                    <div id="childrens_<?=$rec['cat_id']?>" style="display:none">
                <?endif?>

                <a href="javascript:selectkilde('<?=$rec['pub_id']?>','<?=$rec['pub_name']?>')" class="pub"><?=$rec['pub_name']?></a>
                <?  $leverandor[$rec['lev_id']][$rec['cat_id']][] = $rec; ?>
            <?endfor?>
            <?
                if($open_cat) echo '</div>';
                if($open_lev) echo '</div>';
            ?>
            </div>

            <br />
        </form>
    </body>
</html>
