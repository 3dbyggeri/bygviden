<form name="myform" method="post" style="display:inline">
<input type="hidden" name="save" value="1" />
<input type="hidden" name="pane" value="<?=$pane?>" />
<input type="hidden" name="ctx" value="<?=$ctx?>" />
<input type="hidden" name="id" value="<?=$edit?>" />
<table width="100%" cellpadding="3" cellspacing="0" border="0" class="color1">
    <tr style="background-color:#fff">
        <td  colspan="2" ><strong>Firma</strong></td>
    </tr>
    <tr>
        <td >Navn</td>
        <td >
            <input type="text" class="input" name="company_name" value="<?=$props['company_name']?>">
        </td>
   </tr>
   <tr>
        <td >Beskrivelse</td>
        <td >
            <textarea class="input" name="company_description"><?=$props['company_email']?></textarea>
        </td>
   </tr>
   <tr>
        <td >Logo</td>
        <td >
            <input type="text" class="input" name="company_logo" value="<?=$props['company_logo']?>">
        </td>
   </tr>
   <tr>
        <td >Adresse</td>
        <td >
            <input type="text" class="input" name="company_address" value="<?=$props['company_address']?>">
        </td>
   </tr>
   <tr>
        <td >Postnr.</td>
        <td >
            <input type="text" class="input" name="company_postbox" value="<?=$props['company_postbox']?>">
        </td>
   </tr>
   <tr>
        <td >By</td>
        <td >
            <input type="text" class="input" name="company_city" value="<?=$props['company_city']?>">
        </td>
   </tr>
   <tr>
        <td >CVR nr.</td>
        <td >
            <input type="text" class="input" name="company_cvr" value="<?=$props['company_cvr']?>">
        </td>
   </tr>
   <tr>
        <td >Telefon</td>
        <td >
            <input type="text" class="input" name="company_telefon" value="<?=$props['company_telefon']?>">
        </td>
   </tr>
   <tr>
        <td >Fax</td>
        <td >
            <input type="text" class="input" name="company_fax" value="<?=$props['company_fax']?>">
        </td>
   </tr>
   <tr>
        <td >Email</td>
        <td >
            <input type="text" class="input" name="company_email" value="<?=$props['company_email']?>">
        </td>
   </tr>

    <tr style="background-color:#fff">
        <td  colspan="2">
            <br />
            <strong>Kontakt person</strong>
        </td>
    </tr>
    <tr>
        <td >Navn</td>
        <td >
            <input type="text" class="input" name="contact_name" value="<?=$props['contact_name']?>">
        </td>
   </tr>
    <tr>
        <td >Email</td>
        <td >
            <input type="text" class="input" name="contact_email" value="<?=$props['contact_email']?>">
        </td>
   </tr>
    <tr>
        <td >Password</td>
        <td >
            <input type="text" class="input" name="contact_password" value="<?=$props['contact_password']?>">
        </td>
   </tr>
    <tr>
        <td >Telefon</td>
        <td >
            <input type="text" class="input" name="contact_telefon" value="<?=$props['contact_telefon']?>">
        </td>
    </tr>
    <tr>
        <td>Status</td>
        <td>
            <select name="status">
                <option value="T" <?=($props['status']=='T')?'selected="selected"':''?>>Pr&oslash;ve</option>
                <option value="A" <?=($props['status']=='A')?'selected="selected"':''?>>Aktiv</option>
                <option value="P" <?=($props['status']=='P')?'selected="selected"':''?>>Pasiv</option>
            </select>

            (<?=$props['triald']?> days)
        </td>
    </tr>
    <tr>
        <td >Abonner p&aring; nyhedsbrev</td>
        <td >
            <input type="checkbox" name="contact_subscribe_to_newsletter" <?=($props['contact_subscribe_to_newsletter']=='y')?'checked':''?>>
        </td>
    </tr>
    <tr style="background-color:#fff">
        <td >&nbsp;</td>
        <td >
            <input type="submit" value="Gemt" class="knap" style="width:150px">
        </td>
    </tr>
</table>
</form>

