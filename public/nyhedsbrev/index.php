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
  		  
			  <div><label for="name" id="name-label">Dit navn*</label><br />
			  <input type="text" name="name" id="name" style="width:175px"/></div>

			  <div><label for="email" id="address-label">Din email adresse*</label><br />
			  <input type="text" name="email" id="email" style="width:175px"/></div>

			  <div><label for="title" id="title-label">Titel</label><br />
			  <input type="text" name="title" id="title" style="width:175px"/></div>

			  <div><label for="company" id="company-label">Virksomhedsnavn*</label><br />
			  <input type="text" name="company" id="company" style="width:175px"/></div>

			  <div><label for="branch" id="branch-label">Branche*</label><br />
			  <select name="branch" id="branch" style="width:175px">
          <option value="">V&aelig;lg...</option>
          <option value="carpenter">T&oslash;mrer</option>
          <option value="joiner">Snedker</option>
          <option value="painter">Maler</option>
          <option value="bricklayer">Murer</option>
          <option value="contractor">Entrepren&oslash;r</option>
          <option value="student">Studerende</option>
          <option value="private">Privat (g&oslash;r-det-selv)</option>
          <option value="other">Andet</option>
        </select></div>

			  <p><input type="submit" name="submit" value="Tilmeld" class="btn" alt="Tilmeld" /> (Felter med * skal udfyldes)</p>

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
