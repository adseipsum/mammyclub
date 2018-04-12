<?php

error_reporting(E_ALL);
ini_set('display_startup_errors',1);
ini_set('display_errors',1);

// ------------ SETTINGS -----------------------------

$workspaceDir = 'C:\workspace\\';
$workspaceVitaLangDir = $workspaceDir . 'vitagramma\src\app\language\\';

if (!file_exists($workspaceVitaLangDir)) {
  die();
}

// ----------- LOGIC ---------------------------------

if (!empty($_POST)) {
  
  $k = $_POST['prefix'] . $_POST['key'];
  
  $value = nl2br(trim($_POST['ru_text']));
  
  // RU
  $language = 'russian';
  $filePath = $workspaceVitaLangDir . $language . '\\' . $_POST['file'];
  $fh = fopen($filePath, 'a');
  $stringData = '$lang[\'' . $k . '\'] = \'' . $value . '\';' . "\n";
  fwrite($fh, $stringData);
  fclose($fh);
  
  // UA
  $uavalue = translate($value, 'uk');
  $language = 'ukranian';
  $filePath = $workspaceVitaLangDir . $language . '\\' . $_POST['file'];
  $fh = fopen($filePath, 'a');
  $stringData = '$lang[\'' . $k . '\'] = \'' . $uavalue . '\';' . "\n";
  fwrite($fh, $stringData);
  fclose($fh);
  
  // EN
  $envalue = translate($value, 'en');
  $language = 'english';
  $filePath = $workspaceVitaLangDir . $language . '\\' . $_POST['file'];
  $fh = fopen($filePath, 'a');
  $stringData = '$lang[\'' . $k . '\'] = \'' . $envalue . '\';' . "\n";
  fwrite($fh, $stringData);
  fclose($fh);
  
  
  // Redirect
  $params = '?';
  $params .= 'file=' . $_POST['file'];
  $params .= '&prefix=' . $_POST['prefix'];
  $params .= '&key=' . $_POST['key'];
  $params .= '&lang_key=' . $k;
  header('Location: msg.php' . $params);
  
  
  die();
} else {
  if (isset($_GET['key']) && is_numeric($_GET['key'])) {
    $_GET['key'] = $_GET['key'] + 1;
  }
}

$files = array();
if ($handle = opendir($workspaceVitaLangDir . 'russian')) {
  while (false !== ($entry = readdir($handle))) {
    if ($entry != "." && $entry != ".." && strpos($entry, '.php') !== FALSE) {
      $files[] = $entry;
    }
  }
  closedir($handle);
}

function translate($value, $langTo) {
  $url = "https://www.googleapis.com/language/translate/v2?key=AIzaSyD8XmelbeeOVYrogczgRE8GdIVvKvfSSUA&source=ru&target=" . $langTo . "&q=" . urlencode($value);
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, $url);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($curl, CURLOPT_REFERER, 'localhost');
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
  $response = curl_exec($curl);
  curl_close($curl);
  $json = json_decode($response, true);
  if (isset($json['data']) && isset($json['data']['translations']) && isset($json['data']['translations'][0]) && isset($json['data']['translations'][0]['translatedText'])) {
    $value = $json['data']['translations'][0]['translatedText'];
  }
  
  $value = str_replace('& lt;', '&lt;', $value);
  $value = str_replace('& gt;', '&lt;', $value);
  $value = str_replace('& laquo ;', '&laquo;', $value);
  $value = str_replace('& laquo;', '&laquo;', $value);
  $value = str_replace('& raquo;', '&raquo;', $value);
  $value = str_replace('& raquo ;', '&raquo;', $value);
  $value = str_replace('& nbsp;', '&nbsp;', $value);
  $value = str_replace('Ago', 'Back', $value);
  $value = str_replace('" ', '"', $value);
  $value = str_replace(' "', '"', $value);
  $value = str_replace('</ ', '</', $value);
  $value = str_replace('</ nobr>', '</nobr>', $value);
  $value = str_replace(' = ', '=', $value);
  $value = str_replace('= ', '=', $value);
  $value = str_replace(' =', '=', $value);
  $value = str_replace(' &raquo;', '&raquo;', $value);
  $value = str_replace('&laquo; ', '&laquo;', $value);
  
  return $value;
}

?>
<html>
  <head>
    <title>Message properties generator :)</title>
  </head>
  <body>

    <h1>Message properties generator :)</h1>
    
    
    <? if(isset($_GET['lang_key'])): ?>
     <h2><pre>&lt;<?='?=lang(\'' . $_GET['lang_key'] . ');?'?>&gt;</pre></h2>
    <? endif; ?>
    
    
    <form action="msg.php" method="post" autocomplete="off">
    
      <div>
        <span>Файл: </span>
        <select name="file" required>
          <option value=""></option>
          <? foreach($files as $f): ?>
          <option value="<?=$f;?>" <?=(isset($_GET['file']) && $_GET['file'] == $f)?'selected="selected"':'';?>><?=$f;?></option>
          <? endforeach; ?>
        </select>
      </div>
      
      <div style="margin-top: 20px;">
        <span>Префикс (с точкой): </span><input name="prefix" style="width: 300px;" value="<?=(isset($_GET['prefix']))?$_GET['prefix']:'';?>" required/>
      </div>
      
      <div style="margin-top: 20px;">
        <span>Ключ: </span><input name="key" style="width: 300px;" value="<?=(isset($_GET['key']))?$_GET['key']:'1';?>" required/>
      </div>
    
      <div style="margin-top: 20px;">
        <span>Текст (РУС): </span><textarea name="ru_text" style="width: 100%" required></textarea>
      </div>
      
      <div style="margin-top: 20px;">
        <input type="submit"/>
      </div>
    </form>
  
  
  
  </body>

</html>