<?php
/*********************************************************************/
/*   addUser.php                                                    */
/*********************************************************************/
require_once("../util/dba.php");
require_once("../util/user.php");
require_once("../util/users.php");

session_start();

if( $submited || $_POST["submited"] )
{
  if( !$name ) $name = $_POST["name"];
  if( !$full_name ) $full_name = $_POST["full_name"];
  if( !$mail ) $mail = $_POST["mail"];
  if( !$password ) $password = $_POST["password"];
  if( !$confirm_password ) $confirm_password = $_POST["confirm_password"];

  if( $password == $confirm_password )
  {
    $users  = new users( new dba() );
    $editUser = new user( new dba(), $users->addUser() );
    $editUser->setName( $name );
    $editUser->setFull_name( $full_name );
    $editUser->setPassword( $password );
    $editUser->setMail( $mail );
    Header("Location:index.php?pane=users" ); 
  }
  else
  {
    $editUser->mail = $mail;
    $editUser->name = $name;
    $editUser->full_name = $full_name;
    $message = "De passwords du valgte stemmer ikke overens";
  } 
}
$title   = "Tilføj bruger";
?>
<?require_once("userForm.php");?>
