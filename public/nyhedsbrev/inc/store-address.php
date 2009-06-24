<?php
function storeAddress()
{
	
	// Validation
	if(!$_GET['email']){ return "Email mangler"; } 

	if(!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*$/i", $_GET['email'])) {
		return "Email er ikke korrekt"; 
	}
    require_once("../../admin/util/dba.php");
    require_once("../../admin/newsletter/Subscriber.php");


	require_once('MCAPI.class.php');
	$api = new MCAPI('anders.thomsen@teknologisk.dk','ouXNsSAPFk');
	
	// Fetch mailing list id
	$lists = $api->lists();
	$list_id = $lists[0]['id'];

	if($api->listSubscribe($list_id, $_GET['email'], '') === true) {
		// It worked!	
        $subscriber = new subscriber( new dba() );
        $id = $subscriber->add($_GET['email']);

    return 'Du er nu blevet tilmeldt. Tjek din mail og f&oslash;lg linket for at bekr&aelig;fte din tilmelding.';
	}else{
		// An error ocurred, return error message	
		return 'Teknisk fejl: ' . $api->errorMessage;
	}
	
}

// If being called via ajax, autorun the function
if($_GET['ajax']){ echo storeAddress(); }
?>
