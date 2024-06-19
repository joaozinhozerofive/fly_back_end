<?php
require_once($parentDir . '/uploadsConfig/upload.php');

class BannerService {
    public static function create($data, $file) {
        $page_name     = $data->page_name;
        $banner_tittle = $data->banner_tittle;
        $image         = $file->image;

        self::validateDataBannerCreate($data);

        $newHash = bin2hex(random_bytes(16));
        $image->name = $newHash . $image->name;

        try {
            (new qbquery('banner'))
            ->insert([
                'page_name'     => $page_name, 
                'banner_tittle' => $banner_tittle, 
                'image'         => $image->name
            ]);

            UploadConfigs::saveFile($image);


            AppSucess("Banner criado com sucesso!", 201);
        }
        catch (Exception $e) {
            AppError("Não foi possível inserir o banner.");
        }
    }   

    public static function show($params) {
        $banners = self::getBannersByRouteParams($params);

        return $banners;
    }

    public static function delete($id) {
        $banner = self::getBannerById($id);

        if(!$banner) {
            AppError('Banner não encontrado.', 404);
        }

        try {
            (new qbquery('banner'))
            ->delete("id = $id");
            
            UploadConfigs::deleteFile($banner->image);
            
            AppSucess("Banner excluído com sucesso!");
        }
        catch(Exception $e)     {
            AppError("Não foi possível deletar o banner.", 400);
        }
    }

    public static function validateDataBannerCreate($data) {
        $page_name     = $data->page_name;
        $banner_tittle = $data->banner_tittle;

        if(!trim($page_name)) {
            AppError("Nome da página é obrigatório", 400);
        }
    }

    public static function getBannersByRouteParams($params) {
        $id = intval(pr_value($params, 'id'));

        if($id) {
            $banner = self::getBannerById($id);

            if(!$banner) {
                AppError("Banner não encontrado", 404);
            }

            return $banner;
        }

        if($params) {
            $banners = self::getBannerByLikeParams($params);

            return $banners;
        }

        return self::getManyBanners();
    }

    public static function getBannerById($id) {
        $banner = (new qbquery('banner'))
        ->where(['id' => $id])
        ->getFirst();

        return $banner;
    }

    public static function getBannerByLikeParams($params) {
        $params = objectToArrayAssoc($params);

        return (new qbquery('banner'))
        ->whereLike($params)
        ->getMany();
    }

    public static function getManyBanners() {
        return (new qbquery('banner'))
        ->getMany();
    }
}