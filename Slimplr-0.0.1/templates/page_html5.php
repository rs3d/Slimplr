<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="<?php echo $lang;?>"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="<?php echo $lang;?>"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="<?php echo $lang;?>"> <![endif]-->
<!--[if gt IE 8]><!--><html class="no-js" lang="<?php echo $lang;?>" xml:lang="<?php echo $lang;?>">  <!--<![endif]-->
<head>
	<title><?php echo $title;?></title>
	<meta charset="utf-8" />
	<meta name="author" 		content="" />
	<meta name="keywords" 		content="<?php echo $title;?>" />
	<meta name="description" 	content="<?php echo $title;?>" />

	  <meta name="HandheldFriendly" content="True">
	  <meta name="MobileOptimized" content="320">
	  <meta name="viewport" content="width=device-width">
	  <meta http-equiv="cleartype" content="on">

	<meta name="robots" content="index, follow" />

	<link href="/css/style.css" rel="stylesheet" media="all"  />
		<link href="/css/css3-test.css" rel="stylesheet" media="all"  />

	<link href='http://fonts.googleapis.com/css?family=Muli:300,400,300italic,400italic' rel='stylesheet' type='text/css'>


	
	<link rel="shortcut icon" href="/img/favicon.ico" />
	<link id="page_favicon" href="/img/favicon.ico" rel="icon" type="image/x-icon" />
	<link rel="apple-touch-icon" href="/img/apple-touch-icon.png">
    <link rel="apple-touch-icon" sizes="57x57" href="/img/apple-touch-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/img/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/img/apple-touch-icon-114x114.png">
	


    <script src="js/libs/modernizr-2.0.6.min.js"></script>
	


</head>
<body class="<?php echo 'id-'.$page->id;?>">
<div class="container"> 
<div id="page">
	<header id="head">
		<hgroup class="section">
			<h1 id="logo">
				<a href="/">
					<?php echo $title;?>
				</a>
			</h1>
			<h2 id="claim" >
				<?php echo $claim;?>
			</h2>
		</hgroup>
	</header>
	<div id="layout" class="cf">
		<div id="main" role="main" class="section drop-shadow cf">
			<div id="content">
		     <h3 id="name" >
				<?php echo $name;?> 
			</h3>
		
		<div class="ajax-content">
			<?php echo($content); ?>
		</div>

			
			
			
			<?php

		
			//show($page);
			
			?>
			<p>
			
			</p>
		
			</div>
		</div>


		<nav id="navigation" class="section">
			<!--<h3 class="section">Navigation</h3> -->
			<div id="navigation-main">
				<h4 class="section">Navigation</h4>
				<?php echo $navigation['main'];?>
			</div>
			<!--
			<div id="navigation-context">
				<h4 class="section">Context</h4>
				<?php echo $navigation['context'];?>
			</div>
			-->

			<!--<div id="navigation-global">
				<h4 class="section">Global</h4>
		 		<?php echo $navigation['global'];?>
			</div>-->
		</nav>

		
		<aside id="sidebar">
			<section class="skillz drop-shadow cf">
				<div class="info">
				<h4>Fork  <a href="https://github.com/rs3d/Slimplr">Slimplr on GitHub</a></h4>
				<h5>Credits/Features</h5>

				<p>
					
					<a href="http://www.slimframework.com/">Slim Framework</a><br />	
					<a href="http://www.php.net/">PHP5</a> + <a href="http://php.net/manual/en/book.simplexml.php">Simple-XML</a><br />	
					<a href="http://jquery.com/">jQuery</a>, <a href="http://jquerypp.com/">jQuery++</a> + <a href="https://github.com/balupton/History.js/">history.js</a><br />
					<a href="http://www.html5rocks.com/en/">HTML5/CSS3</a><br />
					<a href="http://ipapun.deviantart.com/art/Devine-Icons-137555756">Devine Icons</a>
				</p>
				
				</div>
			</section>
			<section class="contact drop-shadow cf">
				<div class="info">
				<h4>Contact</h4>
				<p><strong>Robin Schmitz</strong> </p>
				<p>				
				Urbanstr. 64 – 10967 Berlin  Germany
				</p>
				<p>
				
				Mobil		0 1 77 / 727 94 82
				</p>
				<p>
				Mail	<a href="mailto:rob@rs3d.de">rob@rs3d.de</a><br />
				Twitter	<a href="twitter.com/rs3d">twitter.com/rs3d</a><br />
				Website	<a href="http://www.rs3d.de/">www.rs3d.de</a>
				</p>
				</div>
			</section>
		</aside>

		

		
	</div>
	</div>
	<footer id="footer" class="section">
		<div class="layout">
			<div id="navigation-breadcrumb" class="breadcrumbs">
				<h4 class="section">Breadcrumb</h4>

		 		<?php echo $navigation['breadcrumb'];?>
		 	
			</div>
			<p class="copyright">
				<time pubdate="<?php echo date('Y-m-d');?>">&copy; 2012</time> <a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>">http://<?php echo $_SERVER['HTTP_HOST']; ?></a>
			</p>
	
		</div>
		</footer>
</div>

  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
  <script>window.jQuery || document.write('<script src="js/libs/jquery-1.7.2.min.js"><\/script>')</script>

  <!-- scripts concatenated and minified via ant build script-->
  <script src="/js/jquerypp.custom.js"></script>
  <script src="/js/helper.js"></script>
  <script src="/js/jquery.history.js"></script>
  
  <script src="/js/script.js"></script>
</body>
</html>