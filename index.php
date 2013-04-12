<?php
	//
	// Allow CORS access
	//
	if(isset($_SERVER['HTTP_ORIGIN'])){
		header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
		header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
		header('Access-Control-Max-Age: 1000');
		header('Access-Control-Allow-Headers: Content-Type');
	}

	//
	// Validate the request
	//
	if(isset($_POST['source']) && isset($_POST['hash']) && isset($_POST['layout'])){
		// Check the layout
		if(in_array($_POST['layout'], array('dot', 'neato', 'twopi', 'circo', 'fdp', 'sfdp'))){
			// Check the hash
			$hash = md5($_POST['source']);
			if($hash == $_POST['hash']){
				// Define our filenames
				$dir = dirname(__FILE__);
				$src = "{$dir}/src/{$hash}.{$_POST['layout']}";
				$img = "{$dir}/img/{$hash}.{$_POST['layout']}";
				// Write the source into a file
				file_put_contents($src, $_POST['source']);
				// Now create the png
				$gviz = shell_exec($_POST['layout'] . ' -Tpng ' . escapeshellarg($src) . ' -o ' . escapeshellarg($img));
				// All good, let PHP output a 200 signal
				exit();
			}
		}
	}

	//
	// Since this page acts as a simple router for missing pages too, figure out the response
	//
	if(strpos($_SERVER['REQUEST_URI'], 'img') !== FALSE || strpos($_SERVER['REQUEST_URI'], 'src') !== FALSE){
		// 408 - means that the server may try for this resource again later
		header($_SERVER["SERVER_PROTOCOL"] . " 408 Request Timeout");
	} else if($_SERVER['REQUEST_URI'] == '/' || $_SERVER['REQUEST_URI'] == ''){
		// 302 - send them to my info page
		header("Location: http://oodavid.com/gviz/");
	} else {
		// 404 - meh
		header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found"); 
	}