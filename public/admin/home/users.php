<?php
    require_once("../util/users.php");
    $users    = new users($dba);
    $userList = $users->getUsers();
?>
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td colspan="3"><img src="../graphics/transp.gif" height="20"></td>
		</tr>
		<tr>
			<td class="header">Bruger navn</td>
			<td class="header">Fuld navn</td>
			<td class="header">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="3"><img src="../graphics/transp.gif" height="15"></td>
		</tr>
		<?for( $i = 0; $i< count( $userList ); $i++ ):?>
		<tr class="<?=($i%2==0)?"color1":"color2"?>" style="padding-top:3px;padding-bottom:3px;">
			<td><a href="editUser.php?id=<?=$userList[$i]["id"]?>" class="tabelText" style="padding-top:3px;padding-bottom:3px"><?=$userList[$i]["name"]?></a></td>
			<td><a href="editUser.php?id=<?=$userList[$i]["id"]?>" class="tabelText"><?=$userList[$i]["full_name"]?></a></td>
			<td><?if( $userList[$i]["id"] != 1 ):?><a href="deleteUser.php?id=<?=$userList[$i]["id"]?>" class="tabelText">Delete</a><?endif?></td>
		</tr>
		<?endfor?>
		<tr>
			<td colspan="3"><img src="../graphics/transp.gif" height="15"></td>
		</tr>
		<tr>
			<td class="small_header">&nbsp;</td>
			<td class="small_header">&nbsp;</td>
			<form name="my_name" action="addUser.php" method="post">
				<td class="header" align="right">					
					<input type="submit" value="Tilføj bruger" name="addUser" class="knap" style="width:100px">
          &nbsp;&nbsp;
				</td>
			</form>
		</tr>
		<tr>
			<td colspan="3"><img src="../graphics/transp.gif" height="15"></td>
		</tr>
	</table>
