<?php 
class Route {
    private $currentRoute;

    private $alternativeRoute;

    private $responseData;  

    public function useRoute(string $currentRoute) {
       $this->currentRoute = 'fly-web-service/server' . $currentRoute; 
    }

    public function get(string $alternativeRoute, $data) {
        if($_SERVER["REQUEST_METHOD"] == 'GET') {

            if($alternativeRoute = '/') {
                $alternativeRoute = '';
            }
    
            $this->setResponseData($data);
            $this->alternativeRoute = $alternativeRoute;
    
            $currentRoute = $this->currentRoute . $this ->alternativeRoute;
            $originRoute =  $this->getRouteFormatted($_SERVER['REQUEST_URI']);
    
            if($this->matchRoutes($originRoute, $currentRoute)) {
             echo json_encode($this->responseData);
            }

        }
    }

    public function post(string $alternativeRoute, $data) {
        if($_SERVER["REQUEST_METHOD"] == 'POST') {

            if($alternativeRoute === '/') {
                $alternativeRoute = '';
            }
    
            $this->setResponseData($data);
            $this->alternativeRoute = $alternativeRoute;
    
            $currentRoute = $this->currentRoute . $this ->alternativeRoute;
            $originRoute =  $this->getRouteFormatted($_SERVER['REQUEST_URI']);
    
            if($this->matchRoutes($originRoute, $currentRoute)) {
              echo json_encode($this->responseData);
            }
        }
    }

    private function getRouteFormatted($currentRoute) {
        $aRoute = explode("/", $currentRoute); 
        $aRouteWithoutSpace = [];

        foreach ($aRoute as $key => $value) {
            if($value != "") {
                array_push($aRouteWithoutSpace, $value);
            }
        }

        $sRoute = implode('/', $aRouteWithoutSpace);

        return $sRoute;
    }

    private function setResponseData($data) {
        $this->responseData = $data;
    }

    private function matchRoutes(string $routeOne, string $routeTwo) {
        if($routeOne == $routeTwo) {
            return true;
        }
        else{
            return false;
        }
    }

}
?>