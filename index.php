<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=
    , initial-scale=1.0">
    <title>Autogegevens ophalen</title>
    <link rel="stylesheet" href='./style.css'>
    <script defer src='./script.js'></script>
</head>

<!-- 
Wensen: 

- merk / type / handelsbenaming  v 
- max 8 tekens invoeren in veld nummerbord v 
- knop toon/verberg meer gegevens v 

-->
<body>

<div class="center">

<form action=" " method="post">

<div class="inputgroup licenseplate_container">

<div style="display: flex">
<input class='licenseplate' type="text" id="lp" name="lp" maxlength="8">
<div>
    <a href='./'>X</a>
</div>

</div>

<input class="invisible" type="submit" value='Zoek auto'> 

</form>



</div>

<?php

if (!empty($_POST['lp'])) {

   $_POST['lp'] = strtoupper($_POST['lp']); 

   $_POST['lp'] = str_replace('-', '', $_POST['lp']);
   

$url = 'https://opendata.rdw.nl/resource/m9d7-ebf2.json?kenteken='.$_POST['lp'];

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_SSL_VERIFYPEER => 0   // Nodig bij Strato 

));

$response = curl_exec($curl);

curl_close($curl);

$response = json_decode($response, true);

?>
<script>
    document.getElementById('lp').value= "<?php echo $response[0]['kenteken']; ?>"
</script>

<?php

if (empty($response[0])) {
    echo '<div class="center">Het ingevoerde kenteken ('. strtoupper($_POST['lp']) .') is niet bekend.</div>';
    return;
};

echo '<div class="table_center">';

echo '<table>';

echo '<tr>'; 

if (str_contains($response[0]['handelsbenaming'], $response[0]['merk'] )) 
 {
    echo '<td><strong>Merk/type: </strong></td>' . 
    '<td>' . $response[0]['merk'] . ' ' . trim($response[0]['handelsbenaming'], $response[0]['merk']) .
' - ' . strtolower($response[0]['eerste_kleur']);
 }

 else {
    echo '<td><strong>Merk/type: </strong></td>' . 
    '<td>' . $response[0]['merk'] . ' ' . $response[0]['handelsbenaming'] . 
    ' - ' . strtolower($response[0]['eerste_kleur']);
 }

echo '</td>';
echo '</tr>';

/*
echo '<tr>';
echo '<td><strong>Merk/type</strong></td>' . '<td>' .
(empty($response[0]['handelsbenaming']) ? '<i> niet bekend </i>' : $response[0]['handelsbenaming']) . 
(!empty($response[0]['eerste_kleur']) ? ' - ' . strtolower($response[0]['eerste_kleur']) : null ) . '</td>';
echo '</tr>';
*/

echo '<tr>';
echo '<td><strong>Vervaldatum APK</strong></td>' . '<td>' . 
(empty($response[0]['vervaldatum_apk_dt']) ? '<i> niet bekend </i>' : date('d-m-Y' , strtotime($response[0]['vervaldatum_apk_dt']))) . '</td>';
echo '</tr>';

echo '<tr>';
echo '<td><strong>In bezit van huidige eigenaar sinds</strong></td>' . '<td>' . 
(empty($response[0]['datum_tenaamstelling_dt']) ? '<i> niet bekend </i>' : date('d-m-Y' , strtotime($response[0]['datum_tenaamstelling_dt']))) . '</td>';
echo '</tr>';

echo '<tr>';
echo '<td><strong>Datum eerste toelating ' . ($response[0]['datum_eerste_toelating_dt'] != $response[0]['datum_eerste_tenaamstelling_in_nederland_dt'] ? '/ in Nederland ' : null ) . '</strong></td>' . 
(empty($response[0]['datum_eerste_toelating_dt']) ? '<i> niet bekend </i>' : '<td>' . date('d-m-Y' , strtotime($response[0]['datum_eerste_toelating_dt']))) . 

($response[0]['datum_eerste_toelating_dt'] != $response[0]['datum_eerste_tenaamstelling_in_nederland_dt'] ?  
 ' / ' . date('d-m-Y' , strtotime($response[0]['datum_eerste_tenaamstelling_in_nederland_dt'])) : null) .   
'</td>';
echo '</tr>';

echo '<tr>';
echo '<td><strong>Zuinigheidsclassificatie</strong></td>' . '<td>' . $response[0]['zuinigheidsclassificatie'] . '</td>';
echo '</tr>';

// Extra gegevens 
echo '
</table>
</div>';

echo '
<div class="btn">
<button id="show_hide_button"></button>
</div>

<div id="show_hide">';

echo '
<div class="table_center">
<table>';

echo '<tr>';
echo '<td><strong>Aantal zitplaatsen</strong></td>' . '<td>' . 
(empty($response[0]['aantal_zitplaatsen']) ? '<i> niet bekend </i>' : $response[0]['aantal_zitplaatsen']) . '</td>';
echo '</tr>';

echo '<tr>';
echo '<td><strong>Aantal cilinders / cilinderinhoud</strong></td>' . '<td>' . 
(empty($response[0]['aantal_cilinders']) ? '<i> niet bekend </i>' : $response[0]['aantal_cilinders']) . ' / ' . 
(empty($response[0]['cilinderinhoud']) ? '<i> niet bekend </i>' : $response[0]['cilinderinhoud']) . '</td>';
echo '</tr>';

echo '<tr>';
echo '<td><strong>Massa leeg voertuig</strong></td>' . '<td>' . 
(empty($response[0]['massa_ledig_voertuig']) ? '<i> niet bekend </i>' : $response[0]['massa_ledig_voertuig']) . '</td>';
echo '</tr>';

echo '<tr>';
echo '<td><strong>Toegestane maximum massa voertuig</strong></td>' . '<td>' . 
(empty($response[0]['toegestane_maximum_massa_voertuig']) ? '<i> niet bekend </i>' : $response[0]['toegestane_maximum_massa_voertuig']) . '</td>';
echo '</tr>';

echo '<tr>';
echo '<td><strong>Maximum massa trekken ongeremd / geremd</strong></td>' . '<td>' . 
(empty($response[0]['maximum_massa_trekken_ongeremd']) ? '<i> niet bekend </i>' : $response[0]['maximum_massa_trekken_ongeremd']) . ' / ' . 
(empty($response[0]['maximum_trekken_massa_geremd']) ? '<i> niet bekend </i>' : $response[0]['maximum_trekken_massa_geremd']) . '</td>';
echo '</tr>';
      
echo '<tr>';
echo '<td><strong>Catalogusprijs</strong></td>' . '<td>' . 
(empty($response[0]['catalogusprijs']) ? '<i> niet bekend </i>' : $response[0]['catalogusprijs'] . ' euro ') . '</td>';
echo '</tr>';

echo '<tr>';
echo '<td><strong>Aantal deuren / wielen </strong></td>' . '<td>' . 
(empty($response[0]['aantal_deuren']) ? '<i> niet bekend </i>' : $response[0]['aantal_deuren']) . ' / ' . 
(empty($response[0]['aantal_wielen']) ? '<i> niet bekend </i>' : $response[0]['aantal_wielen']) . '</td>';
echo '</tr>';
      
echo '<tr>';
echo '<td><strong>Europese voertuigcategorie</strong></td>' . '<td>' . 
(empty($response[0]['europese_voertuigcategorie']) ? '<i> niet bekend </i>' : $response[0]['europese_voertuigcategorie']) . '</td>';
echo '</tr>'; 
       
echo '<tr>';
echo '<td><strong>Plaats chassisnummer</strong></td>' . '<td>' . 
(empty($response[0]['plaats_chassisnummer']) ? '<i> niet bekend </i>' : $response[0]['plaats_chassisnummer']) . '</td>';
echo '</tr>';

echo '<tr>';
echo '<td><strong>Typegoedkeuringsnummer</strong></td>' . '<td>' . 
(empty($response[0]['typegoedkeuringsnummer']) ? '<i> niet bekend </i>' : $response[0]['typegoedkeuringsnummer']) . '</td>';
echo '</tr>'; 
  
echo '<tr>';
echo '<td><strong>Wielbasis</strong></td>' . '<td>' . 
(empty($response[0]['wielbasis']) ? '<i> niet bekend </i>' : $response[0]['wielbasis']) . '</td>';
echo '</tr>';

echo '<tr>';
echo '<td><strong>Openstaande terugroepactie indicator</strong></td>' . '<td>' . 
(empty($response[0]['openstaande_terugroepactie_indicator']) ? '<i> niet bekend </i>' : $response[0]['openstaande_terugroepactie_indicator']) . '</td>';
echo '</tr>';
       
echo '<tr>';
echo '<td><strong>Jaar laatste registratie tellerstand</strong></td>' . '<td>' . 
(empty($response[0]['jaar_laatste_registratie_tellerstand']) ? '<i> niet bekend </i>' : $response[0]['jaar_laatste_registratie_tellerstand']) . '</td>';
echo '</tr>';   

echo '<tr>';
echo '<td><strong>Tellerstandoordeel</strong></td>' . '<td>' . 
(empty($response[0]['tellerstandoordeel']) ? '<i> niet bekend </i>' : $response[0]['tellerstandoordeel']) . '</td>';
echo '</tr>';  

echo '
</table>
</div>
</div>';

};   
      
?>

<script>

let showHideVar = true;

let showHideBtn = document.getElementById('show_hide_button');
let showHideTable = document.getElementById('show_hide');

 showHideBtn.innerText = 'Toon meer gegevens';
  showHideTable.setAttribute('class', 'hide');

    showHideBtn.addEventListener('click', () => {

        showHideVar = !showHideVar;

        if (showHideVar) {
            showHideTable.setAttribute('class', 'hide');
            showHideBtn.innerText = 'Toon meer gegevens';        
        } 
        else {
            showHideTable.removeAttribute('class', 'hide');
            showHideBtn.innerText = 'Minder gegevens';
        }       

        })
</script>

</body>

</html>
