<?php
	$files   = glob(dirname(__FILE__) . "/img/*");
	$reverse = array_reverse($files);
	$latest  = array_slice($reverse, 0, 10);
	foreach($latest as $file){
		$file = explode('/', $file);
		$file = array_pop($file);
		echo "<img src=\"/img/{$file}\" /><br />";
	}