<?php
require_once(dirname(__DIR__) . '/BannerModule/banner.service.php');
class BannerController {
    public function create() {
        $body = requestFormData();

        BannerService::create(data : $body, file : getFiles());
    }

    public function show() {
        $params = getRouteParams();
        $response = BannerService::show(params : $params);
        responseJson($response);
    }

    public function delete() {
        $id = getRouteParams('id');
        BannerService::delete(id : $id);
    }
}