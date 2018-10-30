<?php require APP_ROOT . '/views/inc/header.php'; ?>

    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card card-body bg-light mt-5">
                <h2>Register</h2>
                <form class="" action="<?= URL_ROOT; ?>/user/register" method="post">
                    <div class="form-group">
                        <label for="name">Name: <sup>*</sup></label>
                        <input type="text" name="name"
                        class="form-control form-control-lg <?php echo (!empty($data['name_err'])) ? 'is-invalid' : ''; ?>"
                        value="<?= $data['name']; ?>">
                        <span class="invalid-feedback">
                            <?php
                            foreach($data['name_err'] as $error){
                                echo $error, "<br>";
                            }
                            ?>
                        </span>
                    </div>
                    <div class="form-group">
                        <label for="email">Email: <sup>*</sup></label>
                        <input type="email" name="email"
                        class="form-control form-control-lg <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>"
                        value="<?= $data['email']; ?>">
                        <span class="invalid-feedback">
                            <?php
                            foreach($data['email_err'] as $error){
                                echo $error, "<br>";
                            }
                            ?>
                        </span>
                    </div>
                    <div class="form-group">
                        <label for="password">Password: <sup>*</sup></label>
                        <input type="password" name="password"
                        class="form-control form-control-lg <?php echo (!empty($data['pwd_err'])) ? 'is-invalid' : ''; ?>"
                        value="<?= $data['password']; ?>">
                        <span class="invalid-feedback">
                            <?php
                            foreach($data['pwd_err'] as $error){
                                echo $error, "<br>";
                            }
                            ?>
                        </span>
                    </div>

                    <div class="row">
                        <div class="col">
                            <input type="submit" value="Register" class="btn btn-success btn-block">
                        </div>
                        <div class="col">
                            <a href="<?= URL_ROOT; ?>/user/login" class="btn btn-light btn-block">Login</a>
                        </div>
                    </div>
                </form>
            </div>

        </div>

    </div>

<?php require APP_ROOT . '/views/inc/footer.php'; ?>
