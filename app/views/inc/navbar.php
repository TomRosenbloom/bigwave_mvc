<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-3">
  <a class="navbar-brand" href="<?= URL_ROOT; ?>"><?= SITE_NAME; ?></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarsExampleDefault">

    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link" href="<?= URL_ROOT; ?>">Home</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="<?= URL_ROOT; ?>/admin/">Admin</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="<?= URL_ROOT; ?>/event/">Events</a>
      </li>
    </ul>

    <ul class="navbar-nav ml-auto">
        <?php if(isset($_SESSION['user_id'])){ ?>
            <li class="nav-item">
                <a class="nav-link" href="<?= URL_ROOT; ?>/user/logout">Logout</a>
            </li>
        <?php } else { ?>
            <li class="nav-item">
                <a class="nav-link" href="<?= URL_ROOT; ?>/user/register">Register</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= URL_ROOT; ?>/user/login">Login</a>
            </li>
        <?php } ?>
    </ul>

  </div>
</nav>
