<?php

echo "this is the bootstrap file<br>";
// this will be my bootstrapper, in which I'm going to:
// run autoloader
// do some config... (such as?)
// parse the url and dispatch the request
// that seems like a lot to do in one file, but how else can you do it?

require __DIR__.'\..\vendor\autoload.php';

$app = new App();
