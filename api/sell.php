<?php

class sell extends api
{
  protected function Reserve()
  {
    return array(
      "design" => "sell/sell_form.ejs",
      "result" => "sell_left"
      );
  }
}