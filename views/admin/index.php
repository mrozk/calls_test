<div class="row">
    <?php
    $messages = Application::getApplication()->getMessage();
    foreach ($messages as $message) {
        ?>
        <p style="padding: 20px" class="bg-warning"><?=$message?></p>
        <?php
    }
    ?>
</div>
<form class="form-signin" action="index.php?r=admin/login" method="post">
    <h2 class="form-signin-heading">Вход</h2>
    <label for="inputEmail" class="sr-only">Email address</label>
    <input type="text" name="name" id="inputEmail" class="form-control" placeholder="Email address" required autofocus>
    <label for="inputPassword" class="sr-only">Password</label>
    <input type="password"  name="password" id="inputPassword" class="form-control" placeholder="Password" required>
    <button class="btn btn-lg btn-primary btn-block" type="submit">Войти</button>
</form>