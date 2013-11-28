<?php

class buy extends api
{
  protected function Reserve()
  {
    return array(
      "design" => "buy/buy_form.ejs",
      "result" => "buy_right"
      );
  }
}