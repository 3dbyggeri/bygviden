<?php
/*********************************************************************/
/*   editUser.php                                                    */
/*********************************************************************/
require_once("../util/dba.php");
require_once("../util/user.php");

session_start();
$user   = new user( new dba() );
if( !$user->isLogged() ) die("<script language='javascript'>top.document.location.href='../log.php';</scrip>");

if( !$id ) $id = $_GET["id"];
if( is_numeric( $id ) )
{
    $editUser = new user( new dba(), $id );
}
else
{
  die("Parameter id expected");
}

if( $submited || $_POST["submited"] )
{
    if( !$name ) $name = $_POST["name"];
    if( !$full_name ) $full_name = $_POST["full_name"];
    if( !$password ) $password = $_POST["password"];
    if( !$confirm_password ) $confirm_password = $_POST["confirm_password"];
    if( !$mail ) $mail = $_POST["mail"];

    if( $password == $confirm_password )
    {
      $editUser->setName( $name );
      $editUser->setFull_name( $full_name );

      if( trim( $password ) ) $editUser->setPassword( $password );
      $editUser->setMail( $mail );

      Header("Location:index.php?pane=users");
    }
    else
    {
      $editUser->mail = $mail;
      $editUser->name = $name;
      $editUser->full_name = $full_name;
      $message = "Dinne passwords stemmer ikke overens";  
    } 
}

$title = "Redigere indstillinger for ";
$title.= ( $editUser->full_name )? $editUser->full_name: $editUser->name;
?>
<?require_once("userForm.php");?>
