<?php

//Zde vytvarim noveho robota
//Tento krok staci udelat jednou hned na zacatku,
// tak nemam podminku pokud se stranka nenacte

$url="https://area51.serverzone.dev/robot/";
$data=array('email' => 'vesai1@seznam.cz');
function httpPost($url, $data)
{
  $curl = curl_init($url);
  curl_setopt($curl, CURLOPT_POST, true);
  curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  $response = curl_exec($curl);
  curl_close($curl);
  return $response;
}
$vysledek = httpPost($url,$data);
echo($vysledek);
// Vystup zustava stejny, tak si pomoci substringu beru jen casti s kterymi pracuji.
$id=substr($vysledek,7,strlen($vysledek)-9);
echo($id);

// Funkce kterou posilam PUT requests robotovi

function httpPut($id, $data)
{
  $url1="https://area51.serverzone.dev/robot/".$id."/move";

  $curl = curl_init($url1);

  curl_setopt($curl,CURLOPT_CUSTOMREQUEST, "PUT");
  curl_setopt($curl,CURLOPT_POSTFIELDS, http_build_query($data));
  curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl,CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
  curl_setopt($curl,CURLOPT_VERBOSE, true);

  $response = curl_exec($curl);
  //echo(curl_errno($curl));
  //echo(curl_error($curl));
  curl_close($curl);

  $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
  echo($code);

  return $response;
}
//Funkce, kterou robot odleti
//Sla by pouzit predchozi funkce s jinou url,
// ale zkopirovani bylo snazsi kdyz uz se kod nepouzije
  function httpPutExit($id, $data)
  {
    $url1="https://area51.serverzone.dev/robot/".$id."/escape";

    $curl = curl_init($url1);

    curl_setopt($curl,CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($curl,CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl,CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($curl,CURLOPT_VERBOSE, true);

    $response = curl_exec($curl);
    //echo(curl_errno($curl));
    //echo(curl_error($curl));
    curl_close($curl);
    $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    echo($code);

    return $response;
}


// Moje reseni ulohy: robot narazi na jednu stranu a pak na druhou.
// Mezitim ukladam vzdalenost co ujel
// Promenna $doprava neni potreba, ale kontroloval jsem pomoci ni vystupy
// Promenna $timeout ma zabranit zacykleni v pripade, ze server opakovane neodpovi

$timeout=0;
$pohybINT=5;
$doprava=0;
while($pohybINT == 5){
$data=array('direction'=>'right','distance'=>5);
$pohyb = httpPut($id,$data);
if($pohyb==""){
  $timeout+=1;

  if($timeout==20){
    echo("Server neodpovida");
    $pohybINT=2;
  }
}else{
echo($pohyb);
$pohybINT=(int)substr($pohyb,12,1);
$doprava+= $pohybINT;
echo($pohybINT);
echo($doprava);
}
}

// Analogicke reseni pohybu opacnym smerem, s obnovenymi promennymi
// Promenna $doleva zmeri sirku skladu
$timeout=0;
$pohybINT=5;
$doleva=0;
while($pohybINT == 5){
$data=array('direction'=>'left','distance'=>5);
$pohyb = httpPut($id,$data);
if($pohyb==""){
  $timeout+=1;

  if($timeout==20){
    echo("Server neodpovida");
    $pohybINT=2;
}
}else{
echo($pohyb);
$pohybINT=(int)substr($pohyb,12,1);
$doleva+= $pohybINT;
echo($doleva);
}
}


// Analogicke reseni pro pohyb nahoru a dolu
$timeout=0;
$pohybINT=5;
$dolu=0;
while($pohybINT == 5){
$data=array('direction'=>'down','distance'=>5);
$pohyb = httpPut($id,$data);
if($pohyb==""){
  $timeout+=1;

  if($timeout==20){
    echo("Server neodpovida");
    $pohybINT=2;
}
}else{
echo($pohyb);
$pohybINT=(int)substr($pohyb,12,1);
$dolu+= $pohybINT;
echo("dolu".$dolu);
}
}


$timeout=0;
$pohybINT=5;
$nahoru=0;
while($pohybINT == 5){
$data=array('direction'=>'up','distance'=>5);
$pohyb = httpPut($id,$data);
if($pohyb==""){
  $timeout+=1;

  if($timeout==20){
    echo("Server neodpovida");
    $pohybINT=2;
}
}else{
echo($pohyb);
$pohybINT=(int)substr($pohyb,12,1);
$nahoru+= $pohybINT;
echo("nahoru".$nahoru);
}
}

// Zde si zmerim polovinu haly kam se ma robot na konci dostat
$vyska=$nahoru/2;
$sirka=$doleva/2;

// Robot se pohybuje co nejrychlejsi ke stredu
while($vyska > 5){
$data=array('direction'=>'down','distance'=>5);
$pohyb = httpPut($id,$data);
if($pohyb==""){
  $timeout+=1;

  if($timeout==20){
    echo("Server neodpovida");
    $pohybINT=2;
}
}else{
echo($pohyb);
$pohybINT=(int)substr($pohyb,12,1);
$vyska-= $pohybINT;
echo("dolu".$vyska);
}
}

// Robot ujede vzdalenost, ktera mu zbyva poslednim pohybem dolu
$timeout=0;
$pohyb="";
while($pohyb==""){
$timeout +=1;
$data=array('direction'=>'down','distance'=>$vyska);
$pohyb = httpPut($id,$data);


if($timeout==20){
  echo("Server neodpovida");
  $pohyb=4;
}
}
$pohybINT=(int)substr($pohyb,12,1);
$vyska-= $pohybINT;
echo($pohyb);
echo("dolu".$vyska);

// Robot jede stejnym zpusobem do stredu horizontalne

while($sirka > 5){
$data=array('direction'=>'right','distance'=>5);
$pohyb = httpPut($id,$data);
if($pohyb==""){
  $timeout+=1;

  if($timeout==20){
    echo("Server neodpovida");
    $pohybINT=2;
}
}else{
echo($pohyb);
$pohybINT=(int)substr($pohyb,12,1);
$sirka-= $pohybINT;
echo("doprava".$sirka);
}
}

$timeout=0;
$pohyb="";
while($pohyb==""){
$data=array('direction'=>'right','distance'=>$sirka);
$pohyb = httpPut($id,$data);
$timeout+=1;
echo($pohyb);
if($timeout==20){
  echo("Server neodpovida");
  $pohyb=2;
}
}
$pohybINT=(int)substr($pohyb,12,1);
$sirka-= $pohybINT;

echo("doprava".$sirka);

// Finalni vyroba dat pro vystreleni robota a volani funkce pro jeho escape.

$data= array('salary' =>'30000kc');

$pohyb = httpPutExit($id,$data);
echo($pohyb);

?>
