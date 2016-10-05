<?php

/**
 * Created by PhpStorm.
 * User: mrozk
 * Date: 05.10.16
 * Time: 20:27
 */
class ControlController extends BaseController
{

    public $user;

    public $models = [
        'RecallsModel'
    ];

    public function __construct()
    {
        parent::__construct();
        $this->user = Application::getApplication()->getUser();
        if(!$this->user){
            Application::getApplication()->redirect('index.php');
        }
    }


    public function editAction()
    {
        $recalls = new RecallsModel();
        $recall = $recalls->getById($_GET);
        return $this->view->render('edit', [
            'recall' => $recall
        ]);
    }



    public function updateAction(){
        $recalls = new RecallsModel();
        if($recalls->update($_POST)){
            $message = 'ЗАпись успешно обновлена';
        }else{
            $message = 'Ошибка обновления записи';
        }
        Application::getApplication()->pushMessage($message);
        Application::getApplication()->redirect('index.php');
    }


    public function unpublishAction(){
        $recalls = new RecallsModel();
        if($recalls->publish($_GET, 0)){
            $message = 'ЗАпись успешно обновлена';
        }else{
            $message = 'Ошибка обновления записи';
        }
        Application::getApplication()->pushMessage($message);
        Application::getApplication()->redirect('index.php');
    }

    public function publishAction(){
        $recalls = new RecallsModel();
        if($recalls->publish($_GET, 1)){
            $message = 'ЗАпись успешно обновлена';
        }else{
            $message = 'Ошибка обновления записи';
        }
        Application::getApplication()->pushMessage($message);
        Application::getApplication()->redirect('index.php');
    }
}