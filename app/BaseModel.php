<?php

/**
 * Created by PhpStorm.
 * User: mrozk
 * Date: 05.10.16
 * Time: 17:26
 */
class BaseModel
{

    /**
     * @var mysqli
     */
    public $db;

    public function __construct()
    {
        $this->db = Application::getApplication()->getDb();
    }


    public function getValue($data, $key)
    {
        return isset($data[$key]) ? $data[$key] : null;
    }

    public function getInt($data, $key)
    {
        $val = $this->getValue($data, $key);
        if ($val) {
            $val = (int)$val;
        }

        return $val;
    }

    public function getString($data, $key)
    {
        $val = $this->getValue($data, $key);
        if ($val) {
            $val = filter_var($val, FILTER_SANITIZE_STRING);
        }

        return $val;
    }
}