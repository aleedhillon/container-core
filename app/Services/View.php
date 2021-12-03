<?php

namespace App\Services;

use Exception;

class View
{
    protected string $view;
    protected array $data;
    protected bool $withLayout;

    public function __construct(string $view, array $data = [], bool $withLayout = false)
    {
        $this->view = $view;
        $this->data = $data;
        $this->withLayout = $withLayout;
    }

    public function render()
    {
        $viewPath = $this->resolveView($this->view);

        extract($this->data);

        ob_start();

        include $viewPath;

        http_response_code(200);
        $content = ob_get_clean();

        if ($this->withLayout) {
            ob_start();

            include $this->resolveView('layouts/main');

            return ob_get_clean();
        }

        return $content;
    }

    protected function resolveView(string $view)
    {
        $viewPath = realpath(__DIR__ . "/../../views/{$view}.php");

        if (!file_exists($viewPath)) {
            throw new Exception('View not found');
        }

        return $viewPath;
    }

    public static function make(string $view, array $data = [], bool $withLayout = false)
    {
        return new static($view, $data, $withLayout);
    }

    public function __toString()
    {
        return $this->render();
    }
}
