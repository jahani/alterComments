<?php
//error_reporting(E_ALL);
//ini_set("display_errors", 1);

header("Content-Type: text/html; charset=UTF-8");

function getTitle($Url){
    $str = file_get_contents($Url);
    if(strlen($str)>0){
        preg_match("/\<title\>(.*)\<\/title\>/",$str,$title);
        return $title[1];
    }
}

function lrmv($str, $prefix) {
	if (substr($str, 0, strlen($prefix)) == $prefix) {
		return substr($str, strlen($prefix));
	} else {
		return $str;
	}
}

function correctRqst($str)
{
	$str = urldecode($str);
	$str = strtolower($str);
	
	$str = lrmv($str,'/url/');
	$str = lrmv($str,'https://');
	$str = lrmv($str,'http://');
	$str = lrmv($str,'www.'); // TODO: isn't it a domain name?
	
	
	$str = rtrim($str,'/'); //TODO: suffix remove
	
	// Remove ?google_comment_id=
	$param = '?google_comment_id=';
	$pos = strpos($str, $param);
	$endpoint = $pos;
	if ($pos!== false) {
		$str = substr($str,0,$endpoint );
	}
	
	
	return $str;
}
$hostDomain = "http://altercomments.ebnes.ir/url/";
$commentsDomain = "http://altercomments.ebnes.ir/url"; // $domain = "http://$_SERVER[HTTP_HOST]"; *.ebnes.ir
$rqst = correctRqst($_SERVER['REQUEST_URI']);
$comments_url = "$commentsDomain/$rqst";
//$actual_link = urldecode("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
//$page = $_GET["page"];
//$rqst = urldecode($_SERVER[REQUEST_URI]);
list($domain)=explode('/', $rqst);
list($topLD,$secondLD)=explode('.',strrev($domain),3);
$topLD = strrev($topLD);
$secondLD= strrev($secondLD);
$rootDomain = "$secondLD.$topLD";
?>

<html>
<head>
<title>Alter Comments<?php
	if (!empty($rqst)) {
		echo ' - '.$rqst;
	}
	?>
</title>
</head>

<link href='http://fonts.googleapis.com/css?family=Lobster|Indie+Flower' rel='stylesheet' type='text/css'>
<link href='http://altercomments.ebnes.ir/url_style.css' rel='stylesheet' type='text/css'>

<div class="container-fluid">
  <div class="container">
    <div class="header header-top">
      <a href="http://altercomments.ebnes.ir/"><h1>Alter Comments</h1></a>
      <span class="options"> on <?php
        if ($rqst==$rootDomain) {
        	echo '<a href="'.$hostDomain.$rootDomain.'"><span class="link">'.$rootDomain.'</span></a>';
        } else {
        	if ($rqst==$domain) {
        		echo 'a <a href="'.$hostDomain.$rootDomain.'"><span class="link">'.$rootDomain.'</span></a> subdomain.';
	        } else {
        		echo 'a <a href="'.$hostDomain.$domain.'"><span class="link">'.$domain.'</span></a> page.';
	        }
	}?>
      </span>
    </div>

    <div class="comments">
      <script src="https://apis.google.com/js/plusone.js">
      </script>
      <div class="g-comments"
          data-href="<?php echo $comments_url; ?>"
          data-width="642"
          data-first_party_property="BLOGGER"
          data-view_type="FILTERED_POSTMOD">Loading Script...
      </div>
    </div>
    
    <div class="footer">
      <p><strong>URL:</strong> <?php echo '<a href="//'.$rqst.'"><span class="link">'.$rqst.'</span></a>' . ' (<a href="'.$comments_url.'"><span class="link">Alter Comments</span></a>)'; ?></p>
    </div>

  </div>
</div>

</html>