<form name="usage_form">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr> 
    <td><img src="../graphics/transp.gif" height="20"></td>
  </tr> 
  <tr>
    <td>
      <table width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
          <td class="header">
            <?=$kilde->name?>  <span class="alert_message"><?=$message?></span>
          </td>
       </tr>
      </table>
    </td>
  </tr>
  <tr><td>&nbsp;</td></tr>
  <tr>
  	<td>
        <table id="stamdata" width="100%" cellpadding="0" cellspacing="0" border="0" >
            <tr>
                <td>
                <style>
                    #usage td {font-size:11px;background-color:#FFF0A4;}
                    #usage td.color2 {background-color:#FFCE63;}
                    #usage td.color3 {background-color:#cdcdcd;}
                </style>
        <?
            $actors = Array('all'=>'Alle',
                            'login'=>'Medlem',
                            'byg'=>'Dansk Byggeri',
                            'ark'=>'Ark',
                            'fri'=>'Fri',
                            'tun'=>'Tun',
                            'no'=>'Ingen');
        ?>
        <table id="usage" width="100%" cellpadding="5" cellspacing="1" border="0">
            <tr>
                <td class="color2">&nbsp;</td>
                <?foreach($actors as $key=>$value):?>
                    <td class="color2">
                        <?=$value?>
                    </td>
                <?endforeach?>
            </tr>
            <tr>
                <td  class="color2">Ikke publiceret</td>

                <?foreach($actors as $key=>$value):?>
                    <td>
                        <input type="checkbox" name="<?=$key?>" value="unpublish" onclick="check_single(this)"/>
                    </td>
                <?endforeach?>
            </tr>
            <tr>
                <td  class="color2">Publiceret</td>

                <?foreach($actors as $key=>$value):?>
                    <td>
                        <input type="checkbox" name="<?=$key?>" value="publish" onclick="check_single(this)"/>
                    </td>
                <?endforeach?>
            </tr>
            <tr>
                <td  class="color2">Resume</td>

                <?foreach($actors as $key=>$value):?>
                    <td>
                        <input type="checkbox" name="<?=$key?>" value="resume" onclick="check_single(this)"/>
                    </td>
                <?endforeach?>
            </tr>
            <tr>
                <td class="color2">Fuldtekst</td>

                <?foreach($actors as $key=>$value):?>
                    <td>
                        <input type="checkbox" name="<?=$key?>" value="fuldtekst" onclick="check_single(this)" />
                    </td>
                <?endforeach?>
            </tr>
            <script>
                function check_single(n)
                {
                    if(!n.checked) return;
                    var fields = eval('document.usage_form' );
                    for( var i = 0; i < fields.elements.length; i++ )
                    {
                       if( fields.elements[i].name != n.name ) continue;
                       if( fields.elements[i].value != n.value )
                       {
                            fields.elements[i].checked = false;
                            var ctxt = document.getElementById(fields.elements[i].value +'_'+ fields.elements[i].name );
                            if(ctxt) ctxt.style.display = 'none';
                       }
                    }
                }

                function toggling(n)
                {
                    var ctxt = document.getElementById( n.value +'_'+ n.name );
                    ctxt.style.display = (ctxt.style.display =='none')? 'block':'none';
                    check_single(n);
                }
            </script>
            <tr>
                <td class="color2">Enkelt pris</td>
                <td class="color3">&nbsp;</td>

                <?foreach($actors as $key=>$value):?>
                    <?if($key=='all') continue; ?>
                    <td>

                        <table cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td valign="top">
                                    <input type="checkbox" name="<?=$key?>" value="enkelt_pris" onclick="toggling(this)" />
                                </td>
                                <td>
                                    <span id="enkelt_pris_<?=$key?>" style="display:none">
                                       <input type="text" name="enkelt_pris_medlem" size="5"> kr.
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </td>
                <?endforeach?>
            </tr>
            <tr>
                <td class="color2">Abonament</td>
                <td class="color3">&nbsp;</td>

                <?foreach($actors as $key=>$value):?>
                    <?if($key=='all') continue; ?>
                    <td nowrap>

                        <table cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td valign="top">
                                    <input type="checkbox" name="<?=$key?>" value="abonament" onclick="toggling(this)" />
                                </td>
                                <td>
                                    <span id="abonament_<?=$key?>" style="display:none">
                                       <select name="abonament_periode">
                                          <?for( $i = 1; $i < 25; $i++ ):?>
                                            <option value="<?=$i?>" <?=( $kilde->abonament_periode == $i )?'selected':''?>><?=$i?> m.</option>
                                          <?endfor?>
                                       </select>
                                       <input type="text" name="enkelt_pris_medlem" size="5"> kr.
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </td>
                <?endforeach?>
            </tr>
            <tr>
                <td class="color2" valign="top">Bruger ordning</td>
                <td class="color3">&nbsp;</td>
                <?foreach($actors as $key=>$value):?>
                    <?if($key=='all') continue; ?>
                    <td>

                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td valign="top">
                                    <input type="checkbox" name="<?=$key?>" value="rabat"  onclick="toggling(this)"/>
                                </td>
                                <td valign="top">
                                    <table style="margin-left:4px;display:none" id="rabat_<?=$key?>" cellpadding="0" cellspacing="0" border="0" <?=$style?>>
                                      <?foreach($kilde->bruger_data_model as $k=>$v):?>
                                        <tr>
                                          <td><?=$v?></td>
                                          <td nowrap><input type="text" size="5" name="<?=$k?>" value="<?=$bruger_model[$k]?>"> kr.</td>
                                        </tr>
                                      <?endforeach?>
                                    </table>

                                </td>
                           </tr>
                        </table>

                    </td>
                <?endforeach?>
            </tr>
        </table>

            </tr>
        </table>
    </td>
 </tr>
  <tr><td>&nbsp;</td></tr>
  <tr>
    <td >
		    <table cellpadding="0" cellspacing="0" border="0" width="325">
          <tr>
            <td class="tdpadtext">
              <?if( $referer ):?>
                <a href="<?=$referer?>"><img align="middle" src="../graphics/back_arrow.gif" border="0"></a>
              <?endif?>
            </td>
            <td  align="right">
              <input type="button" value="Fortryd" name="cancel" class="knap">
              <input type="button" value="Gemt" name="editproperties" class="knap"> 
            </td>
           </tr>
				</table>
      </td>
    </tr>
    <tr> 
        <td ><img src="../graphics/transp.gif" height="15"></td>
    <tr>
  </table>
</form>
