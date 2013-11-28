<?php

class stats extends api
{
  protected function Reserve()
  {
    return array('design' => 'stats/body', 'result' => 'stats');
  }
}