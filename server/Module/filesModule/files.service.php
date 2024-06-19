<?php

class FilesService {
    public static function show($fileName) {   
        responseStaticFile($fileName);
    }
}