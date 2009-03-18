<?php
    /*********************************************************************/
    /*   set_up.php                                                      */
    /*********************************************************************/
    /*   Ronald Jaramillo   -                                            */
    /*                                                                   */
    /*   V I Z I O N   F A C T O R Y   N E W M E D I A                   */
    /*   Vermundsgade 40C - 2100 København Ø - Danmark                   */
    /*   Tel : +45 39 29  25 11 - Fax: +45 39 29 80 11                   */
    /*   ronald@vizionfactory.dk - www.vizionfactory.dk                  */
    /*                                                                   */
    /*********************************************************************/
    require("../util/dba.php");
    $dba = new dba();
    $prefix = $p =  $dba->getPrefix();

    
    require("kildestyring.php");
    require("producentstyring.php");
    require("agentstyring.php");
    require("buildingselement.php");
    require("brancher.php");



    /*****************************  user   *******************************/
    $sql= "DROP TABLE IF EXISTS ".$prefix."a_user";
    $dba->exec( $sql );

    $sql = "CREATE TABLE
                ".$prefix."a_user
            (
                id                  INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
                name                VARCHAR(50),
                full_name           VARCHAR(50),
                password            VARCHAR(100),
                mail                VARCHAR(100),
                language            VARCHAR(20),
                warning             INTEGER,
                sessionTime         INTEGER,
                sessid              VARCHAR(200),
                sessionStart        TIMESTAMP,
		            pane	              VARCHAR(50)
            )";
    $dba->exec( $sql );

    $status.=$prefix."user table created<br>";

    //insert admin user
    $sql= "INSERT INTO
            ".$prefix."a_user
           (
                name,
                password
            )
            VALUES
            (
                'admin',
                '". md5( 'admin') ."'
            )";
    $dba->exec( $sql );

    /*****************************  role *******************************/
    $sql = "DROP TABLE IF EXISTS ".$prefix."role";
    $dba->exec( $sql );

    $sql = "CREATE TABLE
                ".$prefix."role
            (
                id                  INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
                name                VARCHAR(50),
                description         TEXT
            )";
    $dba->exec( $sql );

    $status.=$prefix."role table created<br>";

    //create admin role
    $sql = "INSERT INTO
                ".$prefix."role
            (
                name
            )
            VALUES
            (
                'admin'
            )";
    $dba->exec( $sql );

    /*************************  user2role *****************************/
    $sql = "DROP TABLE IF EXISTS ".$prefix."user2role";
    $dba->exec( $sql );

    $sql = "CREATE TABLE
                ".$prefix."user2role
            (
                role     INTEGER,
                user     INTEGER
            )";

    $dba->exec( $sql );

    $status.=$prefix."user2role table created<br>";

    //add admin to admin role
    $sql = "INSERT INTO
                ".$prefix."user2role

            (
                role,
                user
            )
            VALUES
            (
                1,
                1
            )";
    $dba->exec( $sql );

    /*************************  realms *****************************/
    $sql = "DROP TABLE IF EXISTS ". $prefix ."realms";
    $dba->exec( $sql );

    $sql = "CREATE TABLE
                ".$prefix."realms
            (
                id                  INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
                name                VARCHAR(50)
            )";
    $dba->exec( $sql );

    $status.=$prefix."realms table created<br>";

    $sql = "INSERT INTO ".$prefix."realms( name ) VALUES( 'Edit' )";
    $dba->exec( $sql );

    $sql = "INSERT INTO ".$prefix."realms( name ) VALUES( 'Rename' )";
    $dba->exec( $sql );

    $sql = "INSERT INTO ".$prefix."realms( name ) VALUES( 'Delete' )";
    $dba->exec( $sql );

    $sql = "INSERT INTO ".$prefix."realms( name ) VALUES( 'Create' )";
    $dba->exec( $sql );

    $sql = "INSERT INTO ".$prefix."realms( name ) VALUES( 'Duplicate' )";
    $dba->exec( $sql );

    $sql = "INSERT INTO ".$prefix."realms( name ) VALUES( 'Move' )";
    $dba->exec( $sql );

    $sql = "INSERT INTO ".$prefix."realms( name ) VALUES( 'Restore' )";
    $dba->exec( $sql );

    $sql = "INSERT INTO ".$prefix."realms( name ) VALUES( 'Remove version' )";
    $dba->exec( $sql );

    $sql = "INSERT INTO ".$prefix."realms( name ) VALUES( 'Properties' )";
    $dba->exec( $sql );


    /*************************  roles_constrains *****************************/
    $sql = "DROP TABLE IF EXISTS ". $prefix ."roles_constrains";
    $dba->exec( $sql );

    $sql = "CREATE TABLE
                ".$prefix."roles_constrains
            (
                doc               INTEGER,
                role              INTEGER,
                realm             INTEGER
            )";
    $dba->exec( $sql );

    $status.=$prefix."roles_constrains table created<br>";


    //give admin all right over index page
    $sql = "INSERT INTO
            ".$prefix."roles_constrains
            ( doc, role, realm )
            SELECT
              1,
              1,
              id
            FROM ".$prefix."realms";
    $dba->exec( $sql );


    //now register the user id and start a session for him
    session_start();
    session_register("1");
    $sid = session_id();
    $sql = "UPDATE ".$prefix."a_user SET sessid='$sid',sessionStart=NOW() WHERE id=1";
    $dba->exec($sql);
?>
<html>
	<head>
		<title>Install</title>
        <link rel="stylesheet" href="../style/style.css" type="text/css">
	</head>
	<body class="grayBody">
        <br><br>
        <br><br>
        <center>
            <table cellpadding="0" cellspacing="0" border="0" width="400">
                <tr>
                    <td class="Header2" colspan="2">Following tables where created by the system:</td>
                </tr>
                <tr>
                    <td colspan="2"><img src="../graphics/red.gif" border="0" width="400" height="3"></td>
                </tr>
                <tr>
                    <td colspan="2"><img src="../graphics/transp.gif" border="0" width="400" height="10"></td>
                </tr>
                <tr>

                    <td class="plainText" colspan="2">
                        <?php echo $status;?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><img src="../graphics/transp.gif" border="0" width="400" height="10"></td>
                </tr>
                <tr>
                    <td colspan="2"><img src="../graphics/red.gif" border="0" width="400" height="3"></td>
                </tr>
                <tr>
                    <td colspan="2" align="right">
                        <input type="button" value=" START " onClick="document.location.href='../index.php';" class="knap" style="width:200px">
                    </td>
                </tr>
            </table>
        </center>
	</body>
</html>
