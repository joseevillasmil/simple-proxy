<?php

function getUrl()
{
	if(isset($_GET['site']))
	{
		
		if(isset($GLOBALS['websites'][$_GET['site']]))
		{
			$GLOBALS['site'] = $_GET['site'];
			return $GLOBALS['websites'][$_GET['site']];
		}
		return null;
	}else{
		return null;
	}
}

function getParams()
{
	if(isset($_GET['p']))
	{
		return $_GET['p'];
	}else{
		return null;
	}
}


function getCache($item)
{
	if (!validUrl($item)) {
		$item = $GLOBALS['url']."/".$item;
	}
	
	$name = 'cache/'.md5($item);

	if(!file_exists($name))
	{	
		exec("wget -O $name $item");
	}
	
	return $name;
}

function getHtml($url)
{
	try{
		$name = "tmp/".uniqid();
		exec("wget -O $name $url");
		$html = file_get_contents($name);
		unlink($name);
		return $html;
		
	}catch(Exception $e)
	{
		return "";
	}

}

function validUrl($url)
{
	
	$file_headers = @get_headers($url);   
	if ($file_headers === false) {
		return false;
	}	
	return true;
}


function prepareHtml($html)
{
	preg_match_all('/src="(.*?)"/s', $html, $coincidencias);
	
	foreach($coincidencias[1] as $item)
	{	
			$img = getCache($item, $GLOBALS['url']);
			$html = str_replace($item, $img, $html);
		
	}
	
	preg_match_all("/src='(.*?)'/s", $html, $coincidencias);
	
	foreach($coincidencias[1] as $item)
	{	
			$img = getCache($item, $GLOBALS['url']);
			$html = str_replace($item, $img, $html);
		
	}


	preg_match_all('/href="(.*?)"/s', $html, $coincidencias);
	
	foreach($coincidencias[1] as $item)
	{	
			if (strpos($item, '.css') === false) {
				
				$aux = str_replace("http://", "", $item);
				$aux = str_replace("https://", "", $item);
				
				$array = explode("/", $aux);
				
				$array[0] = "";
				
				$parametros = implode("/", $array);
				
				$a = "?site=".$GLOBALS['site']."&p=".$parametros;
				
				$html = str_replace($item, $a, $html);
				
			}else
			{
				$css = getCache($item, $GLOBALS['url']);
				$html = str_replace($item, $css, $html);
			}
		
	} 
	
	preg_match_all("/href='(.*?)'/s", $html, $coincidencias);
	
	foreach($coincidencias[1] as $item)
	{	
			if (strpos($item, '.css') === false) {
				
				$aux = str_replace("http://", "", $item);
				$aux = str_replace("https://", "", $item);
				
				$array = explode("/", $aux);
				
				$array[0] = "";
				
				$parametros = implode("/", $array);
				
				$a = "?site=".$GLOBALS['site']."&p=".$parametros;
				
				
				$html = str_replace($item, $a, $html);
				
			}else
			{
				$css = getCache($item, $GLOBALS['url']);
				$html = str_replace($item, $css, $html);
			}
		
	} 
	return $html;
}