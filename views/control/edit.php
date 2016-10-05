<form enctype="multipart/form-data" role="form" action="index.php?r=control/update" method="post">

    <div class="form-group">
        <h2>Правка отзыва</h2>
    </div>

    <div class="form-group">
        <label for="name">Имя</label>
        <input value="<?=$recall['name']?>" required name="name" type="text" class="form-control" id="name" placeholder="Введите имя">
        <!--<p class="help-block">Пример строки с подсказкой</p>-->
    </div>

    <div class="form-group">
        <label for="email">Email</label>
        <input value="<?=$recall['email']?>" required type="email" name="email" class="form-control" id="email" placeholder="Введите email">
        <!--<p class="help-block">Пример строки с подсказкой</p>-->
    </div>

    <div class="form-group">
        <label for="text">Текст</label>
        <textarea  required name="text" class="form-control" id="text"><?=$recall['text']?></textarea>
        <!--<p class="help-block">Пример строки с подсказкой</p>-->
    </div>

    <input type="hidden" name="id" value="<?=$recall['id']?>">


    <!--<div class="checkbox">
        <label><input type="checkbox"> Чекбокс</label>
    </div>-->

    <button type="submit" class="btn btn-success">Принять</button>
</form>