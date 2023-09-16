<?php

namespace App\Helpers;

class GuardHelper 
{
  public static function guard()
  {
    return [
      'web',
      'cms',
    ];
  }
}