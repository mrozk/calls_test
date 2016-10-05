<?php

/**
 * Created by PhpStorm.
 * User: mrozk
 * Date: 05.10.16
 * Time: 14:50
 */
class View
{
    public $layout;
    public $defaultContext;

    /**
     * View constructor.
     * @param $layout
     * @param $defaultContext
     */
    public function __construct($layout, $defaultContext)
    {
        $this->layout = $layout;
        $this->defaultContext = $defaultContext;
    }


    public function render($viewName, $params = [], $context = null, $layout = null)
    {

        if (!$layout) {
            $layout = $this->layout;
        }

        $app = Application::getApplication();
        $base = Application::getApplication()->getPath();
        $layoutPath = $base . 'views/layouts/' . $layout . '.php';

        if (!file_exists($layoutPath)) {
            $app->end('Шаблон не найден');
        }

        $content = $this->renderAjax($viewName, $params, $context);

        ob_start();
        require_once $layoutPath;
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

    public function renderAjax($viewName, $params = [], $context = null)
    {
        if (!$context) {
            $context = $this->defaultContext;
        }
        $app = Application::getApplication();
        $base = Application::getApplication()->getPath();
        $viewPath = $base . 'views/' . $context . '/' . $viewName . '.php';
        if (!file_exists($viewPath)) {
            $app->end('Вид не найден');
        }
        extract($params);

        ob_start();
        require_once $viewPath;
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }


}