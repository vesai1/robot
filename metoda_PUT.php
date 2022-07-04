
<?php

$url="https://area51.serverzone.dev/robot/3a80308a03bc76c963d36122fdf063fa1b1b721a/move";

$data=array('direction'=>'right','distance'=>3);
function httpPut($url, $data)
{

  $curl = curl_init($url);

  curl_setopt($curl,CURLOPT_PUT, true);
  curl_setopt($curl,CURLOPT_POSTFIELDS, http_build_query($data));
  curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl,CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
  curl_setopt($curl,CURLOPT_VERBOSE, true);
  $response = curl_exec($curl);
  echo(curl_errno($curl));
  echo(curl_error($curl));
  curl_close($curl);
  echo($response);
  $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
  echo($code);
  return $response;
}
$vysledek = httpPut($url,$data);
echo($vysledek);

?>
