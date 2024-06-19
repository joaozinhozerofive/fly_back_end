<?php
require_once(__DIR__ . '/files.service.php');
class FilesController {
    public function show() {   
        $fileName = getRouteParams('image');
        FilesService::show($fileName);        
    }
}