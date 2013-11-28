<?php

class tribunal extends api
{
  protected function Reserve()
  {
    return array('design' => 'tribunal/form', 'result' => 'tribunal');
  }
}