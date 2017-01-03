<?php

namespace App\Helpers;

class Request
{


  public static function only($request, array $params)
  {
    $requestArray = [];
    foreach ($request->getParams() as $key => $param) {
      if(in_array($key, $params)) {
        $requestArray[$key] = $param;
      }
    }

    return $requestArray;

  }

  public static function get($request, $name)
  {
    return ($request->getParam($name)) ? $request->getParam($name) : null;
  }

}
