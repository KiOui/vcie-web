<?php

// Get current time as we did at start
$mtime = microtime();
$mtime = explode(" ", $mtime);
$mtime = $mtime[1] + $mtime[0];
// Store end time in a variable
$tend = $mtime;
// Calculate the difference
$totaltimems = ($tend - $TIME_START) * 1000;
// Output result
printf("<!-- %f ms -->", $totaltimems);
