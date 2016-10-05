<?php

abstract class BaseController
{

    protected $view;

    protected $models = [];

    protected $layout = 'main';

    private $id;

    public function __construct()
    {
        $this->id = strtolower(str_replace('Controller', '', get_class($this)));
        $this->view = new View($this->layout, $this->id);
        $path = Application::getApplication()->getPath();
        // Тянем модели
        if (count($this->models) > 0) {
            foreach ($this->models as $item) {
                $model = $path . 'models/' . $item . '.php';
                if (!file_exists($model)) {
                    Application::getApplication()->end('Model not found');
                }
                require_once $model;
            }
        }
    }

    public function before()
    {

    }

    public function after($result)
    {

    }


    public function runAction($action)
    {
        $methodName = $action . 'Action';

        if (!method_exists($this, $methodName)) {
            Application::getApplication()->end('Controller action not found');
        }
        $this->before();
        $result = $this->$methodName();
        $this->after($result);

        echo $result;
    }

}