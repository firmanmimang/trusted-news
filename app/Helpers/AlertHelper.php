<?php

namespace App\Helpers;

class AlertHelper {

  const ALERT_SUCCESS = 'success';
  const ALERT_ERROR = 'error';
  const ALERT_INFO = 'info';

  public static function flashError(string $message,string $position='bottom-right', int $timeout=3000)
  {
    flash()
      ->option('position', $position)
      ->option('timeout', $timeout)
      ->addError($message);
  }

  public static function flashSuccess(string $message,string $position='bottom-right', int $timeout=3000)
  {
    flash()
      ->option('position', $position)
      ->option('timeout', $timeout)
      ->addSuccess($message);
  }
}