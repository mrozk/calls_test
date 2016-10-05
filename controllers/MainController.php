<?php

class MainController extends BaseController
{

    public $models = [
        'RecallsModel'
    ];



    public function addAction()
    {
        $recalls = new RecallsModel();
        if(!$recalls->addRecall($_POST, $_FILES)){
            Application::getApplication()->pushMessage('Ошибка добавления отзыва');
        }else{
            Application::getApplication()->pushMessage('Отзыв успешно добавлен');
        }

        Application::getApplication()->redirect('index.php?r=main/index');
    }

    public function indexAction()
    {
        $user = Application::getApplication()->getUser();
        $recalls = new RecallsModel();
        $sort = isset($_GET['sort']) ? $_GET['sort'] : 'date';
        $recalls = $recalls->getRecalls(($user) ? true : false, $sort);

        return $this->view->render('index', [
            'recalls' => $recalls,
            'user' => $user,
            'sort' => $sort
        ]);
    }

    public function migrateAction()
    {
        // Оставляем открытым сугубо для теста
        $rec = new RecallsModel();
        if($rec->migrate()){
            echo 'Successful migrated';
        }
    }

}