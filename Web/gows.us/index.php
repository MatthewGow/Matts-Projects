<? ob_start(); ?>
<?php /* index.php ( URL shrinking implementation ) */

require_once 'includes/conf.php'; // <- site-specific settings
require_once 'includes/lilurl.php'; // <- lilURL class file

$lilurl = new lilURL();
$msg = '';
$msg1 = '';
$msg2 = '';
$msg3 = '<font size="10em" color="#7B7B7B"><b>Welcome!</b></font><br /><font size="4em" color="#7B7B7B">If you are new here, Thanks<br />for visiting! To use the<br />site either enter a URL<br />to shrink or select a file<br />to upload! Please keep<br />your uploads clean!</font><br />';
$msg4 = '';
$msg5 = '';
$hack_detection='';
$url='';

// if the form has been submitted
if ( isset($_POST['longurl']) )
{
	// escape bad characters from the user's url
	$longurl = trim(mysql_escape_string($_POST['longurl']));

	// set the protocol to not ok by default
	$protocol_ok = false;
	
	// if there's a list of allowed protocols, 
	// check to make sure that the user's url uses one of them
	if ( count($allowed_protocols) )
	{
		foreach ( $allowed_protocols as $ap )
		{
			if ( strtolower(substr($longurl, 0, strlen($ap))) == strtolower($ap) )
			{
				$protocol_ok = true;
				break;
			}
		}
	}
	else // if there's no protocol list, screw all that
	{
		$protocol_ok = true;
	}
		
	// add the url to the database
	if ( $protocol_ok && $lilurl->add_url($longurl) )
	{
		if ( REWRITE ) // mod_rewrite style link
		{
			$url = 'http://'.$_SERVER['SERVER_NAME'].'/l/'.$lilurl->get_id($longurl);
		}
		else // regular GET style link
		{
			$url = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'].'?id='.$lilurl->get_id($longurl);
		}
		$msg1 = '<font size="5em" color="#7B7B7B"><b>Here is your Link!</b></font><br />';
        $msg3 = '';
		$msg = '<font color="#FFFFFF">Your short URL:</font> <input type="text" value="'.$url.'"></input>';
		$msg4 = '<font color="#FFFFFF">Link for websites:</font> <input type="text" value="<a href=&quot;'.$url.'&quot;>'.$url.'</a>"></input>';
		$msg5 = '<font color="#FFFFFF">Link for forums:</font> <input type="text" value="[url]'.$url.'[url]"></input>';
	}
	elseif ( !$protocol_ok )
	{
		$msg = '<p class="error">Invalid protocol!</p>';
	}
	else
	{
		$msg = '<p class="error">Creation of your short URL failed for some reason.</p>';
	}
}
else // if the form hasn't been submitted, look for an id to redirect to
{
	if ( isSet($_GET['id']) ) // check GET first
	{
		$id = mysql_escape_string($_GET['id']);
	}
	elseif ( REWRITE ) // check the URI if we're using mod_rewrite
	{
		$explodo = explode('/', $_SERVER['REQUEST_URI']);
		$id = mysql_escape_string($explodo[count($explodo)-1]);
	}
	else // otherwise, just make it empty
	{
		$id = '';
	}
	
	// if the id isn't empty and it's not this file, redirect to it's url
	if ( $id != '' && $id != basename($_SERVER['PHP_SELF']) )
	{
		$location = $lilurl->get_url($id);
		
		if ( $location != -1 )
		{
			header('Location: '.$location);
		}
		else
		{
			$msg = 'Sorry, but that short URL is not in our database.';
		}
	}
}

// print the form

?>
<?php
 //This function separates the extension from the rest of the file name and returns it 
 function findexts ($filename) 
 { 
	 $filename = strtolower($filename) ; 
	 $exts = split("[/\\.]", $filename) ; 
	 $n = count($exts)-1; 
	 $exts = $exts[$n]; 
	 return $exts; 
 } 
 //This applies the function to our file  
 $uname = isset($_FILES['uploaded']['name']) ? $_FILES['uploaded']['name'] : '';
 $ext = findexts ($uname) ; 
 ?>
 <?php
 if(isset($_SERVER['CONTENT_LENGTH']) && $_SERVER['CONTENT_LENGTH']>104857600)
 {
 		$msg = '<p class="error">ERROR: Sorry, to keep our storage from becoming full, <br />we limit our upload file sizes to 100mb.</p>';
		$msg2 = '';
		$msg3 = '';
		$hack_detection=true;
 		$ok=0;
 } 
 
 ?>
 <?php
$blacklist = array(".php", ".phtml", ".php3", ".php4", ".js", ".shtml", ".pl" ,".py" ,".html" ,".htm" ,".php2" ,".php5" ,".pwml" ,".inc" ,".asp" ,".aspx" ,".ascx" ,".jsp" ,".cfm" ,".cfc" ,".pl" ,".bat" ,".com" ,".dll" ,".vbs" ,".js" ,".cgi" ,".htaccess" ,".asis" ,".sh" ,".shtml" ,".shtm" ,".phtm");
foreach ($blacklist as $file)
{
	$uname = isset($_FILES['uploaded']['name']) ? $_FILES['uploaded']['name'] : '';
	if(preg_match("/$file\$/i", $uname))
	{
		$msg = '<p class="error">ERROR: To prevent Script Kiddies, some file types are restricted. </br> If you still wish to send this file, try putting it in a *.rar or *.zip container! </p>';
		$msg2 = '';
		$msg3 = '';
		$hack_detection=true;
	}
}
if($hack_detection==false)
{
	?>
	<?php
	$msg2 = '';
	$ran = rand();
	$ran2 = $ran.".";
	$target = "u/";
	$newdir = "u/".$ran;
	if(isset($_FILES['uploaded']['name'])==true)
	{
		if (!file_exists($newdir)) {
			mkdir($newdir, 0777, true);
		}
	}
	$target = $newdir."/". $uname;
    
	
	$ok=1;
	$tname = isset($_FILES['uploaded']['tmp_name']) ? $_FILES['uploaded']['tmp_name'] : '';
	if(move_uploaded_file($tname, $target))
	{
		$msg1 = '<font size="5em" color="#7B7B7B"><b>Here is your file!</b></font><br />';
        $msg3 = '';
		$msg = '<font color="#FFFFFF">Your file is at:</font> <input type="text" value="http://gows.us/'.$target.'"></input>';
		$msg4 = '<font color="#FFFFFF">Link for websites:</font> <input type="text" value="<a href=&quot;http://gows.us/'.$target.'&quot;>'.$url.'</a>"></input>';
		$msg5 = '<font color="#FFFFFF">Link for forums:</font> <input type="text" value="[url]http://gows.us/'.$target.'[/url]"></input>';
		
		
		$con = mysql_connect("localhost","null","null");
		if (!$con)
		  {
		  die('Could not connect: ' . mysql_error());
		  }
		mysql_select_db("upload_files", $con);
		//Set variables to add to db
		$ip = getenv("REMOTE_ADDR");
		$date  = date("Y-m-d H:i:s");
		$filename=substr($target, 2);
		$upurl='http://gows.us/' . $target;
		//Run the SQL Query
		$sql="INSERT INTO upload_info (date, url, filename, ip)

		VALUES

		('$date','$upurl','$filename','$ip')";

		 

		if (!mysql_query($sql,$con))

		  {

		  die('Error: ' . mysql_error());

		  }

		 

		mysql_close($con);


	
	
	}
	else 
	{
		$msg2 = '';
	}
	?>

	<?php
}
?>    

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html>
	<head>
		<link rel="shortcut icon" href="favicon.ico" type="image/x-ico" />
		<meta name="title" content="Gows.us" />
		<meta name="description" content="Free Helpful Web Scripts!" />
		<title>Gows.us : Free Helpful Web Scripts</title>
		<meta name="Description" content="Gows.us offers free helpful web scripts with no signups, including a url shrinker and a free public file dump!">
		<SCRIPT language="JavaScript1.2" src="main.js" type="text/javascript"></SCRIPT>
		<style type="text/css">
			body {
				font: .8em;
				text-align: center;
				color: #333;
				background-color: #fff;
				margin-top: 5em;
                font-family: Candara;
				background-color: #161616;
				/* background-image: url(images/img4.jpg); */
			}
			#submit
			{
				color: #FFF;
				background: #555;
				border: 2px outset #000
			}
		
			h1 {
				font-size: 2em;
				padding: 0;
				margin: 0;
			}

			h4 {
				font-size: 9px;
				color: #FFF;
			}
		
			form {
				width: 28em;
				background-color: #555;
				border: 1px solid #000;
				margin-left: auto;
				margin-right: auto;
				padding: 1em;
				color: white;
			}

			fieldset {
				border: 0;
				margin: 0;
				padding: 0;
			}
		
			a {
				color: #bbb;
				text-decoration: none;
				font-weight: bold;
			}

			a:visited {
				color: #07a;
			}

			a:hover {
				color: #c30;
			}

			.error, .success {
				font-size: 1.2em;
				font-weight: bold;
			}
			
			.error {
				color: #ff0000;
			}
			
			.success {
				color: green;
			}
			
			body,td,th {
				font-size: 0.8em;
				text-align: center;
				color: #000;
			}
			
			.longurl input[type="text"] {
			padding: 10px;
			color: #000;
			background-color: #272822;
			border: solid 1px #dcdcdc;
			transition: box-shadow 0.3s, border 0.3s;
			}
			.longurl input[type="text"]:focus,
			.longurl input[type="text"].focus {
			border: solid 1px #707070;
			box-shadow: 0 0 5px 1px #969696;
}


			/* Like button main text color */
			div.like span.connect_widget_text {color:#fff;}
			div.like div.connect_widget_confirmation {color:#fff;}
			div.like span.connect_widget_text a {color:#ffc6ff;}

			/* Hide all the comments box */
			div.comment_body {display:none;}
			div.like div.connect_widget_sample_connections {display:none;}
        </style>		

		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<script>

$(document).ready(function() { 
//

//show the progress bar only if a file field was clicked
    var show_bar = 0;
    $('input[type="file"]').click(function(){
        show_bar = 1;
    });

//show iframe on form submit
    $("#form1").submit(function(){

        if (show_bar === 1) { 
            $('#upload_frame').show();
            function set () {
                $('#upload_frame').attr('src','upload_frame.php?up_id=<?php echo $up_id; ?>');
            }
            setTimeout(set);
        }
    });
//

});

</script> 
	</head>
	<body onLoad="document.getElementById('longurl').focus()">
	<?php 
//get unique id
$up_id = uniqid(); 
?> 
	<DIV id="TipLayer" style="visibility:hidden;position:absolute;z-index:1000;top:-100;"></DIV>
	<SCRIPT language="JavaScript1.2" src="popstyle.js" type="text/javascript"></SCRIPT>   
    <table width="100%" border="0">
		<tr>
			<td colspan="2"><img src="images/img5.png" /><br /><font size="5em" color="#555">Free Helpful Web Scripts!</font></td>
		</tr>
		<tr>
			<td width="50%"><font size="5em" color="#7B7B7B"><b>Enter A Long URL To Shrink</b></font><br/>
				<form action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
					<fieldset>
						<label for="longurl">Enter a long URL:</label>
						<input type="text" name="longurl" id="longurl" value="http://" />
						<input type="submit" name="submit" id="submit" value="Shrink it!" />
					</fieldset>
				</form>
				
<!--Get jQuery-->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.0/jquery.js" type="text/javascript"></script>

<!--Progress Bar and iframe Styling-->
<link href="style_progress.css" rel="stylesheet" type="text/css" /> 
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.0/jquery.js" type="text/javascript"></script>

<!--display bar only if file is chosen-->

				
				<font size="5em" color="#7B7B7B"><b>or</b></font>
				<br /><font size="5em" color="#7B7B7B"><b>Select A File To Upload</b></font><br />
				<form enctype="multipart/form-data" action="<?php $self = isset($_FILES['PHP_SELFS']) ? $_FILES['PHP_SELFS'] : ''; echo $self?>" method="post" name="form1" id="form1">
					Please choose a file (<100mb):
					<input name="uploaded" type="file" id="uploaded" />
					<br />
					<input type="submit" value="Upload" name="Submit" id="submit" />
					<!--APC hidden field-->
    <input type="hidden" name="APC_UPLOAD_PROGRESS" id="progress_key" value="<?php echo $up_id; ?>"/>
<!---->
    <iframe id="upload_frame" name="upload_frame" frameborder="0" border="0" src="" scrolling="no" scrollbar="no" > </iframe>
<!----> 
				</form>

			</td>

			<td width="50%"><br />      
				<?php echo $msg1; ?><br /><br /><?php echo $msg; ?><?php echo $msg2; ?><br /><br />
				<?php echo $msg4; ?><?php echo $msg3; ?><br /><br />
				<?php echo $msg5; ?><br /><br /> 
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center">
				<h4>
					<?php 
					echo '<br>';
					echo '<font size="1em" color="#7B7B7B">DMCA requests: <a href="mailto:dmca@gows.us">dmca@gows.us</a>';
					echo '<br>';
					echo 'btw, your IP Address is: </b>';
					//Gets the IP address
					$ip = getenv("REMOTE_ADDR"); 
					echo $ip; 
					$isp = gethostbyaddr($ip);
					if ($isp) {
					echo '<br><b>';
					echo 'and your host address is: </b>' . $isp.'</font>';
					}
										
										
					//	<a href="http://dot5hosting.com/green/green-certified.bml?domain=Gows.us" >
					//	<img src="images/image8.png" alt="Gows.us is Eco-Friendly!" border="0" />
					//</a><br />
					//<fb:comments xid="566464564651" width="320" url="http://gows.us" title="Gows.us - Free Helpful Webscripts" 
					//simple="1" css="scripts/dark_like2.css"></fb:comments> 
					//This website is Hosted for free by <A href="#" onMouseOver="stm(Text[8],Style[5])" onMouseOut="htm()">Matt Gow</A>
					?> 
				</h4>
			</td>
		</tr>
	</table>

	<!--This bit of code runs a snow script on the months of December and January-->
	<?php
		$month=date("F");
		if($month=="December"||$month=="January")
		{
			?>
			<script type="text/javascript" src="scripts/snow.js">
			</script>
			<?php
		}
	?>
	
	
<!--Google Analytics-->
  <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-47262489-1', 'gows.us');
  ga('send', 'pageview');
  </script>
	
	
	<!--[if !(lt IE 8)]><!-->
   <script type="text/javascript">
     (function(){var e=document.createElement("script");e.type="text/javascript";e.async=true;e.src=document.location.protocol+"//d1agz031tafz8n.cloudfront.net/thedaywefightback.js/widget.min.js";var t=document.getElementsByTagName("script")[0];t.parentNode.insertBefore(e,t)})()
   </script>
<!--<![endif]-->
	</body>

</html>
<? ob_flush(); ?>
