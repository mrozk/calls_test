<?php


class AdminController extends BaseController
{

    public $layout = 'admin';

    public function loginAction()
    {

        if (Application::getApplication()->getUser()) {
            Application::getApplication()->redirect('index.php?r=main/index');
            return null;
        }

        $user = new UserModel();
        if (!$user->login($_POST)) {
            Application::getApplication()->pushMessage('Ошибка входа');
            Application::getApplication()->redirect('index.php?r=admin/index');
        } else {
            Application::getApplication()->redirect('index.php?r=main/index');
        }
    }


    public function generateAction()
    {
        /*$user = new UserModel();
        $user->createUser(['name' => 'admin', 'password' => '123']);*/
    }

    public function indexAction()
    {
        if (Application::getApplication()->getUser()) {
            Application::getApplication()->redirect('index.php?r=main/index');
            return null;
        }

        return $this->view->render('index', [
            //'recalls' => $recalls
        ]);
    }

}