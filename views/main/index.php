<?php
/** @var RecallsModel[] $recalls */
/** @var UserModel $user */
?>


    <div class="row">
        <?php
        $messages = Application::getApplication()->getMessage();
        foreach ($messages as $message) {
            ?>
            <p style="padding: 20px" class="bg-warning"><?= $message ?></p>
            <?php
        }
        ?>
    </div>

    <div class="row">

        <? if ($user): ?>
            <div class="form-group"><h2>Панель администратора</h2></div>
        <? endif; ?>

        <div class="form-group"><h2>Список отзывов</h2></div>
        <div class="form-group">
            <?php
            if($sort == 'name'){
                $sortType = 'имени';
            }else if($sort == 'email'){
                $sortType = 'email';
            }else{
                $sortType = 'дате';
            }
            ?>
            <h3>Сортировка по "<?=$sortType?>":</h3>
            <a href="index.php?index.php?r=main/index&sort=name">Имя</a>
            <a href="index.php?index.php?r=main/index&sort=email">Email</a>
            <a href="index.php?index.php?r=main/index&sort=date">Дата</a>
        </div>


        <ul class="list-group">
            <? foreach ($recalls as $item): ?>
                <li class="list-group-item">
                    <div class="row">
                        <div style="text-align: center" class="col-md-6">
                            <?php
                            $path = Application::getApplication()->getPath() .
                                Application::getApplication()->getConfig('upload_dir') . $item['photo_path'];
                            $image = (!file_exists($path)) ? 'noimage.png' : $item['photo_path'];
                            ?>
                            <img style="max-width: 320px" src="assets/app/img/<?= $image ?>">
                            <br />
                            <?if($item['is_moderated']):?>
                                Отредактировано администратором
                            <?endif?>
                        </div>
                        <div class="col-md-6">

                            <div class="row">
                                <span class="has-feedback">Имя:</span>
                                <?= $item['name'] ?>
                                <br />
                                <span class="has-feedback">Email:</span>
                                <?= $item['email'] ?>

                                <?if($user):?>
                                <div style="float: right; padding: 0px 20px 0 0">

                                    <? if ($item['is_approved'] == 0): ?>
                                        <a href="index.php?r=control/publish&id=<?= $item['id'] ?>"
                                            <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                                        </a>
                                    <? else: ?>
                                        <a href="index.php?r=control/unpublish&id=<?= $item['id'] ?>"
                                            <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                        </a>
                                    <?endif?>
                                    <a href="index.php?r=control/edit&id=<?= $item['id'] ?>"
                                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                                    </a>

                                    <span class="glyphicon">#<?=$item['id']?></span>
                                </div>
                                <?endif?>
                            </div>

                            <div class="row">
                                <?= $item['text'] ?>
                            </div>

                        </div>

                    </div>
                </li>
            <? endforeach ?>

            <li style="display: none"  class="preview list-group-item">
                <div class="row">
                    <div style="text-align: center" class="col-md-6">
                        <?php
                        $path = Application::getApplication()->getPath() .
                            Application::getApplication()->getConfig('upload_dir') . $item['photo_path'];
                        ?>
                        <img style="max-width: 320px" src="assets/app/img/noimage.png">
                    </div>
                    <div class="col-md-6">

                        <div class="row">
                            <span class="has-feedback">Имя:</span>
                            <span class="prev-name"><?= $item['name'] ?></span>
                            <br />
                            <span class="has-feedback">Email:</span>
                            <span class="prev-email"><?= $item['email'] ?></span>

                        </div>

                        <div class="row">
                            <span class="prev-text">
                            <?= $item['text'] ?>
                            </span>
                        </div>

                    </div>

                </div>
            </li>


        </ul>

    </div>

<? if (!$user): ?>
    <form enctype="multipart/form-data" role="form" action="index.php?r=main/add" method="post">

        <div class="form-group">
            <h2>Оставьте пожайлуста отзыв</h2>
        </div>

        <div class="form-group">
            <label for="name">Имя</label>
            <input required name="name" type="text" class="form-control" id="name" placeholder="Введите имя">
            <!--<p class="help-block">Пример строки с подсказкой</p>-->
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input required type="email" name="email" class="form-control" id="email" placeholder="Введите email">
            <!--<p class="help-block">Пример строки с подсказкой</p>-->
        </div>

        <div class="form-group">
            <label for="text">Текст</label>
            <textarea required name="text" class="form-control" id="text"></textarea>
            <!--<p class="help-block">Пример строки с подсказкой</p>-->
        </div>


        <div class="form-group">
            <label for="exampleInputFile">Добавить картинку</label>
            <input type="file" name="file" id="exampleInputFile">
            <!--  <p class="help-block">Example block-level help text here.</p>-->
        </div>


        <!--<div class="checkbox">
            <label><input type="checkbox"> Чекбокс</label>
        </div>-->

        <button type="submit" class="btn btn-success">Принять</button>
        <a href="javascript:void(0)"  class="btn preview-btn">Предпросмотр</a>
    </form>
<? endif; ?>

<script type="text/javascript">
    (function(){
        $(document).ready(function(){
            $('.preview-btn').on('click', function(){
                $('.preview').css('display', 'block');
                $('.prev-name').html($('#name').val());
                $('.prev-email').html($('#email').val());
                $('.prev-text').html($('#text').val());
            });

        });

    })()
</script>
