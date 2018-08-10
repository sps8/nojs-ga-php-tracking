<?php
### nojs-ga-php-tracking ###
#
# Serverside-Google-Analytics-Tracking without cookies and javascript 
# Use PHP and Google Measurement Protocol (https://developers.google.com/analytics/devguides/collection/protocol/v1/parameters)
#
# Usage: 
# > include this script with include('YOURPATH/stat.php'); in the index.php and or other pages to track
# > change values below

/* Send Data */
function sendstat($d) {
$ga = array(
	 'v' 	=> 1 # Version
	,'aip'	=> 1 # Anonymize IP
	,'tid'	=> 'UA-XXXXXX-1' # YOUR Google UA
	,'cid'	=> md5($_SERVER['REMOTE_ADDR']) # CID:  You can use session_id() or other fingerprint methods 
);
$d = array_merge($ga, $d);
$url = 'http://www.google-analytics.com/collect';
$ch = curl_init();
	curl_setopt($ch,CURLOPT_USERAGENT, $d['ua']);
	curl_setopt($ch,CURLOPT_URL, $url);
	curl_setopt($ch,CURLOPT_HTTPHEADER,array('Content-type: application/x-www-form-urlencoded'));
	curl_setopt($ch,CURLOPT_HTTP_VERSION,CURL_HTTP_VERSION_1_1);
	curl_setopt($ch,CURLOPT_POST, TRUE);
	curl_setopt($ch,CURLOPT_POSTFIELDS, utf8_encode(http_build_query($d)) );
	curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
curl_exec($ch);
curl_close($ch);
}

/* event tracking 
Example for jquery event fire:
 function ga(s,t,ec,ea,el,ev){$.ajax({type:"POST",url:"YOURPATH/stat.php",data:{"type":t,"ec":ec,"ea":ea,"el":el,"ev":ev}})}
 ga('send','event','CATEGORIE','ACTION','LABEL','VALUE')
*/
if($_POST['type']=='event'){
$sdata = array(
	 't'	=> 'event'
	,'ec'	=> $_POST['ec']	# Categorie
	,'ea'	=> $_POST['ea']	# Action
	,'el'	=> $_POST['el']	# Label
	,'ev'	=> $_POST['ev']	# Value
);
sendstat($sdata);
}
/* Example for e-commerce tracking */
else if($_GET['checkout']){ 
$sdata = array(
	 't' 	=> 'transaction'
	,'ti'	=> $_SESSION['Transaction ID']
	,'ta'	=> $_SESSION['Transaction Affiliation']
	,'tr' 	=> $_SESSION['Transaction Revenue']
	# ... more values see https://developers.google.com/analytics/devguides/collection/protocol/v1/parameters#ecomm
);
sendstat($sdata);
$sdata = array(
	 't' 	=> 'item'
	,'ti'	=> $_SESSION['Transaction ID']
	,'in'	=> $_SESSION['Item Name']
	,'ic'	=> $_SESSION['Item Code']
	,'ip' 	=> $_SESSION['Item Price']
	,'iq' 	=> $_SESSION['Item Quantity']
	,'iv' 	=> $_SESSION['Item Category']
);
sendstat($sdata);
}
/* Page Tracking */
else {
$sdata = array(
	 't'  => 'pageview'
	,'dt' => html_entity_decode($PAGETITLE) # Change to your pagetitle variable
	,'dh' => $_SERVER["HTTP_HOST"]
	,'dp' => $_SERVER['REQUEST_URI']
	,'dr' => $_SERVER['HTTP_REFERER']
	,'uip'=> $_SERVER['REMOTE_ADDR']
	,'ua' => $_SERVER['HTTP_USER_AGENT']
	,'ul' => substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2)
);
if(isset($_REQUEST['utm_source']))	$sdata['cs']=$_REQUEST['utm_source'];
if(isset($_REQUEST['utm_medium']))	$sdata['cm']=$_REQUEST['utm_medium'];
if(isset($_REQUEST['utm_campaign']))$sdata['cn']=$_REQUEST['utm_campaign'];
if(isset($_REQUEST['utm_term']))	$sdata['ck']=$_REQUEST['utm_term'];
if(isset($_REQUEST['utm_content']))	$sdata['cc']=$_REQUEST['utm_content'];
sendstat($sdata);
}
?>