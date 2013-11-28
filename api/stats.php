<?php

class stats extends api
{
  protected function Reserve()
  {
    $res = array(
      "bitcoin" =>
        array(
          "blocks" => 271993,
          "difficulty" => 609486680,
          "forecast" => "???",
          "power" => "???",
          "time" => "22:52"
          ),
      "mtgox" =>
        array(
          "last" => "???",
          "high" => "???",
          "low" => "???",
          "buy" => "???",
          "sell" => "???",
          "volume" => "???"
          )
    );
    return array(
      'design' => 'stats/body',
      'result' => 'stats',
      'data' => $res);
  }
}