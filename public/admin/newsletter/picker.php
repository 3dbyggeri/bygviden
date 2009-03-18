<?
require_once('../util/dba.php');
require_once('Newsletter.php');
$newsletter = new newsletter(new dba());

$n_id= ($_GET['n_id'])? $_GET['n_id']:$_POST['n_id'];
$p_id = ($_GET['p_id'])? $_GET['p_id']:$_POST['p_id'];
$column = ($_GET['column'])? $_GET['column']:$_POST['column'];
?>
<html>
    <head>
        <title>Content Picker</title>
        <style>
            body { margin:0;padding:0 }
            body, td { font-family:verdana,sans-serif;
                       font-size:11px;color:#333; }
            #top { text-align:right;padding:10px; }
            #top a { text-decoration:none;color:#000;font-size:10px; }

            #tabs { border-bottom:2px solid #999; }
            #tabs ul, li
            {
                list-style:none;
                display:inline;
                margin:0;
                padding:0;
            }
            #tabs a
            {
                padding-left:10px;
                padding-right:10px;
                padding-top:4px;
                padding-bottom:2px;
                background-color:#e3e3e3;
                text-decoration:none;
                color:#333;
                line-height:16px;
            }

            #tabs a:hover
            {
                background-color:#cdcdcd;
            }

            #tabs a.selected
            {
                background-color:#999;
                color:#fff;
            }
            #panel
            {
                padding:20px;
            }

        </style>
    </head>
    <body bgcolor="#FFFFFF">
        <div id="top">
            <a href="javascript:window.close()">Luk vindue [x]</a>
        </div>
            <?
                $ctx=($_GET['ctx'])? $_GET['ctx']:$_POST['ctx'];
                $x = array('paragraph'=>'Paragraf','news'=>'Nyheder','profiles'=>'Profiler');
                if(!$ctx) $ctx='paragraph';
            ?>
            <ul id="tabs">
                <?foreach($x as $k=>$v):?>
                    <li><a href="?n_id=<?=$n_id?>&p_id=<?=$p_id?>&ctx=<?=$k?>&column=<?=$column?>" 
                            class="<?=($k==$ctx)?'selected':''?>"><?=$v?></a></li>
                <?endforeach?>
            </ul>
            <div id="panel">
                <?require_once($ctx.'_picker.php')?>
            </div>

    </body>
</html>
