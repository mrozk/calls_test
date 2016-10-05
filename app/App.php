<?php
require_once 'BaseController.php';
require_once 'View.php';
require_once 'BaseModel.php';
require_once 'UserModel.php';

class Application
{

    public $config;
    public $action = 'index';
    private $controller = 'main';
    private $delimiter = '/';
    private $db;

    public static $current;
    private $user;

    public function getConfig($key)
    {
        return isset($this->config[$key]) ? $this->config[$key] : null;
    }

    public function getPath()
    {
        return dirname(__DIR__) . DIRECTORY_SEPARATOR;
    }

    public function __construct($config)
    {
        session_start();
        $this->config = $config;
        self::$current = $this;
    }

    public static function getApplication()
    {
        return self::$current;
    }

    public function getDb()
    {
        if (!$this->db) {

            $this->db = new mysqli(
                $this->config['host'],
                $this->config['user'],
                $this->config['password'],
                $this->config['db']
            );
            if ($this->db->connect_errno) {
                $this->end('DB not available');
            }

        }

        return $this->db;
    }

    public function redirect($location)
    {
        header('Location: ' . $location);
    }

    /**
     * @return BaseController
     */
    private function getController()
    {
        $controller = ucfirst(strtolower($this->controller)) . 'Controller';
        $actionPath = $this->getPath() . 'controllers/' . $controller . '.php';

        if (!file_exists($actionPath)) {
            $this->end('Controller not found');
        }

        require_once $actionPath;

        return new $controller();

    }

    public function run()
    {
        $this->initCommands();
        $controller = $this->getController();
        $controller->runAction($this->action);
    }

    protected function initCommands()
    {
        if (isset($_REQUEST['r'])) {
            $r = $_REQUEST['r'];
            $pos = strpos($r, $this->delimiter);
            if ($pos === false) {
                $this->controller = $r;
            } else {
                $parts = explode($this->delimiter, $r);
                $this->controller = $parts[0];
                $this->action = $parts[1];
            }
        }
    }

    public function end($string = '')
    {
        echo $string;
        exit;
    }

    public function getMessage()
    {
        if (isset($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            $_SESSION['flash'] = null;
            return $flash;
        }
    }

    public function pushMessage($message)
    {
        if (!isset($_SESSION['flash'])) {
            $_SESSION['flash'] = [];
        }
        array_push($_SESSION['flash'], $message);
    }

    public function setUser(UserModel $user)
    {
        $_SESSION['id'] = $user->id;
        $this->user = $user;
    }

    public function getUser()
    {

        if ($this->user) {
            return $this->user;
        }

        if (!isset($_SESSION['id'])) {
            return null;
        }

        $userModel = new UserModel();
        $this->user = $userModel->getById($_SESSION['id']);

        if (!$this->user) {
            return null;
        }

        return $this->user;
    }

}
