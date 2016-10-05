<?php

/**
 * Created by PhpStorm.
 * User: mrozk
 * Date: 05.10.16
 * Time: 19:03
 */
class UserModel extends BaseModel
{

    public $id;
    public $name;

    public function getById($id)
    {
        $res = $this->db->query('SELECT * FROM users WHERE id=' . $id);
        $raw = $res->fetch_assoc();
        if (!$raw) {
            return null;
        }

        $this->id = $raw['id'];
        $this->name = $raw['name'];

        return $this;
    }

    public function login($data)
    {
        $name = $this->getString($data, 'name');
        $password = $this->getString($data, 'password');

        $res = $this->db->query('SELECT * FROM users WHERE name="' . $name . '"');
        $raw = $res->fetch_assoc();
        if (!$raw) {
            return false;
        }

        if (!$this->checkPassword($raw['password'], $password)) {
            return false;
        }

        $this->id = $raw['id'];
        $this->name = $raw['name'];

        Application::getApplication()->setUser($this);

        return true;

    }

    public function getPassword($password)
    {
        return md5($password);
    }

    public function checkPassword($password, $value)
    {
        if (md5($value) == $password) {
            return true;
        }

        return false;
    }

    public function createUser($data)
    {

        $name = $this->getString($data, 'name');
        $password = $this->getString($data, 'password');
        if (empty($name) || empty($password)) {
            return false;
        }

        $password = $this->getPassword($password);

        $query = 'INSERT INTO users(`name`, `password`) VALUES ("' .
            $name . '", "' .
            $password . '")';


        if ($this->db->query($query)) {
            return true;
        }

        return false;
    }
}