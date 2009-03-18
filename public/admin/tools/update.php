<?
                require_once("../util/dba.php");
                $dba = new dba();
           

        $sql =" UPDATE dev_bruger SET firmanavn1='Anja', firmanavn2='Binderup', restricted_shop='n', parent=10136 WHERE bruger_navn='abi'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('abi','abi',10136,'Anja','Binderup','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Anders Frederik', firmanavn2='Gjesing', restricted_shop='n', parent=10136 WHERE bruger_navn='AFG'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('AFG','AFG',10136,'Anders Frederik','Gjesing','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Anne Hartmann', firmanavn2='Fordsmann', restricted_shop='n', parent=10136 WHERE bruger_navn='AHF'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('AHF','AHF',10136,'Anne Hartmann','Fordsmann','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Anders', firmanavn2='Hundahl', restricted_shop='n', parent=10136 WHERE bruger_navn='AHU'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('AHU','AHU',10136,'Anders','Hundahl','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Anders', firmanavn2='Larsen', restricted_shop='n', parent=10136 WHERE bruger_navn='ALA'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('ALA','ALA',10136,'Anders','Larsen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Anita', firmanavn2='Løvstrøm', restricted_shop='n', parent=10136 WHERE bruger_navn='ALL'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('ALL','ALL',10136,'Anita','Løvstrøm','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Anita K.', firmanavn2='Larsen', restricted_shop='n', parent=10136 WHERE bruger_navn='ANL'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('ANL','ANL',10136,'Anita K.','Larsen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Alex Nybo', firmanavn2='Nørby', restricted_shop='n', parent=10136 WHERE bruger_navn='ANN'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('ANN','ANN',10136,'Alex Nybo','Nørby','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Anne Schak', firmanavn2='Tobiasen', restricted_shop='n', parent=10136 WHERE bruger_navn='AST'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('AST','AST',10136,'Anne Schak','Tobiasen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Birgitte', firmanavn2='Bryde', restricted_shop='n', parent=10136 WHERE bruger_navn='BBR'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('BBR','BBR',10136,'Birgitte','Bryde','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Børge', firmanavn2='Damm', restricted_shop='n', parent=10136 WHERE bruger_navn='BDA'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('BDA','BDA',10136,'Børge','Damm','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Bent', firmanavn2='Jensen', restricted_shop='n', parent=10136 WHERE bruger_navn='BEJ'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('BEJ','BEJ',10136,'Bent','Jensen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Børge', firmanavn2='Elgaard', restricted_shop='n', parent=10136 WHERE bruger_navn='BEL'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('BEL','BEL',10136,'Børge','Elgaard','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Bente', firmanavn2='Strandhøj', restricted_shop='n', parent=10136 WHERE bruger_navn='BES'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('BES','BES',10136,'Bente','Strandhøj','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Bente', firmanavn2='Frederiksen', restricted_shop='n', parent=10136 WHERE bruger_navn='BFR'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('BFR','BFR',10136,'Bente','Frederiksen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Britta', firmanavn2='Helseby', restricted_shop='n', parent=10136 WHERE bruger_navn='BHE'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('BHE','BHE',10136,'Britta','Helseby','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Bjarne', firmanavn2='Nielsen', restricted_shop='n', parent=10136 WHERE bruger_navn='BNI'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('BNI','BNI',10136,'Bjarne','Nielsen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Bent', firmanavn2='Hansen', restricted_shop='n', parent=10136 WHERE bruger_navn='BTH'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('BTH','BTH',10136,'Bent','Hansen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Carsten', firmanavn2='Bekker', restricted_shop='n', parent=10136 WHERE bruger_navn='CBE'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('CBE','CBE',10136,'Carsten','Bekker','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Christian L.', firmanavn2='Sørensen', restricted_shop='n', parent=10136 WHERE bruger_navn='cls'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('cls','cls',10136,'Christian L.','Sørensen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Dorte', firmanavn2='Brygger', restricted_shop='n', parent=10136 WHERE bruger_navn='DBJ'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('DBJ','DBJ',10136,'Dorte','Brygger','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Ditte', firmanavn2='Brøndum', restricted_shop='n', parent=10136 WHERE bruger_navn='DIB'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('DIB','DIB',10136,'Ditte','Brøndum','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='David', firmanavn2='Kiertzner', restricted_shop='n', parent=10136 WHERE bruger_navn='DKI'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('DKI','DKI',10136,'David','Kiertzner','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Dorthe', firmanavn2='Klug', restricted_shop='n', parent=10136 WHERE bruger_navn='DKL'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('DKL','DKL',10136,'Dorthe','Klug','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Dorthe', firmanavn2='Bøttcher', restricted_shop='n', parent=10136 WHERE bruger_navn='dob'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('dob','dob',10136,'Dorthe','Bøttcher','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Erik', firmanavn2='Christiansen', restricted_shop='n', parent=10136 WHERE bruger_navn='EEC'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('EEC','EEC',10136,'Erik','Christiansen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Elin', firmanavn2='Hougaard', restricted_shop='n', parent=10136 WHERE bruger_navn='EHC'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('EHC','EHC',10136,'Elin','Hougaard','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Eva', firmanavn2='Witthøft', restricted_shop='n', parent=10136 WHERE bruger_navn='EJW'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('EJW','EJW',10136,'Eva','Witthøft','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Eva', firmanavn2='Kartholm', restricted_shop='n', parent=10136 WHERE bruger_navn='EKA'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('EKA','EKA',10136,'Eva','Kartholm','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Erik Teglgaard', firmanavn2='Nielsen', restricted_shop='n', parent=10136 WHERE bruger_navn='ETE'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('ETE','ETE',10136,'Erik Teglgaard','Nielsen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Finn Bo', firmanavn2='Frandsen', restricted_shop='n', parent=10136 WHERE bruger_navn='FBF'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('FBF','FBF',10136,'Finn Bo','Frandsen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Finn', firmanavn2='Hørning', restricted_shop='n', parent=10136 WHERE bruger_navn='FIH'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('FIH','FIH',10136,'Finn','Hørning','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Finn', firmanavn2='Lodsgaard', restricted_shop='n', parent=10136 WHERE bruger_navn='FLO'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('FLO','FLO',10136,'Finn','Lodsgaard','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Frank', firmanavn2='Rasmussen', restricted_shop='n', parent=10136 WHERE bruger_navn='FRA'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('FRA','FRA',10136,'Frank','Rasmussen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Gro', firmanavn2='Andersen', restricted_shop='n', parent=10136 WHERE bruger_navn='GCA'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('GCA','GCA',10136,'Gro','Andersen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Gert', firmanavn2='Jørgensen', restricted_shop='n', parent=10136 WHERE bruger_navn='GEJ'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('GEJ','GEJ',10136,'Gert','Jørgensen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Gry', firmanavn2='Hansen', restricted_shop='n', parent=10136 WHERE bruger_navn='GMH'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('GMH','GMH',10136,'Gry','Hansen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Grethe', firmanavn2='Wittendorf', restricted_shop='n', parent=10136 WHERE bruger_navn='GWN'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('GWN','GWN',10136,'Grethe','Wittendorf','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Hardy', firmanavn2='Käehne', restricted_shop='n', parent=10136 WHERE bruger_navn='HAK'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('HAK','HAK',10136,'Hardy','Käehne','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Helle Carlsson', firmanavn2='Christensen', restricted_shop='n', parent=10136 WHERE bruger_navn='HCC'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('HCC','HCC',10136,'Helle Carlsson','Christensen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Henning', firmanavn2='Ejsing', restricted_shop='n', parent=10136 WHERE bruger_navn='HEJ'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('HEJ','HEJ',10136,'Henning','Ejsing','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Henrik', firmanavn2='Fausing', restricted_shop='n', parent=10136 WHERE bruger_navn='HFA'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('HFA','HFA',10136,'Henrik','Fausing','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Helle', firmanavn2='Frimann', restricted_shop='n', parent=10136 WHERE bruger_navn='HFR'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('HFR','HFR',10136,'Helle','Frimann','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Hans Henrik', firmanavn2='Kristensen', restricted_shop='n', parent=10136 WHERE bruger_navn='HHK'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('HHK','HHK',10136,'Hans Henrik','Kristensen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Henrik Hjelm', firmanavn2='Pedersen', restricted_shop='n', parent=10136 WHERE bruger_navn='HHP'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('HHP','HHP',10136,'Henrik Hjelm','Pedersen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Heidi P.', firmanavn2='Jacobsen', restricted_shop='n', parent=10136 WHERE bruger_navn='HIP'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('HIP','HIP',10136,'Heidi P.','Jacobsen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Henning', firmanavn2='Kaab', restricted_shop='n', parent=10136 WHERE bruger_navn='HKA'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('HKA','HKA',10136,'Henning','Kaab','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Henrik', firmanavn2='Jørgensen', restricted_shop='n', parent=10136 WHERE bruger_navn='HKJ'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('HKJ','HKJ',10136,'Henrik','Jørgensen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Hanne', firmanavn2='Lund-Pedersen', restricted_shop='n', parent=10136 WHERE bruger_navn='HLP'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('HLP','HLP',10136,'Hanne','Lund-Pedersen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Hans Madsen', firmanavn2='Sørensen', restricted_shop='n', parent=10136 WHERE bruger_navn='HMS'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('HMS','HMS',10136,'Hans Madsen','Sørensen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Holger', firmanavn2='Jørgensen', restricted_shop='n', parent=10136 WHERE bruger_navn='HOJ'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('HOJ','HOJ',10136,'Holger','Jørgensen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Ove', firmanavn2='Kronborg', restricted_shop='n', parent=10136 WHERE bruger_navn='HOK'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('HOK','HOK',10136,'Ove','Kronborg','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Henrik', firmanavn2='Olsen', restricted_shop='n', parent=10136 WHERE bruger_navn='HOL'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('HOL','HOL',10136,'Henrik','Olsen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Henning P.', firmanavn2='Christiansen', restricted_shop='n', parent=10136 WHERE bruger_navn='HPC'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('HPC','HPC',10136,'Henning P.','Christiansen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Hanna', firmanavn2='Sonsberg', restricted_shop='n', parent=10136 WHERE bruger_navn='HSO'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('HSO','HSO',10136,'Hanna','Sonsberg','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Henrik Stig', firmanavn2='Sørensen', restricted_shop='n', parent=10136 WHERE bruger_navn='HSS'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('HSS','HSS',10136,'Henrik Stig','Sørensen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Henriette', firmanavn2='Thuen', restricted_shop='n', parent=10136 WHERE bruger_navn='HTU'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('HTU','HTU',10136,'Henriette','Thuen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Hugo', firmanavn2='Møller', restricted_shop='n', parent=10136 WHERE bruger_navn='HUM'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('HUM','HUM',10136,'Hugo','Møller','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Holger', firmanavn2='Vohs', restricted_shop='n', parent=10136 WHERE bruger_navn='HVO'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('HVO','HVO',10136,'Holger','Vohs','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Ib', firmanavn2='Mechlenborg', restricted_shop='n', parent=10136 WHERE bruger_navn='IBM'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('IBM','IBM',10136,'Ib','Mechlenborg','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Inger', firmanavn2='Andersen', restricted_shop='n', parent=10136 WHERE bruger_navn='IMA'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('IMA','IMA',10136,'Inger','Andersen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Iver', firmanavn2='Nordentoft', restricted_shop='n', parent=10136 WHERE bruger_navn='INO'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('INO','INO',10136,'Iver','Nordentoft','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Jane Møller', firmanavn2='Hansen', restricted_shop='n', parent=10136 WHERE bruger_navn='jam'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('jam','jam',10136,'Jane Møller','Hansen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Jørgen', firmanavn2='Andersen', restricted_shop='n', parent=10136 WHERE bruger_navn='JAN'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('JAN','JAN',10136,'Jørgen','Andersen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Jette', firmanavn2='Friis', restricted_shop='n', parent=10136 WHERE bruger_navn='JEF'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('JEF','JEF',10136,'Jette','Friis','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Jørgen', firmanavn2='Faarbæk', restricted_shop='n', parent=10136 WHERE bruger_navn='JFA'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('JFA','JFA',10136,'Jørgen','Faarbæk','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Jann', firmanavn2='Frei', restricted_shop='n', parent=10136 WHERE bruger_navn='JFR'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('JFR','JFR',10136,'Jann','Frei','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Julie Greve', firmanavn2='Lindholm', restricted_shop='n', parent=10136 WHERE bruger_navn='jgl'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('jgl','jgl',10136,'Julie Greve','Lindholm','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Jørgen', firmanavn2='Heien', restricted_shop='n', parent=10136 WHERE bruger_navn='JHE'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('JHE','JHE',10136,'Jørgen','Heien','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Jacob Hougaard', firmanavn2='Hansen', restricted_shop='n', parent=10136 WHERE bruger_navn='JHH'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('JHH','JHH',10136,'Jacob Hougaard','Hansen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='John', firmanavn2='Hvass', restricted_shop='n', parent=10136 WHERE bruger_navn='JHV'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('JHV','JHV',10136,'John','Hvass','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Jesper Juul', firmanavn2='Sørensen', restricted_shop='n', parent=10136 WHERE bruger_navn='JJS'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('JJS','JJS',10136,'Jesper Juul','Sørensen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Jan', firmanavn2='Hesselberg', restricted_shop='n', parent=10136 WHERE bruger_navn='JKH'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('JKH','JKH',10136,'Jan','Hesselberg','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Jens', firmanavn2='Klarskov', restricted_shop='n', parent=10136 WHERE bruger_navn='JKL'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('JKL','JKL',10136,'Jens','Klarskov','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Jannie Lybæch', firmanavn2='Christensen', restricted_shop='n', parent=10136 WHERE bruger_navn='JLC'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('JLC','JLC',10136,'Jannie Lybæch','Christensen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='John Lundby', firmanavn2='Petersen', restricted_shop='n', parent=10136 WHERE bruger_navn='JLP'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('JLP','JLP',10136,'John Lundby','Petersen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Janne Hersom', firmanavn2='Madsen', restricted_shop='n', parent=10136 WHERE bruger_navn='JMA'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('JMA','JMA',10136,'Janne Hersom','Madsen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Jette Møller', firmanavn2='Kristensen', restricted_shop='n', parent=10136 WHERE bruger_navn='JMK'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('JMK','JMK',10136,'Jette Møller','Kristensen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='John Normann', firmanavn2='Nørhave', restricted_shop='n', parent=10136 WHERE bruger_navn='JNN'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('JNN','JNN',10136,'John Normann','Nørhave','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Jørgen', firmanavn2='Boje', restricted_shop='n', parent=10136 WHERE bruger_navn='JOB'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('JOB','JOB',10136,'Jørgen','Boje','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Jonas', firmanavn2='Møller', restricted_shop='n', parent=10136 WHERE bruger_navn='JOM'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('JOM','JOM',10136,'Jonas','Møller','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Johs.', firmanavn2='Pedersen', restricted_shop='n', parent=10136 WHERE bruger_navn='JOP'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('JOP','JOP',10136,'Johs.','Pedersen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='John', firmanavn2='Steffensen', restricted_shop='n', parent=10136 WHERE bruger_navn='jst'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('jst','jst',10136,'John','Steffensen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Jens', firmanavn2='Thøgersen', restricted_shop='n', parent=10136 WHERE bruger_navn='JTH'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('JTH','JTH',10136,'Jens','Thøgersen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Kjeld Almer', firmanavn2='Nielsen', restricted_shop='n', parent=10136 WHERE bruger_navn='KAN'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('KAN','KAN',10136,'Kjeld Almer','Nielsen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Karin', firmanavn2='Viuf', restricted_shop='n', parent=10136 WHERE bruger_navn='KAV'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('KAV','KAV',10136,'Karin','Viuf','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Klaus', firmanavn2='Bang', restricted_shop='n', parent=10136 WHERE bruger_navn='kba'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('kba','kba',10136,'Klaus','Bang','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Kenneth', firmanavn2='Rasmussen', restricted_shop='n', parent=10136 WHERE bruger_navn='KER'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('KER','KER',10136,'Kenneth','Rasmussen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Kurt F.', firmanavn2='Nielsen', restricted_shop='n', parent=10136 WHERE bruger_navn='KFN'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('KFN','KFN',10136,'Kurt F.','Nielsen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Kent', firmanavn2='Fuglsang', restricted_shop='n', parent=10136 WHERE bruger_navn='KFU'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('KFU','KFU',10136,'Kent','Fuglsang','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Hassan', firmanavn2='Khalid', restricted_shop='n', parent=10136 WHERE bruger_navn='KHA'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('KHA','KHA',10136,'Hassan','Khalid','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Karl Højhus', firmanavn2='Jeppesen', restricted_shop='n', parent=10136 WHERE bruger_navn='KHJ'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('KHJ','KHJ',10136,'Karl Højhus','Jeppesen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Klaus Ising', firmanavn2='Hansen', restricted_shop='n', parent=10136 WHERE bruger_navn='KIH'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('KIH','KIH',10136,'Klaus Ising','Hansen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Kjeld', firmanavn2='Søgaard', restricted_shop='n', parent=10136 WHERE bruger_navn='KJS'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('KJS','KJS',10136,'Kjeld','Søgaard','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Karina', firmanavn2='Laugaard', restricted_shop='n', parent=10136 WHERE bruger_navn='KLA'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('KLA','KLA',10136,'Karina','Laugaard','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Kristine', firmanavn2='Rasmussen', restricted_shop='n', parent=10136 WHERE bruger_navn='kra'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('kra','kra',10136,'Kristine','Rasmussen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Kristen', firmanavn2='Dybdal', restricted_shop='n', parent=10136 WHERE bruger_navn='KRD'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('KRD','KRD',10136,'Kristen','Dybdal','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Lars', firmanavn2='Høier', restricted_shop='n', parent=10136 WHERE bruger_navn='LAH'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('LAH','LAH',10136,'Lars','Høier','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Lone', firmanavn2='Alrik', restricted_shop='n', parent=10136 WHERE bruger_navn='LAL'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('LAL','LAL',10136,'Lone','Alrik','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Lene', firmanavn2='Henriksen', restricted_shop='n', parent=10136 WHERE bruger_navn='LEH'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('LEH','LEH',10136,'Lene','Henriksen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Leif', firmanavn2='Jensen', restricted_shop='n', parent=10136 WHERE bruger_navn='LEJ'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('LEJ','LEJ',10136,'Leif','Jensen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Lars Elkjær', firmanavn2='Nielsen', restricted_shop='n', parent=10136 WHERE bruger_navn='LEN'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('LEN','LEN',10136,'Lars Elkjær','Nielsen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Lars', firmanavn2='Fredskov', restricted_shop='n', parent=10136 WHERE bruger_navn='LFR'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('LFR','LFR',10136,'Lars','Fredskov','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Lisbeth Øxenberg', firmanavn2='Schebye', restricted_shop='n', parent=10136 WHERE bruger_navn='LIS'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('LIS','LIS',10136,'Lisbeth Øxenberg','Schebye','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Lene Lund', firmanavn2='Sørensen', restricted_shop='n', parent=10136 WHERE bruger_navn='LLS'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('LLS','LLS',10136,'Lene Lund','Sørensen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Lene Møller', firmanavn2='Rosenstand', restricted_shop='n', parent=10136 WHERE bruger_navn='LMO'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('LMO','LMO',10136,'Lene Møller','Rosenstand','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Louise', firmanavn2='Larsen', restricted_shop='n', parent=10136 WHERE bruger_navn='LOL'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('LOL','LOL',10136,'Louise','Larsen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Lotte', firmanavn2='Wæver', restricted_shop='n', parent=10136 WHERE bruger_navn='LOW'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('LOW','LOW',10136,'Lotte','Wæver','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Lene', firmanavn2='Jægerslund', restricted_shop='n', parent=10136 WHERE bruger_navn='LRJ'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('LRJ','LRJ',10136,'Lene','Jægerslund','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Lotte Rahbek', firmanavn2='Kamp', restricted_shop='n', parent=10136 WHERE bruger_navn='LRK'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('LRK','LRK',10136,'Lotte Rahbek','Kamp','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Lars Vang', firmanavn2='Hoffmeyer', restricted_shop='n', parent=10136 WHERE bruger_navn='LVH'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('LVH','LVH',10136,'Lars Vang','Hoffmeyer','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Louise Westy', firmanavn2='Andersen', restricted_shop='n', parent=10136 WHERE bruger_navn='lwa'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('lwa','lwa',10136,'Louise Westy','Andersen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Laila Wulff', firmanavn2='Nielsen', restricted_shop='n', parent=10136 WHERE bruger_navn='LWN'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('LWN','LWN',10136,'Laila Wulff','Nielsen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Morten', firmanavn2='Alsdorf', restricted_shop='n', parent=10136 WHERE bruger_navn='MAF'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('MAF','MAF',10136,'Morten','Alsdorf','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Maria', firmanavn2='Hyldahl', restricted_shop='n', parent=10136 WHERE bruger_navn='mah'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('mah','mah',10136,'Maria','Hyldahl','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Mette Alkjær', firmanavn2='Larsen', restricted_shop='n', parent=10136 WHERE bruger_navn='MAL'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('MAL','MAL',10136,'Mette Alkjær','Larsen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Morten', firmanavn2='Andersson', restricted_shop='n', parent=10136 WHERE bruger_navn='MAN'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('MAN','MAN',10136,'Morten','Andersson','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Marianne', firmanavn2='Bentsen', restricted_shop='n', parent=10136 WHERE bruger_navn='MBE'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('MBE','MBE',10136,'Marianne','Bentsen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Mette', firmanavn2='Clausen', restricted_shop='n', parent=10136 WHERE bruger_navn='MCL'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('MCL','MCL',10136,'Mette','Clausen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Marie-Louise Deth', firmanavn2='Petersen', restricted_shop='n', parent=10136 WHERE bruger_navn='MDP'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('MDP','MDP',10136,'Marie-Louise Deth','Petersen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Mette', firmanavn2='Høgfeldt', restricted_shop='n', parent=10136 WHERE bruger_navn='MEH'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('MEH','MEH',10136,'Mette','Høgfeldt','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Mette', firmanavn2='Sattrup', restricted_shop='n', parent=10136 WHERE bruger_navn='MES'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('MES','MES',10136,'Mette','Sattrup','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Mette', firmanavn2='Øbro', restricted_shop='n', parent=10136 WHERE bruger_navn='MET'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('MET','MET',10136,'Mette','Øbro','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Marie', firmanavn2='Frederiksen', restricted_shop='n', parent=10136 WHERE bruger_navn='mfr'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('mfr','mfr',10136,'Marie','Frederiksen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Martin Gamfeldt', firmanavn2='Michelsen', restricted_shop='n', parent=10136 WHERE bruger_navn='MGM'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('MGM','MGM',10136,'Martin Gamfeldt','Michelsen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Mogens', firmanavn2='Hansen', restricted_shop='n', parent=10136 WHERE bruger_navn='MHA'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('MHA','MHA',10136,'Mogens','Hansen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Michael H.', firmanavn2='Nielsen', restricted_shop='n', parent=10136 WHERE bruger_navn='MHN'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('MHN','MHN',10136,'Michael H.','Nielsen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Marian', firmanavn2='Hoffensetz', restricted_shop='n', parent=10136 WHERE bruger_navn='MHO'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('MHO','MHO',10136,'Marian','Hoffensetz','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Martin K.I.', firmanavn2='Christensen', restricted_shop='n', parent=10136 WHERE bruger_navn='MIC'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('MIC','MIC',10136,'Martin K.I.','Christensen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Marianne', firmanavn2='Kromand', restricted_shop='n', parent=10136 WHERE bruger_navn='MKR'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('MKR','MKR',10136,'Marianne','Kromand','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Marie-Louise', firmanavn2='Paludan', restricted_shop='n', parent=10136 WHERE bruger_navn='MLP'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('MLP','MLP',10136,'Marie-Louise','Paludan','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Mogens', firmanavn2='Lynggaard', restricted_shop='n', parent=10136 WHERE bruger_navn='MLY'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('MLY','MLY',10136,'Mogens','Lynggaard','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Mette Møller', firmanavn2='Nielsen', restricted_shop='n', parent=10136 WHERE bruger_navn='mmn'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('mmn','mmn',10136,'Mette Møller','Nielsen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Mogens', firmanavn2='Albjerg', restricted_shop='n', parent=10136 WHERE bruger_navn='MOA'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('MOA','MOA',10136,'Mogens','Albjerg','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Mogens', firmanavn2='Hjelm', restricted_shop='n', parent=10136 WHERE bruger_navn='MOH'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('MOH','MOH',10136,'Mogens','Hjelm','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Morten Peter', firmanavn2='Nielsen', restricted_shop='n', parent=10136 WHERE bruger_navn='MPN'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('MPN','MPN',10136,'Morten Peter','Nielsen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Martin Riget', firmanavn2='Nielsen', restricted_shop='n', parent=10136 WHERE bruger_navn='MRN'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('MRN','MRN',10136,'Martin Riget','Nielsen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Mogens Rold', firmanavn2='Sørensen', restricted_shop='n', parent=10136 WHERE bruger_navn='MRS'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('MRS','MRS',10136,'Mogens Rold','Sørensen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Mette', firmanavn2='Schmidt', restricted_shop='n', parent=10136 WHERE bruger_navn='msc'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('msc','msc',10136,'Mette','Schmidt','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Nicholas', firmanavn2='Breuning', restricted_shop='n', parent=10136 WHERE bruger_navn='NBR'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('NBR','NBR',10136,'Nicholas','Breuning','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Nicolaj', firmanavn2='Christensen', restricted_shop='n', parent=10136 WHERE bruger_navn='NIC'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('NIC','NIC',10136,'Nicolaj','Christensen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Niels', firmanavn2='Strange', restricted_shop='n', parent=10136 WHERE bruger_navn='NST'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('NST','NST',10136,'Niels','Strange','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Ove', firmanavn2='Hillersborg', restricted_shop='n', parent=10136 WHERE bruger_navn='OBH'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('OBH','OBH',10136,'Ove','Hillersborg','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Ole Bondo', firmanavn2='Jensen', restricted_shop='n', parent=10136 WHERE bruger_navn='OBJ'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('OBJ','OBJ',10136,'Ole Bondo','Jensen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Ove Hoe', firmanavn2='Andersen', restricted_shop='n', parent=10136 WHERE bruger_navn='oha'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('oha','oha',10136,'Ove Hoe','Andersen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Ole Schack', firmanavn2='Petersen', restricted_shop='n', parent=10136 WHERE bruger_navn='OSP'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('OSP','OSP',10136,'Ole Schack','Petersen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Ole', firmanavn2='Thaarup', restricted_shop='n', parent=10136 WHERE bruger_navn='OTH'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('OTH','OTH',10136,'Ole','Thaarup','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Per Bjerregaard', firmanavn2='Jepsen', restricted_shop='n', parent=10136 WHERE bruger_navn='PBJ'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('PBJ','PBJ',10136,'Per Bjerregaard','Jepsen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Poul', firmanavn2='Brincker', restricted_shop='n', parent=10136 WHERE bruger_navn='PBR'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('PBR','PBR',10136,'Poul','Brincker','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Per Dahlin', firmanavn2='Larsen', restricted_shop='n', parent=10136 WHERE bruger_navn='PDL'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('PDL','PDL',10136,'Per Dahlin','Larsen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Poul Erik', firmanavn2='Henriksen', restricted_shop='n', parent=10136 WHERE bruger_navn='PEH'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('PEH','PEH',10136,'Poul Erik','Henriksen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Poul-Erik', firmanavn2='Nielsen', restricted_shop='n', parent=10136 WHERE bruger_navn='PEN'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('PEN','PEN',10136,'Poul-Erik','Nielsen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Palle', firmanavn2='Eriksen', restricted_shop='n', parent=10136 WHERE bruger_navn='PER'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('PER','PER',10136,'Palle','Eriksen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Petter', firmanavn2='Astrup', restricted_shop='n', parent=10136 WHERE bruger_navn='PET'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('PET','PET',10136,'Petter','Astrup','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Peter Glud', firmanavn2='Falborg', restricted_shop='n', parent=10136 WHERE bruger_navn='PGF'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('PGF','PGF',10136,'Peter Glud','Falborg','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Palle Hørlyck', firmanavn2='Jacobsen', restricted_shop='n', parent=10136 WHERE bruger_navn='PHJ'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('PHJ','PHJ',10136,'Palle Hørlyck','Jacobsen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Preben Ries', firmanavn2='Nielsen', restricted_shop='n', parent=10136 WHERE bruger_navn='PNI'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('PNI','PNI',10136,'Preben Ries','Nielsen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Peder Nørskov', firmanavn2='Madsen', restricted_shop='n', parent=10136 WHERE bruger_navn='PNM'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('PNM','PNM',10136,'Peder Nørskov','Madsen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Poul Erik', firmanavn2='Hjorth', restricted_shop='n', parent=10136 WHERE bruger_navn='POH'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('POH','POH',10136,'Poul Erik','Hjorth','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Preben', firmanavn2='Tang', restricted_shop='n', parent=10136 WHERE bruger_navn='PTA'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('PTA','PTA',10136,'Preben','Tang','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Preben', firmanavn2='Hankelbjerg', restricted_shop='n', parent=10136 WHERE bruger_navn='PVH'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('PVH','PVH',10136,'Preben','Hankelbjerg','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Rita Dorst', firmanavn2='Christensen', restricted_shop='n', parent=10136 WHERE bruger_navn='RDC'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('RDC','RDC',10136,'Rita Dorst','Christensen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Søren', firmanavn2='Bech', restricted_shop='n', parent=10136 WHERE bruger_navn='SBE'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('SBE','SBE',10136,'Søren','Bech','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Steen Byskov', firmanavn2='Petersen', restricted_shop='n', parent=10136 WHERE bruger_navn='SBP'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('SBP','SBP',10136,'Steen Byskov','Petersen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Sylvia', firmanavn2='Christensen', restricted_shop='n', parent=10136 WHERE bruger_navn='SCH'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('SCH','SCH',10136,'Sylvia','Christensen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Anna', firmanavn2='Schmidt', restricted_shop='n', parent=10136 WHERE bruger_navn='SCH'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('SCH','SCH',10136,'Anna','Schmidt','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Søren', firmanavn2='Gadtoft', restricted_shop='n', parent=10136 WHERE bruger_navn='SGA'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('SGA','SGA',10136,'Søren','Gadtoft','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Sonja', firmanavn2='Hjorth', restricted_shop='n', parent=10136 WHERE bruger_navn='SHI'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('SHI','SHI',10136,'Sonja','Hjorth','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Søren Hoffmann', firmanavn2='Sørensen', restricted_shop='n', parent=10136 WHERE bruger_navn='SHS'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('SHS','SHS',10136,'Søren Hoffmann','Sørensen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Søren Lund', firmanavn2='Hansen', restricted_shop='n', parent=10136 WHERE bruger_navn='SLH'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('SLH','SLH',10136,'Søren Lund','Hansen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Susanne', firmanavn2='Seroff', restricted_shop='n', parent=10136 WHERE bruger_navn='SSE'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('SSE','SSE',10136,'Susanne','Seroff','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Søren Skibber', firmanavn2='Hansen', restricted_shop='n', parent=10136 WHERE bruger_navn='SSH'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('SSH','SSH',10136,'Søren Skibber','Hansen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Susanne', firmanavn2='Wallin', restricted_shop='n', parent=10136 WHERE bruger_navn='SWA'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('SWA','SWA',10136,'Susanne','Wallin','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Stine Zeeberg', firmanavn2='Kristiansen', restricted_shop='n', parent=10136 WHERE bruger_navn='SZK'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('SZK','SZK',10136,'Stine Zeeberg','Kristiansen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Torben', firmanavn2='Theill', restricted_shop='n', parent=10136 WHERE bruger_navn='TIT'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('TIT','TIT',10136,'Torben','Theill','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Torben', firmanavn2='Klitgaard', restricted_shop='n', parent=10136 WHERE bruger_navn='TKL'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('TKL','TKL',10136,'Torben','Klitgaard','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Tove', firmanavn2='Palnum', restricted_shop='n', parent=10136 WHERE bruger_navn='TPA'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('TPA','TPA',10136,'Tove','Palnum','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Thorsten', firmanavn2='Wilstrup', restricted_shop='n', parent=10136 WHERE bruger_navn='TWI'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('TWI','TWI',10136,'Thorsten','Wilstrup','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Vibeke', firmanavn2='Gaardsholt', restricted_shop='n', parent=10136 WHERE bruger_navn='VGA'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('VGA','VGA',10136,'Vibeke','Gaardsholt','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Winnie', firmanavn2='Schütt', restricted_shop='n', parent=10136 WHERE bruger_navn='WSC'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('WSC','WSC',10136,'Winnie','Schütt','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Anette', firmanavn2='Alrik', restricted_shop='n', parent=10136 WHERE bruger_navn='AAL'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('AAL','AAL',10136,'Anette','Alrik','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        

        $sql =" UPDATE dev_bruger SET firmanavn1='Anette', firmanavn2='Amundsen', restricted_shop='n', parent=10136 WHERE bruger_navn='AAM'";
        $dba->exec($sql);
        if(!$dba->affectedRows())
        {
          $sql="INSERT INTO dev_bruger (bruger_navn,password,parent,firmanavn1,firmanavn2,gratist) VALUES ('AAM','AAM',10136,'Anette','Amundsen','y')";
          $dba->exec($sql);
          echo $sql."<br>";
        }
        else
        {
          echo $sql."<br>";
        }
        
?>