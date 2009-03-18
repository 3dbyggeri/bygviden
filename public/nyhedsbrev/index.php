<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
		<title>Bygviden nyhedsbrev</title>
		<link type="text/css" rel="stylesheet" href="css/default.css" />
	</head>

	<body>
              <div id="subscribe">
		
		 <form id="signup" action="<?=$_SERVER['PHP_SELF']; ?>" method="get">
		  <fieldset>
			  
			  <label for="email" id="address-label">Din email adresse
				
			  </label>
			  <div><input type="text" name="email" id="email" style="width:175px"/>
			  <input type="submit" name="submit" value="Tilmeld" class="btn" alt="Tilmeld" /></div>
                          <div id="response">
					<? require_once('inc/store-address.php'); if($_GET['submit']){ echo storeAddress(); } ?>
                          </div>
			
	    <div id="no-spam">Vi h&aring;ndterer din email adresse som en fortrolig oplysning, 
      og viderebringer den derfor ikke til andre.</div>


        
		  </fieldset>
		</form>      
		<script type="text/javascript" src="js/prototype.js"></script>
		<script type="text/javascript" src="js/mailing-list.js"></script>
            </div>
	</body>
</html>
