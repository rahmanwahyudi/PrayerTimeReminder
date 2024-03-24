<?php

namespace Controllers;

trait ViewLoaderTrait
{
    private function view($viewPath, $data = [], $resources = [])
    {
        $headerData = isset($resources['header']) ? $resources['header'] : [];
        $this->loadViewPart('template/header', $headerData);

        $this->loadViewPart($viewPath, $data);

        $footerData = isset($resources['footer']) ? $resources['footer'] : [];
        $this->loadViewPart('template/footer', $footerData);
    }

    private function loadViewPart($viewPath, $data = [])
    {
        $filePath = __DIR__.'/../views/'.$viewPath.'.php';
        if (file_exists($filePath)) {
            extract($data);
            require $filePath;
        } else {
            header('HTTP/1.0 404 Not Found');
            echo "View part not found: {$viewPath}";
        }
    }
}
