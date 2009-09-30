<?php
// require_once('../../debug.php'); // enable show_php function for all kinds of datatypes
function storeAddress()
{
	
	// Validation
	$errors = array();
	if(!$_GET['name']){ $errors[count($errors)] = "Navn skal udfyldes"; } 
	if(!$_GET['email']){ $errors[count($errors)] = "Email skal udfyldes"; } 
	if(!$_GET['branch']){ $errors[count($errors)] = "Branche skal udfyldes"; }

	if(!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*$/i", $_GET['email'])) {
		$errors[count($errors)] = "Email er ikke korrekt"; 
	}

  if(count($errors) > 0){ return implode('<br /> ',$errors); }

  // require_once("../admin/util/dba.php");
  // require_once("../admin/newsletter/Subscriber.php");

	require_once('MCAPI.class.php');
	$api = new MCAPI('anders.thomsen@teknologisk.dk','ouXNsSAPFk');
	
	// Fetch mailing list id
	$lists = $api->lists();
	$list_id = "da07b0a015"; // 'Bygviden Nyhedsbrev on http://us1.admin.mailchimp.com/lists/dashboard?id=1
  $merge_vars = array('name' => $_GET['name'], 'title' => $_GET['title'], 'company' => $_GET['company'], 'branch' => $_GET['branch']);

	if($api->listSubscribe($list_id, $_GET['email'], $merge_vars) === true) {
		// It worked!	
    // $subscriber = new subscriber( new dba() );
    // $id = $subscriber->add($_GET['email']);

    return 'Du er nu blevet tilmeldt. Tjek din mail og f&oslash;lg linket for at bekr&aelig;fte din tilmelding.';
	}else{
		// An error ocurred, return error message	
		return 'Teknisk fejl: ' . $api->errorMessage;
	}
	
}

// If being called via ajax, autorun the function
if($_GET['ajax']){ echo storeAddress(); }
?>
