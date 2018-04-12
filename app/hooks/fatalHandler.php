<?php

  function fatal_handler() {
    register_shutdown_function('shutdownFunction');
  }

  function shutDownFunction() {
    $error = error_get_last();
    if ($error['type'] == 1) {
      chdir($_SERVER['DOCUMENT_ROOT']);
      log_message('error', 'FATAL ERROR: ' . $error['message'] . '; FILE: ' . $error['file'] . ' at line: ' . $error['line']);
    }
  }

?>