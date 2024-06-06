<?php 
class Route {
    private $currentRoute;

    private $alternativeRoute;

    private $routes = [];  

    public function useRoute(string $currentRoute) {
       $this->currentRoute = 'fly-web-service/server' . $currentRoute; 
    }

    public function get(string $alternativeRoute, callable $callback) {
        $this->addRoute('GET', $alternativeRoute, $callback);
    }

    public function post(string $alternativeRoute, callable $callback) {
        $this->addRoute('POST', $alternativeRoute, $callback);
    }

    public function delete(string $alternativeRoute, callable $callback) {
        $this->addRoute('DELETE', $alternativeRoute, $callback);
    }

    public function put(string $alternativeRoute, callable $callback) {
        $this->addRoute('PUT', $alternativeRoute, $callback);
    }
    public function pacth(string $alternativeRoute, callable $callback) {
        $this->addRoute('PATCH', $alternativeRoute, $callback);
    }

    private function getRouteFormatted($currentRoute) {
        $aRoute = explode("/", $currentRoute); 
        $aRouteWithoutSpace = [];

        foreach ($aRoute as $key => $value) {
            if($value != "") {
                array_push($aRouteWithoutSpace, $value);
            }
        }

        $sRoute         = implode('/', $aRouteWithoutSpace);
        $lettersRoute   = str_split($sRoute);
        $haveQueryParam =  false;

        foreach($lettersRoute as $letter) {
            if($letter == '?') {
                $haveQueryParam = true; 
            }
        }

        if($haveQueryParam) {
            $positionQueryParam = strpos($sRoute, '?');
            $sRoute = substr($sRoute, 0, $positionQueryParam);
        }

        return $sRoute;
  }


    private function addRoute($method, $alternativeRoute, callable $callback) {
        $uriRequest = $_SERVER['REQUEST_URI'];
        if($_SERVER["REQUEST_METHOD"] == $method) {
            if($alternativeRoute === '/') {
                $alternativeRoute = '';
            }

            $this->alternativeRoute = $alternativeRoute;
            $currentRoute = $this->currentRoute . $this ->alternativeRoute;
            $this->routes[] = $currentRoute;
            $originRoute  = $this->getRouteFormatted($uriRequest);

            if($this->matchRoutes($originRoute)) {
              http_response_code(200);  

              $callback();
            }
        }
    }

    private function matchRoutes(string $routeOne) {
        $routeExist = false;

        foreach($this->routes as $route) {
            if($routeOne === $route) {
                $routeExist = true;
            }
        }

        return $routeExist;
    }

}
?>