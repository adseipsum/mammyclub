<?php
class DBLogger {

  private $layout = null;

  public function DBLogger() {
  }

  public function process() {
    log_message("error", "DBLogger!!!");
    $dbName = "mammyclub";
    if(ENV == 'PROD') {
      $dbName = "mammyclub_prod";
    }
    $conn = Doctrine_Manager::getInstance()->getConnection($dbName);
    $profiler = $conn->getListener();
    if ($profiler){
      $time = 0;

      $res = "<----------- DATABASE [" . uri_string() ."] ------------> \n";
      $count = 0;
      foreach ($profiler as $event) {
        if($event->getName() == 'query' || $event->getName() == 'execute'){
          $time += $event->getElapsedSecs();
          $res .= "Event: " . $event->getName() . "\n";
          $query = $event->getQuery() . "\n";
          $params = $event->getParams();
          if(!empty($params)) {
//             $res .= "Params: " .  print_r($params, true);
            $query = str_replace('?', "'%s'", $query);
            $query = vsprintf($query, $params);
          }
          if(number_format($event->getElapsedSecs(), 10) > 0.5) {
            $res .= $query;
            $res .= "Time" . number_format($event->getElapsedSecs(), 10) . "\n";
            $res .= "\n";
          }
          $count++;
        }
      }
      $res .= "Total time: " . $time  . "\n";
      $res .= "Total queries: " . $count . " \n";
      log_message("error", $res);
    }
  }



}
?>