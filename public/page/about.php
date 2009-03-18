<?php
class About extends View
{
  var $menu;
  function About()
  {
    $this->menu = array( 
                         'choose'=>'V&aelig;lg fag',
                         'building'=>'Bygningsdel',
                         'bib'=>'Bibliotek',
                         'search'=>'S&oslash;gning',
                         'ext_search'=>'Udvidet s&oslash;gning',
                         'results'=>'S&oslash;geresultater',
                         'pay'=>'Betaling',
                         'minside'=>'Personlige indstillinger',
                         'log'=>'Log in'
                        );
    $items = array('copy'=>'Copyright',
                   'ansvar'=>'Ansvarsfraskrivelse',
                   'kontakt'=>'Kontakt',
                   'om'=>'Om bygviden.dk',
                   'help'=>'Hj&aelig;lp');
  $this->headLine = $items[$this->getCurrentItem()];
  }

  function LeftMenu()
  {
    if($this->getCurrentItem() != 'help') return;
    $str = '<div id="navitree">';
    foreach($this->menu as $k=>$v)
    {
      $str.='<div class="node">';
      if($_GET['id']==$k) $str.='<strong>';
      $str.='<a href="index.php?action=about&item='.$this->getCurrentItem().'&id='.$k.'">'.$v.'</a>';
      if($_GET['id']==$k) $str.='</strong>';
      $str.='</div>';
    }
    return $str.'</div>';
  }
  
  function getCurrentItem()
  {
    if($_REQUEST['item']) return $_REQUEST['item'];
    return 'om';
  }
  function copyRight()
  {
    return '<table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td>
                  Ved at overholde nedenst&aring;ende regler i forbindelse med copyright og 
                  ophavsrettigheder sikrer du, at du altid har adgang til ny og opdateret 
                  viden og erfaring fra bygviden.dk. Og du er med til at bevare bygviden.dk 
                  som en attraktiv Internet-side &#8211; ogs&aring; for de leverand&oslash;rer 
                  af viden og erfaring, der stiller deres materiale til r&aring;dighed for 
                  dig via bygviden.dk.<br> <br> <strong>Regler</strong><br>
                  Brugerens adgang til materialet er at betragte som en personlig adgang, 
                  der ikke m&aring; videregives til andre enkeltpersoner eller virksomheder. 
                  <p><strong>Elektronisk anvendelse</strong><br>
                    Tekst og illustrationer fra bygviden.dk kan udskrives til eget brug og 
                    til internt brug eller distribution i din virksomhed. Det er ikke tilladt 
                    at downloade materialet til din harddisk &#8211; hverken opdelt i enkelte 
                    afsnit eller i den fuldst&aelig;ndige udgave. Det er heller ikke tilladt 
                    at lagre materialet elektronisk i f&aelig;lles arkiver i virksomheden. 
                    Det er s&aring;ledes ikke tilladt under nogen form at foretage elektronisk 
                    kopiering af materialet.</p>
                  <p><strong>Mekanisk anvendelse</strong><br>
                    Med hensyn til mekanisk kopiering henvises til de almindeligt g&aelig;ldende 
                    bestemmelser for ophavsrettigheder.</p>
                  <p>Tekst og illustrationer m&aring; s&aring;ledes ikke distribueres til 
                    personer eller virksomheder uden for din virksomhed. Materialet m&aring; 
                    ligeledes ikke distribueres i forbindelse med undervisning eller foredrag.</p>
                  <p>Det er s&aring;ledes heller ikke tilladt at kopiere, udgive eller s&aelig;lge 
                    materialet hverken i erhvervsm&aelig;ssigt eller privat &oslash;jemed 
                    uden forudg&aring;ende skriftlig aftale fra udgiveren.</p>
                </td>
              </tr>
            </table>';
  }
  function ansvar()
  {
    return '
            <strong>Ansvarsfraskrivelse</strong>
            <p>
              Bygviden.dk er en portal, der g&oslash;r byggeteknisk viden p&aring; en r&aelig;kke 
              danske internet-sites s&oslash;gbar og tilg&aelig;ngelig.
              <a href="http://www.danskbyggeri.dk" target="_blank">Dansk Byggeri</a>  ejer 
              og driver portalen, men producerer ikke selv den information, der findes her. 
              Materiale, der er tilg&aelig;ngeligt via bygviden.dk, stilles derfor til r&aring;dighed som det er og forefindes p&aring; nettet.
            </p>
            <p>
              <a href="http://www.danskbyggeri.dk" target="_blank">Dansk Byggeri</a> kan ikke stilles 
              til ansvar for indhold (herunder struktur og sammenh&aelig;ng) 
              eller slutbrugerens anvendelse af oplysninger, der er hentet via bygviden.dk.
            </p>';

  }
  function kontakt()
  {
      return '
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr> 
                  <td valign="top" class="plainText"><strong>Bygviden.dk</strong><br>
                    Dansk Byggeri<br>
                    Postboks 2125<br>
                    1015 K&oslash;benhavn K 
                    <p>E-mail: <a href="mailto:bygviden@danskbyggeri.dk">bygviden@danskbyggeri.dk</a><br>
                      Tlf. 72 16 00 00<br>
                      Fax: 72 16 00 10</p>
                    <p>Kontaktperson:<br>
                      Rikke M&oslash;ller<br>
                      Tlf. 72 16 01 96<br>
                    </p>
                    <p><strong>Annoncer</strong><br>
                    Kontaktperson:<br>
                    Jan Hesselberg<br>
                    Tlf. 72 16 01 39
                    </p>
                     
                  </td>
                  <td width="15">&nbsp;</td>
                  <td align="left" valign="top" class="plainText">
                  <strong>Servicekontor 
                      N&oslash;rresundby </strong><br>
                      Tlf.: 72 16 00 00

                    <p><strong>Servicekontor Herning</strong><br>
                      Tlf.: 72 16 00 00</p>
                    <p><strong>Servicekontor &Aring;rhus</strong><br>
                      Tlf.: 72 16 00 00</p>
                    <p><strong>Servicekontor Vojens</strong><br>
                      Tlf.: 72 16 00 00</p>
                    <p><strong>Servicekontor Odense</strong><br>
                      Tlf.: 72 16 00 00</p>
                    <p><strong>Servicekontor H&oslash;je T&aring;strup</strong><br>
                      Tel.: 72 16 00 00</p></td>
                </tr>
              </table>';
  }
  function omOs()
  {
    return '
      <a name="top">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td><strong>Bygviden.dk er s&oslash;gemaskinen 
            for byggeteknisk viden i Danmark.</strong> 
            <p>Videnportalen giver dig 
              adgang til informationer, du ikke finder med de generelle s&oslash;gev&aelig;rkt&oslash;jer 
              som Google, Yahoo eller Jubii.</p>
            <p>Der findes masser af teknisk viden p&aring; bygge-omr&aring;det, 
              men enten har det hidtil ikke v&aelig;ret tilg&aelig;ngeligt i digital 
              form eller ogs&aring; har det ikke v&aelig;ret s&oslash;gbart. Viden-udbydere 
              som forskningscentre, brancheorganisationer, offentlige myndigheder 
              og producenter har arbejdet ukoordineret, og det har derfor v&aelig;ret 
              sv&aelig;rt at f&aring; overblik over det store udbud af teknisk 
              viden. Med bygviden.dk har branchen f&aring;et en samlet og s&oslash;gbar 
              indgang til denne viden.</p>

            <p>P&aring; bygviden.dk beh&oslash;ver du ikke downloade en rapport 
              p&aring; hundrede sider; du s&oslash;ger dig i stedet direkte frem 
              til det afsnit, du har brug for. Hvis dokumentet koster penge, betaler 
              du kun for den del, du skal bruge. Det koster ofte ikke mere end 
              en krone.<br>
              <br>
              <a href="#top">Til top</a> </p></td>
        </tr>
      </table>
      <br> 
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td class="label"><a name="2"></a><strong>Hvem st&aring;r bag bygviden.dk?</strong></td>
        </tr>
        <tr> 
          <td class="plainText"><span class="overskriftRed">Dansk Byggeri ejer 
            og driver bygviden.dk.</span> <p>Videnportalen blev skabt, da tre 
              arbejdsgrupper i BYG\'s Maling- og overfladebehandlingssektion, Tr&aelig;sektion 
              samt Murer- og Entrepren&oslash;rsektion satte fokus p&aring; byggeteknisk 
              viden og konkluderede, at selvom der er masser af information p&aring; 
              omr&aring;det, er den sv&aelig;r at finde &#8211; is&aelig;r via 
              nettet.</p>

            <p>Dansk Byggeri samarbejder nu med branchens videnproducenter om 
              at g&oslash;re information om bygge- og procesteknik tilg&aelig;ngelig 
              og s&oslash;gbar via bygviden.dk.<br>
              <br>
              <a href="#top">Til top</a> </p></td>
        </tr>
      </table>
      <br> <table width="100%" border="0" cellspacing="0" cellpadding="0">

        <tr> 
          <td class="label"><a name="3"></a><strong>Form&aring;let med bygviden.dk</strong></td>
        </tr>
        <tr> 
          <td class="plainText"><span class="overskriftRed">Bygviden.dk vil gennem 
            formidling af byggeteknisk viden bidrage til bedre byggeri.</span> 
            <p>M&aring;let skal opfyldes ved at &oslash;ge tilg&aelig;ngeligheden 
              af eksisterende viden om materialer, konstruktioner og udf&oslash;relse 
              inden for t&oslash;mrer-, snedker-, maler- murer- og entrepren&oslash;rbranchen.</p>

            <p>Den samlede viden t&aelig;nkes f&oslash;rst og fremmest anvendt 
              af byggerivirksomheder og deres ansatte i konkrete situationer.<br>
              <br>
              <a href="#top">Til top</a> </p></td>
        </tr>
      </table>
      <br> <table width="100%" border="0" cellspacing="0" cellpadding="0">

        <tr> 
          <td class="label"><a name="4"></a><strong>Teknikken bag bygviden.dk</strong></td>
        </tr>
        <tr> 
          <td class="plainText"><span class="overskriftRed">Bygviden.dk bygger 
            p&aring; avanceret s&oslash;geteknologi, leveret af Autonomy&reg;.</span> 
            <p>S&oslash;gemaskinen Autonomy&reg; er ikke bare en \'dum\' s&oslash;gerobot, 
              men en intelligent agent. Dvs. at s&oslash;gemaskinen er i stand 
              til at l&aelig;re, hvordan et godt dokument om eksempelvis tagkonstruktion 
              ser ud.</p>

            <p>Bygviden.dk\'s redakt&oslash;rer og fagmedarbejdere kan pege p&aring; 
              et ideelt dokument, og den digitale agent fors&oslash;ger derefter 
              at genkende tekstm&oslash;nstret fra dokumentet i de websider og 
              databaser, den gennems&oslash;ger p&aring; nettet.</p>
            <p>Som bruger kan du selv pege p&aring; et \'ideelt\' dokument i et 
              hvilket som helst s&oslash;geresultat. Autonomy&reg; vil derefter 
              ikke blot lede dig hen til dokumentet, men ogs&aring; vise dig en 
              oversigt over besl&aelig;gtede sider, du m&aring;ske ogs&aring; 
              er interesseret i.</p>

            <p>S&oslash;geindekset hos bygviden.dk opdateres dagligt.<br>
              <br>
              <a href="#top">Til top</a></p></td>
        </tr>
      </table>
      <br> <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td class="label"><a name="5"></a><strong>Informationsudbydere</strong></td>

        </tr>
        <tr> 
          <td class="plainText"><span class="overskriftRed">Bygviden.dk har truffet 
            aftale med informationsudbydere p&aring; det byggetekniske omr&aring;de 
            om digital videreformidling af deres viden.</span><br> <br>
            F&oslash;lgende instansers viden er tilg&aelig;ngelig via bygviden.dk: 
            <p>&middot; Betonelementforeningen<br>

              &middot; BPS (Byggeri, Produktivitet, Samarbejde)<br>
              &middot; Brandteknisk Institut<br>
              &middot; BYG-ERFA<br>
              &middot; CTO (Cementfabrikkernes Tekniske Oplysningskontor)<br>
              &middot; Dansk Standard<br>

              &middot; Kalk- og Teglv&aelig;rksforeningen af 1893 (Tek. Ins., 
              Murv&aelig;rkscentret)<br>
              &middot; Murerfagets Oplysningsr&aring;d<br>
              &middot; Retsinfo<br>
              &middot; Statens Byggeforskningsinstitut<br>

              &middot; Tagpapbranchens Oplysningsr&aring;d<br>
              &middot; Teknologisk Institut &#8211; tr&aelig;teknik<br>
              &middot; Telnologisk Institut &#8211; Malerfagligt BehandlingsKatalog<br>

              &middot; Tr&aelig;branchens Oplysningsr&aring;d</p>
            <p>Ud over byggeteknisk viden indekserer bygviden.dk en r&aelig;kke 
              leverand&oslash;rer og producenters websites, heriblandt &#8230;</p>
            <p>&middot; Danogips a/s<br>
              &middot; Icopal a/s<br>

              &middot; ITW Construction Products<br>
              &middot; Palsgaardsgruppen<br>
              &middot; Rockwool a/s<br>
              <br>
              <a href="#top">Til top</a> </p></td>
            </tr>
          </table>';

  }
  function choose()
  {
    return '
            <strong>V&aelig;lg fag</strong>
            <p>N&aring;r du f&oslash;rste gang g&aring;r ind p&aring; bygviden.dk, kan 
              du <a href="index.php?action=valgfag">v&aelig;lge et fag</a>. </p>
            <p>Klik i et af de runde felter ud for fagene, og klik derefter p&aring; 
              knappen \'V&aelig;lg\'.</p>
            <p>Der er fem fag at v&aelig;lge imellem: \'Tr&aelig;fagene\', \'Murerfaget\', 
              \'Strukt&oslash;rer\', \'Malerfaget\' og \'Kloakfaget\'. Du kan ogs&aring; v&aelig;lge indstillingen 
              \'Alle fag\'.</p>
            <p>N&aelig;ste gang, du bes&oslash;ger os fra den samme computer, husker 
              maskinen dit valg. N&aring;r du har valgt fag, f&aring;r du kun pr&aelig;senteret 
              det indhold, der er relevant for dig.</p>
            <p>N&aring;r du har valgt fag p&aring; forsiden, bliver du pr&aelig;senteret 
              for en menu, der viser forskellige <a href="index.php?action=bygningsdel">bygningselementer</a>.<br>
            </p>';
  }
  function building()
  {
    return '
            <strong>Bygningsdel</strong>
            <p>N&aring;r du har <a href="index.php?action=about&item=help&id=choose">valgt et 
              fag</a> p&aring; forsiden af bygviden.dk, f&aring;r du en oversigt over 
              bygningselementer i venstre side af sk&aelig;rmen. Du kan altid komme 
              til oversigten ved at klikke p&aring; \'<a href="index.php?action=bygningsdel">Bygningselementer</a>\' 
              i topnavigationen. </p>
            <p>Klik p&aring; plus-tegnet ud for et af menupunkterne (\'Tag\', \'Yderv&aelig;gge\' 
              eller \'V&aring;drum\' ...) for at &aring;bne menuen. Du ser nu en r&aelig;kke 
              underemner. Nogle af dem kan &aring;bnes ved at klikke p&aring; plus-tegnet. 
              Klik p&aring; et af n&oslash;gleordene, s&aring; f&aring;r du en oversigt 
              over dokumenter om emnet, som bygviden.dk har indekseret. Oversigten vises 
              som et overskueligt <a href="index.php?action=about&item=help&id=results">s&oslash;geresultat</a>.</p>
            <p>Du kan ogs&aring; finde det, du leder efter, ved hj&aelig;lp af bygviden.dk\'s 
              \'<a href="/index.php?action=about&item=help&id=bib">Bibliotek</a>\' eller 
              ved at <a href="index.php?action=about&item=help&id=search">s&oslash;ge</a> 
              dig frem.</p>
           ';
  }
  function bibliotek()
  {
    return '
            <strong>Bibliotek</strong>
            <p>Dokumenterne p&aring; bygviden.dk er under menupunktet \'<a href="index.php?action=about&item=help&id=building">Bygningselementer</a>\' 
              sorteret efter emne. I \'<a href="index.php?action=bibliotek">Biblioteket</a>\' 
              er dokumenterne sorteret efter udgiver.</p>
            <p>Siden best&aring;r af en liste over informationsudbydere, der leverer 
              til bygviden.dk. Klik p&aring; plus-tegnet ud for udbyderens navn for 
              at f&aring; en liste over dokumenter herfra.</p>
            <p>V&eacute;d du p&aring; forh&aring;nd, hvilket dokument du leder efter, 
              er biblioteket ofte den hurtigste m&aring;de at finde det p&aring;, fordi 
              biblioteket giver den mest eksakte indgang. Det bredeste overblik over 
              dokumenter f&aring;r du ved at <a href="index.php?action=about&item=help&id=search">s&oslash;ge</a> 
              dig frem.<br>
            </p>'; 
  }
  function search()
  {
    return '
            <strong>S&oslash;gning</strong>
            <p>S&oslash;gning p&aring; bygviden.dk giver ikke altid det samme resultat, 
              som n&aring;r du klikker dig frem via \'<a href="index.php?action=about&item=help&id=building">Bygningselementer</a>\'. 
            </p>
            <p>Klikker du dig frem, f&aring;r du resultater, som er kontrolleret af 
              bygviden.dk\'s redakt&oslash;r og vore fagmedarbejdere, mens du f&aring;r 
              et mere \'r&aring;t\' resultat, n&aring;r du selv indtaster s&oslash;geord.</p>
            <p>Vi anbefaler, at du starter med at klikke dig frem. Finder du ikke det, 
              du leder efter, kan du indtaste n&oslash;gleord i bygviden.dk\'s s&oslash;gemaskine, 
              som du finder under menupunktet \'<a href="index.php?action=search">S&oslash;g</a>\'.</p>
            <p>Skriv dit s&oslash;geord, og klik p&aring; en af de to s&oslash;geknapper. 
              Den ene s&oslash;geknap starter en s&oslash;gning inden for det fag, du 
              har valgt; den anden starter en s&oslash;gning i alle fag.</p>
            <p>Du kan opn&aring; et mere pr&aelig;cist resultat af din s&oslash;gning 
              ved at benytte \'<a href="index.php?action=about&item=help&id=ext_search">Udvidet 
              s&oslash;gning</a>\'.</p>
            ';
  }
  function ext_search()
  {
    return '
            <strong>Udvidet s&oslash;gning</strong>
            <p>Med \'<a href="index.php?action=search&search=2">Udvidet s&oslash;gning</a>\' 
              kan du g&oslash;re din s&oslash;gning mere pr&aelig;cis. </p>
            <p>Hvis du vil have en oversigt over diverse krav til f.eks. gittersp&aelig;r, 
              kan du med udvidet s&oslash;gning tilv&aelig;lge checkboksene \'Lovkrav\' 
              og \'Tekniske anvisninger\', mens du lader \'Producenter\' og \'Andet materiale\' 
              v&aelig;re uden markering.</p>
            <p>Du kan begr&aelig;nse s&oslash;gningen til &eacute;t eller flere fag, 
              fx \'Tr&aelig;fagene\' og \'Strukt&oslash;rer\'.</p>
            <p>Med udvidet s&oslash;gning sikrer du, at du f&aring;r et relevant 
              <a href="index.php?action=about&item=help&id=results">s&oslash;geresultatet</a>.<br>
            </p>'; 
  }
  function results()
  {
    return '
            <strong>S&oslash;geresultater</strong>
            <p>Bygviden.dk\'s s&oslash;geresultat er langt mere informativt end dem, 
              du kender fra generelle s&oslash;geredskaber som Google og Jubii. </p>
            <p>Du kan se, hvad dokumentet hedder, hvilken publikation det kommer fra, 
              hvilken type (kategori) det tilh&oslash;rer, og hvem der har udgivet det. 
              Du f&aring;r ogs&aring; et kort resum&eacute; af dokumentet.</p>
            <p>S&oslash;geresultaterne er inddelt i op til syv kategorier:<br>
              <br>
              &#8226; \'Gode r&aring;d\'<br>
              &#8226; \'Tekniske anvisninger\'<br>
              &#8226; \'Supplerende tekniske krav\'<br>
              &#8226; \'BYG-ERFA\' (erfaringer)<br>
              &#8226; \'Producenter\'<br>
              &#8226; \'Lovkrav\' og<br>
              &#8226; \'Andet materiale\'</p>
            <p>Hvis du ikke ser alle kategorierne i et s&oslash;geresultat, skyldes 
              det, at bygviden.dk ikke har fundet nogen dokumenter i kategorien.</p>
            <p>Hvis publikationen kan f&aring;s p&aring; tryk fra <a href="http://www.bygforlag.dk" target="_blank">Dansk 
              Byggeris Forlag</a>, er det markeret med et bog-ikon.</p>
            <p>Nogle dokumenter i bygviden.dk\'s s&oslash;geresultater kan kun f&aring;s 
              mod <a href="index.php?action=about&item=help&id=pay">betaling</a>. I s&aring; 
              fald er de markeret med et l&aring;s-ikon. (Kun medlemmer af <a href="http://www.danskbyggeri.dk" target="_blank">Dansk 
              Byggeri</a> kan k&oslash;be dokumenter fra bygviden.dk).</p>


           ';
  }
  function pay()
  {
    return '
            <strong>Betaling</strong>
            <p>Nogle dokumenter p&aring; bygviden.dk koster penge. </p>
            <p>Kun medlemmer af <a href="http://www.danskbyggeri.dk" target="_blank">Dansk Byggeri</a> 
              kan k&oslash;be disse s&aelig;rlige dokumenter via bygviden.dk. Gebyret 
              d&aelig;kker den pris, bygviden.dk betaler informationsudbyderen.</p>
            <p>De dokumenter, der koster penge, er markeret med et penge-ikon i s&oslash;geresultatet. 
              Klik p&aring; dokumentets titel eller ikonet for at se, hvad det koster.</p>
            <p>For at kunne k&oslash;be dokumenter fra bygviden.dk skal du have et brugernavn 
              og password, og du skal v&aelig;re logget ind. </p>
            <p>Bygviden.dk holder styr p&aring; dit forbrug, og du f&aring;r en regning 
              tilsendt en gang i kvartalet. Hvis flere medarbejdere i dit firma k&oslash;ber 
              dokumenter p&aring; bygviden.dk, f&aring;r du en samlet regning med overblik 
              over hver enkelt medarbejders forbrug.</p>
            <p>Du kan holde &oslash;je med dit forbrug via \'Forbrug\' under
              \'<a href="index.php?action=about&item=help&id=minside">Personlig indstillinger</a>\'.</p>
           ';
  }
  function minside()
  {
    return '
            <strong>Personlig indstillinger</strong>
            <p>Du skal v&aelig;re logget 
              ind med brugernavn og password for at f&aring;r adgang til <br>\'Personlig indstillinger\'.
              
              N&aring;r du er logget ind vil kommer der som et menu punkt i h&oslash;jre kolonne.<br>
            </p>
            <p>Her kan du holde 
              styr p&aring; dit k&oslash;b af betalingsdokumenter p&aring; bygviden.dk. 
              Du kan ogs&aring; tilmelde flere brugere i dit firma eller &aelig;ndre 
              din adgangskode. </p>
            <p>Du har fem muligheder p&aring; \'Personlig indstillinger\':<br>
              <br>
              &#8226; Indstillinger: Se kontaktoplysninger og &aelig;ndre dit password.<br>
              &#8226; Brugerstyring: Opret ny bruger, f.eks. en medarbejder eller kollega. 
              Du f&aring;r en samlet regning for alle de brugere, du tilmelder, men 
              du vil kunne se hver enkelts forbrug.<br>
              &#8226; Forbrug: Oversigt over dit forbrug i indev&aelig;rende kvartal, 
              som ikke er faktureret endnu.<br>
              &#8226; Faktureret forbrug: Dit forbrug i forrige kvartal, som du har 
              f&aring;et regning p&aring;.<br>
           ';
  }
  function login()
  {
    return '
            <strong>Log in</strong>
            <p>Du kan til enhver tid logger ind p&aring; bygviden.dk. Indtast din bruger navn og adgangskode i felterne  
               i h&oslash;jre kolonne og tryk p&aring; \'Log ind\' knappen. 
            </p>
            <p>Du skal v&aelig;re logget ind p&aring; bygviden.dk for at kunne 
              <a href="index.php?action=about&item=help&id=pay">k&oslash;be 
              dokumenter</a> og for at tilse dine  
              \'<a href="index.php?action=about&item=help&id=minside">Personlig indstillinger</a>\'.</p>
            <p>Kun medlemmer af <a href="http://www.danskbyggeri.dk" target="_blank">Dansk Byggeri</a> 
              kan k&oslash;be dokumenter fra bygviden.dk.<br>
            </p>'; 
  }
  function helpSection()
  {
    switch($_GET['id'])
    {
      case('choose'): return $this->choose();
      case('building'): return $this->building();
      case('bib'): return $this->bibliotek();
      case('search'): return $this->search();
      case('ext_search'): return $this->ext_search();
      case('results'): return $this->results();
      case('pay'): return $this->pay();
      case('minside'): return $this->minside();
      case('log'): return $this->login();
    }
  }
  function help()
  {
    if($_GET['id']) return $this->helpSection(); 
    return '
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td valign="top">
                  <a href="index.php?action=about&item=help&id=choose">Vælg fag</a><br>
                  Den nemme indgang til bygviden.dk
                  <br>
                  <br>
                  <a href="index.php?action=about&item=help&id=building">Bygningselementer</a><br>
                  Klik dig frem til et emne
                  <br>
                  <br>
                  <a href="index.php?action=about&item=help&id=search">Søgning</a><br>
                  Søg dig frem til et emne
                  <br>
                  <br>
                  <a href="index.php?action=about&item=help&id=ext_search">Udvidet søgning</a><br>
                  Gør din søgning mere præcis
                  <br>
                  <br>
                  <a href="index.php?action=about&item=help&id=results">Søgeresultater</a><br>
                  Sådan er søgeresultatet opdelt
                  <br>
                  <br>
                  <a href="index.php?action=about&item=help&id=pay">Betaling</a><br>
                  Nogle dokumenter på bygviden.dk koster penge
                  <br>
                  <br>
                  <a href="index.php?action=about&item=help&id=minside">Min side</a><br>
                  Tilmeld brugere, og skift adgangskode
                  <br>
                  <br>
                  <a href="index.php?action=about&item=help&id=log">Log ind</a><br>
                  Du skal være logget ind for at købe og se forbrug
                </td>
              </tr>
            </table>';
  }
  function Content()
  {
    switch($this->getCurrentItem())
    {
      case('copy'): return $this->copyRight();
      case('ansvar'): return $this->ansvar();
      case('kontakt'): return $this->kontakt();
      case('om'): return $this->omOs();
      case('help'): return $this->help();
    }
    return $this->omOs();
  }
}
?>
