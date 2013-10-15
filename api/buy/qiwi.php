<?php

class qiwi extends api
{
  protected function Form()
  {
    $main = LoadModule('api', 'main');
    return array(
      "design" => "buy/qiwi_form",
      "result" => "hero_content",
      "script" => array("js/qiwi_buy"),
      "routeline" => "QiwiBuyFrom",
      "data" => array("cource" => $main->GetCource())
      );
  }
  
  protected function Request()
  {
    $main = IncludeModule('api', 'main');
    echo $main->Request($main->GetCource() * $_POST['btc_amount'], $_POST['phone'], $_POST['wallet']);
    exit;
  }
}