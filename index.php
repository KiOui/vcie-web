<?php

require('bootstrap.php');

$h2o = new H2o(TEMPLATE_DIR . 'index.html');
echo $h2o->render();
