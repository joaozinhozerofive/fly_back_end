<?php

class UploadConfigs {
  public static function saveFile($files) {
    $TMP_FOLDER    = dirname(__DIR__ ) . '/TMP';
    $targetFile    = $TMP_FOLDER . '/' . basename($files->name);

    try{
      $extension = explode('.', $files->name);
      $extension = $extension[sizeof($extension) - 1];
     
      if($extension != 'jpg' && $extension != 'png') {
         AppError("Arquivo não permitido", 404);
      }
      move_uploaded_file($files->tmp_name, $targetFile);
    }
    catch(Exception $e) {
      AppError("Não foi possível executar o upload do arquivo {$files->name}", 500);
    }
  }

  public static function deleteFile($fileName) {
    
    $TMP_FOLDER    = dirname(__DIR__ ) . '/TMP';
    $targetFile    = $TMP_FOLDER . '/' . basename($fileName);

    try{
      if(file_exists($targetFile)) {
        unlink($targetFile);
      }
    }
    catch(Exception $e) {
      AppError("Não foi possível excluir o o arquivo {$fileName}", 500);
    }
  }
}