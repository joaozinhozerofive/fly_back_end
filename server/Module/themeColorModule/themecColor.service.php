<?php 

class ThemeColorService{
    public static function update($data) {
       $theme = self::getThemeColor();

       if(!$theme) {
          AppError("Não existe nenhuma configuração de tema.", 404);
       }

       if(strlen($data->color) > 100) {
         AppError("Cor deve ter no máximo 100 carácteres.", 400);
       }
       
       $theme = self::getDataThemeColorUpdate($theme, $data);

       try {
            (new qbquery('theme_color_website'))
            ->update($theme);
            
            AppSucess("Tema atualizado com sucesso!.");
       }
       catch(Exception $e) {
            AppError("Não foi possível atualizar tema.");
       }
    }


    public static function getThemeColor() {
        return (new qbquery('theme_color_website'))
        ->getFirst();
    }

    public static function getDataThemeColorUpdate($theme, $data) {
        $color = $data->color;

        $theme->color = trim($color) ? $color : $theme->color;

        return $theme;
    }
}