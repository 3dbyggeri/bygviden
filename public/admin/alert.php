<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Wizi Alertpage</title><link rel="stylesheet" href="<?=$path?>style/style.css" type="text/css">
</head>
<body bgcolor="#FFFFFF" class="content_body">
	<form name="myform" action="<?=$PHP_SELF?>" method="post">
		<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td><img src="<?=$path?>graphics/transp.gif"></td>
				<td><img src="<?=$path?>graphics/horisontal_button/left_selected.gif"></td>
				<td class="faneblad_selected"><?=( $warning )? $warning:"Warning"?></td>
				<td><img src="<?=$path?>graphics/horisontal_button/right_selected.gif"></td>
			</tr>
		</table>
		<table width="100%" cellpadding="0" cellspacing="0" border="0" class="tdborder_content">
			<tr>
				<td><img src="<?=$path?>graphics/transp.gif" height="20"></td>
			</tr>
			<tr>
				<td class="header"><?=$header?></td>
			</tr>
			<tr>
				<td><img src="<?=$path?>graphics/transp.gif" height="15"></td>
			</tr>
			<tr>
				<td>
					<table width="100%" cellpadding="0" cellspacing="0" border="0" class="color1">
						<tr>
							<td width="1"><img src="<?=$path?>graphics/transp.gif" border="0" width="1" height="50"></td>
							<td class="alert_message"><?=$message?></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td><img src="<?=$path?>graphics/transp.gif" height="10"></td>
			</tr>
			<tr>
				<td class="tdpadtext">				
					<input type="submit" name="<?=$cancel?>" value="Cancel" class="knap">				
					<input type="submit" name="<?=$submit?>" value="Yes" class="knap">
				</td>
			</tr>
			<tr>
				<td><img src="<?=$path?>graphics/transp.gif" height="10"></td>
			</tr>
		</table>
	</form>
</body>
</html>
