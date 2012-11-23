<?php

require('bootstrap.php');

$h2o = new H2o('index.html', $H2O_OPTIONS);
echo $h2o->render();
