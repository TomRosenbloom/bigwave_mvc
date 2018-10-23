<?php

// config
// database
// ...have copied over the database connection and config classes from previous app
// ...but where should I make database connection?
// have a trait that can be used by a model? (rather than a base class that
// always connects to db, since some models might not need db connection. If
// we extend a base model class that has db connection that we don't use, that violates
// one of the SOLID principles, forget which one now)

require __DIR__.'\..\vendor\autoload.php';

use core\Config;
$config = Config::getInstance();

$app = new App();
