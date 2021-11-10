<?php

// Get current time
$mtime = microtime();
// Split seconds and microseconds
$mtime = explode(" ",$mtime);
// Create one value for start time
$mtime = $mtime[1] + $mtime[0];
// Write start time into a variable
$TIME_START = $mtime; 
