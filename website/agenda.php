<?php

require('bootstrap.php');

$template = $twig->loadTemplate('agenda.html');
echo $template->render(array());

include(LIB_DIR . 'toc.php');
