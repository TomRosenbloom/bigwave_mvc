<?php require APP_ROOT . '/views/inc/header.php'; ?>
<?php use App\SessionHelper; ?>
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card card-body bg-light mt-5">
                <?php SessionHelper::flash('register_success'); ?>
                <h2>Login</h2>
                <form class="" action="<?= URL_ROOT; ?>/user/login" method="post">
                    <div class="form-group">
                        <label for="email">Email: <sup>*</sup></label>
                        <input type="email" name="email"
                        class="form-control form-control-lg <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>"
                        value="<?= $data['email']; ?>">
                        <span class="invalid-feedback"><?= $data['email_err']; ?></span>
                    </div>
                    <div class="form-group">
                        <label for="password">Password: <sup>*</sup></label>
                        <input type="password" name="password"
                        class="form-control form-control-lg <?php echo (!empty($data['pwd_err'])) ? 'is-invalid' : ''; ?>"
                        value="<?= $data['password']; ?>">
                        <span class="invalid-feedback"><?= $data['pwd_err']; ?></span>
                    </div>

                    <div class="row">
                        <div class="col">
                            <input type="submit" value="Login" class="btn btn-success btn-block">
                        </div>
                        <div class="col">
                            <a href="<?= URL_ROOT; ?>/user/register" class="btn btn-light btn-block">Register</a>
                        </div>
                    </div>
                </form>
            </div>

        </div>

    </div>

<?php require APP_ROOT . '/views/inc/footer.php'; ?>
