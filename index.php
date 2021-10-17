<?php
	include(__DIR__."/app/config.php");
	include(__DIR__."/app/functions.php");
	
	#get the URL
	$site = null;
	$url = getUrl();
	$params = getParams();
	
	$http_request = $url."/".$params;
	
	if(!empty($url))
	{
			#Find the html code.
			$html = getHtml($http_request);
	
			#Lets process it.
			$html = prepareHtml($html);
			
			#return de HTML code.
			echo $html;
	}
	
	echo "No se pudo procesar";
	exit();