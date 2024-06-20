<?php 

require_once(__DIR__ .'/themecColor.service.php');

class ThemeColorController {
    public function update() {
      $body = requestBody();
      ThemeColorService::update(data : $body);
    }

}