<?php
require_once('Newsletter.php');
$newsletter = new newsletter($dba);

$n_id = ($_GET['n_id'])? $_GET['n_id']:$_POST['n_id'];

if($_GET['rem']) $newsletter->remove($_GET['rem']);
if($n_id =='-1') $n_id = $newsletter->add();

$news = $newsletter->all();
?>
<style>
    .color1 {font-size:11px;font-weight:100; }
    #list td {font-size:11px;font-weight:100; }
    #list td a { text-decoration:none;color:#333;font-size:10px; }
    #layout {background-color:#fff; }
    #layout td {font-size:11px;background-color:#e3e3e3; }
    .tools { text-align:right;padding:3px}
    .para { background-color:#fff;padding:5px; }
    #layout .para td { background-color:#fff; }
    #msg { color:#666;float:right;margin-right:20px;padding:3px;background-color:#ff9900;font-size:11px; }
    .para a { color:#666;text-decoration:none;border-bottom:1px dashed #999;}
</style>
<script src="MochiKit.js"></script>
<script>
    var current_news_id = '<?=$n_id?>';
    var position = {'1':[],'2':[]};
    function cleanlabel(el)
    {
      el.value = '';
      el.style.color='#000';
    }
    function setlabel(el,labeltext)
    {
      if(el.value !='') return;
      el.value = labeltext;
      el.style.color='#999';
    }
    function preview()
    {
      var url = 'newscontroller.php?id='+ current_news_id +'&preview=1'; 
      var w = window.open(url);
      w.focus();
    }
    function testingmail()
    {
      var m = document.getElementById('testmail');
      if(m.value =='' || m.value == 'Test email')
      {
        alert('Du skal angive en email');
        m.select();
        m.focus();
        return;
      }

      //mochi away
      document.getElementById('waittest').style.display='';

      var url = 'newscontroller.php?'+ queryString({'id':current_news_id,'test_email':m.value});
      var d = loadJSONDoc(url);
      d.addCallbacks(test_result,test_failed);
    }
    function test_result(result)
    {
      document.getElementById('waittest').style.display='none';

      if(result['status']!='OK')
      {
        alert('Teknisk fejl:'+ result['msg']);
        return;
      }
      alert('Nyhedsbrevet er blevet sendt til test adressen');
    }
    function test_failed(err)
    {
      alert('Teknisk fejl:'+ err);
      document.getElementById('waittest').style.display='none';
    }
    function sendNewsLetter()
    {
      if(!confirm('Vil du sende nyhedsbrevet nu?')) return;
      if(!confirm('Helt sikkert?')) return;

      //mochi away
      document.getElementById('waitreal').style.display='';

      var url = 'newscontroller.php?'+ queryString({'id':current_news_id,'send':'1'});
      var d = loadJSONDoc(url);
      d.addCallbacks(send_result,send_failed);
    }
    function send_result(result)
    {
      alert('Nyhedsbrevet er blevet sendt');
      document.getElementById('waitreal').style.display='none';
    }
    function send_failed(err)
    {
      alert(err);
      document.getElementById('waitreal').style.display='none';
    }

    function add_paragraph(column)
    {
        var params ='width=600,height=600,location=no,menubar=no,toolbar=no,titlebar=no,resizable=yes';
        var w = open('picker.php?p_id=-1&n_id=<?=$n_id?>&column='+ column,'picker',params);
        w.focus();
    }
    function editparagraph(id,column)
    {
        var params ='width=600,height=600,location=no,menubar=no,toolbar=no,titlebar=no,resizable=yes';
        var w = open('picker.php?p_id='+ id +'&n_id=<?=$n_id?>&column='+ column,'picker',params);
        w.focus();
    }
    function moveup(idx,column)
    {
        var p_id = position[column][idx];
        if(idx==0)
        {
            var tmp = [];
            for(var i=1;i < position[column].length;i++)
            {
                tmp[tmp.length] = position[column][i];
            }
            tmp[tmp.length] = p_id;
            position[column] = tmp;
        }
        else
        {
            position[column][idx] = position[column][idx - 1];
            position[column][idx - 1] = p_id;
        }
        updateposition();
    }
    function movedown(idx,column)
    {
        var p_id = position[column][idx];
        if(idx== position[column][idx].length)
        {
            var tmp = [p_id];
            for(var i=0;i< (position[column].length - 1);i++)
            {
                tmp[tmp.length] = position[column][i];
            }
            position[column] = tmp;
        }
        else
        {
            position[column][idx] = position[column][idx + 1];
            position[column][idx + 1] = p_id;
        }
        updateposition();
    }
    function updateposition()
    {
        var col_1='';
        var col_2='';
        for(var i=0;i< position['1'].length;i++)
        {
            if(col_1!='') col_1+=',';
            col_1+= position['1'][i];
        }

        for(var i=0;i< position['2'].length;i++)
        {
            if(col_2!='') col_2+=',';
            col_2+= position['2'][i];
        }
        document.getElementById('col_1_position').value= col_1;
        document.getElementById('col_2_position').value= col_2;
        document.myform.submit();
    }

    function removeparagraph(id)
    {
        if(!confirm('Er du sikkert?')) return;
        document.location.href='?pane=<?=$pane?>&n_id=<?=$n_id?>&rempara='+ id;
    }
    function removenewsletter(id)
    {
        if(!confirm('Er du sikkert?')) return;
        document.location.href='?pane=<?=$pane?>&rem='+ id;
    }
</script>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr>
    <td><img src="../graphics/transp.gif" height="30"></td>
  </tr>

  <?if(!$n_id):?>
  <tr>
    <td align="right" style="padding-right:10px">					

          <form method="post" name="myform" action="index.php">
              <input type="hidden" name="n_id" value="-1">
              <input type="hidden" name="pane" value="<?=$pane?>" />
              <input type="submit" value="Opret nyhedsbrev" class="knap" style="width:150px">
          </form>
    </td>
  </tr>
  <tr>
    <td>
    <table id="list" cellpadding="3" cellspacing="0" border="0" width="100%" />
     <tr style="background-color:#e3e3e3;padding-top:3px;padding-bottom:3px;">
        <td>
            Titel
        </td>
        <td>
            Oprettet
        </td>
        <td>
            Sendt
        </td>
        <td>
            Monkey Bussiness
        </td>
        <td width="50" align="right">
            &nbsp;
        </td>
      </tr>
  <?for( $i = 0; $i< count($news); $i++ ):?>
    
     <tr class="<?=($i%2==0)?"color1":"color2"?>" style="padding-top:3px;padding-bottom:3px;">
        <td>
            <a href="?pane=<?=$pane?>&n_id=<?=$news[$i]['id']?>"><?=$news[$i]['name']?></a>
        </td>
        <td>
            <a href="?pane=<?=$pane?>&n_id=<?=$news[$i]['id']?>"><?=$news[$i]['created']?></a>
        </td>
        <td>
            <a href="?pane=<?=$pane?>&n_id=<?=$news[$i]['id']?>"><?=$news[$i]['mailed']?></a>
        </td>
        <td>
            <a href="?pane=<?=$pane?>&n_id=<?=$news[$i]['id']?>"><a href="http://admin.mailchimp.com/reports/" target="_blank"><img src="reports_24x24.png" border="0"></a><!--<?=$news[$i]['chimp_id']?>--></a>
        </td>
        <td width="50" align="right">
            <a href="?pane=<?=$pane?>&n_id=<?=$news[$i]['id']?>"><img src="../../tema/graphics/edit.png" border="0" /></a>
            <a href="javascript:removenewsletter('<?=$news[$i]['id']?>')"><img src="../graphics/delete.png" border="0" /></a>
        </td>
      </tr>
  <?endfor?>
        </table>
    </td>
    </tr>
  <?else:?>

      <?
        $msg = "";
        if($_POST['save']) 
        {
            $newsletter->update($n_id,$_POST['name']);
            $newsletter->updatePosition($_POST['col_1_position'],$_POST['col_2_position']);
            $msg = "Gemt ".date("d.m.y H:i:s");

        }
        $props = $newsletter->load($n_id);
        if($_GET['rempara']) $newsletter->removeParagraph($_GET['rempara']);
        $paragraphs = $newsletter->loadParagraphs($n_id);
      ?> 
      <form name="myform" method="post" action="index.php">

      <input type="hidden" id="col_1_position" name="col_1_position" />
      <input type="hidden" id="col_2_position" name="col_2_position" />
      <input type="hidden" name="save" value="1"/>
      <input type="hidden" name="n_id" value="<?=$n_id?>" />
      <input type="hidden" name="pane" value="<?=$pane?>" />
      <tr>
        <td>
            <img src="../graphics/transp.gif" height="30" width="10">
        </td>
      </tr>
      <tr>
        <td style="padding-left:20px;padding-bottom:10px;font-size:11px">

            <input type="button" value="Preview" onclick="preview()" class="knap" style="float:right;margin-right:20px;width:150px" />

            Subject &nbsp;&nbsp;<input type="text" name="name" style="width:300px" value="<?=$props['name']?>" />

            <?if($msg):?><span id="msg"><?=$msg?></span><?endif?>
        </td>
      </tr>
      <tr>
        <td class="color1" style="padding:20px">

            <br /><br />
            <table id="layout"  cellpadding="4" cellspacing="1" border="0">
                <tr>
                    <td valign="top" style="background-color:#fff">
                        <span style="float:right">
                           <a href="javascript:add_paragraph('1')" 
                              title="Tilf&oslash;j indhold"><img src="../graphics/add.png" border="0" /></a> 
                        </span>
                        Hovedet indhold 
                    </td>
                    <td valign="top" style="background-color:#fff">
                        <span style="float:right">

                           <a href="javascript:add_paragraph('2')" 
                              title="Tilf&oslash;j indhold"><img src="../graphics/add.png" border="0" /></a> 
                        </span>
                        H&oslash;jre kolonne
                    </td>
               </tr>
                <tr>
                    <td valign="top" style="width:450px">

                       <?for($i=0;$i < count($paragraphs['1']);$i++):?>
                            <div class="tools">
                                <a href="javascript:moveup(<?=$i?>,'1')"><img 
                                    src="../graphics/pil_up.gif" border="0" /></a>

                                <a href="javascript:movedown(<?=$i?>,'1')"><img 
                                    src="../graphics/pil_down.gif" border="0" /></a>

                                <!--<a href="#"><img src="../graphics/pil_right.gif" border="0" /></a>-->
                                <a href="javascript:editparagraph('<?=$paragraphs['1'][$i]['id']?>','1')"><img 
                                    src="../../tema/graphics/edit.png" border="0" /></a>
                                <a href="javascript:removeparagraph('<?=$paragraphs['1'][$i]['id']?>')"><img 
                                    src="../graphics/delete.png" border="0" /></a>
                            </div>
                            <div class="para"><?=$paragraphs['1'][$i]['body']?></div>

                            <script>
                                position['1'][position['1'].length] = '<?=$paragraphs['1'][$i]['id']?>';
                            </script>
                       <?endfor?>
                    </td>
                    <td valign="top" style="width:250px">

                       <?for($i=0;$i < count($paragraphs['2']);$i++):?>

                            <div class="tools">
                                <a href="javascript:moveup(<?=$i?>,'2')"><img 
                                    src="../graphics/pil_up.gif" border="0" /></a>

                                <a href="javascript:movedown(<?=$i?>,'2')"><img 
                                    src="../graphics/pil_down.gif" border="0" /></a>

                                <!--<a href="#"><img src="../graphics/pil_left.gif" border="0" /></a>-->
                                <a href="javascript:editparagraph('<?=$paragraphs['2'][$i]['id']?>','2')"><img 
                                    src="../../tema/graphics/edit.png" border="0" /></a>
                                <a href="javascript:removeparagraph('<?=$paragraphs['2'][$i]['id']?>')"><img src="../graphics/delete.png" border="0" /></a>
                            </div>
                            <div class="para"><?=$paragraphs['2'][$i]['body']?></div>

                            <script>
                                position['2'][position['2'].length] = '<?=$paragraphs['2'][$i]['id']?>';
                            </script>
                       <?endfor?>
                    </td>
               </tr>
               
            </table>

            
        </td>
      </tr>
      <tr>
        <td style="padding:20px">
            <span style="float:right">
              <input type="submit" value="Gem" class="knap" style="width:100px;" />
            </span>

            <!--<input type="button" onclick="document.location.href='index.php?pane=newsletters'" 
                value="Til Oversigten" class="knap" style="width:100px" />-->

            <input type="text" id="testmail" value="Test email" 
              onfocus="cleanlabel(this)" 
              onblur="setlabel(this,'Test email')" 
              style="width:100px;color:#999" />

            <input type="button" value="Send test" onclick="testingmail()" class="knap" style="width:150px" />
            <img src="wait.gif" style="display:none" border="0" id="waittest" align="absmiddle" />


            <input type="button" onclick="sendNewsLetter()" value="Send nyhedsbrevet" class="knap" style="width:150px;" />
            <img src="wait.gif" style="display:none" border="0" id="waitreal" align="absmiddle" />
            
        </td>
      </tr>
      </form>
  
  
  <?endif?>
  <tr>
    <td><img src="../graphics/transp.gif" height="30"></td>
  </tr>
</table>
