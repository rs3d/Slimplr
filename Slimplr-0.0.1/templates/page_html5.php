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
	<meta name="robots" content="index, follow" />

	<link href="/css/style.css" rel="stylesheet" media="all"  />
	<link href="http://fonts.googleapis.com/css?family=Anonymous+Pro:regular,italic,bold,bolditalic" rel="stylesheet" type="text/css" />

	
	<link rel="shortcut icon" href="/img/favicon.ico" />
	<link id="page_favicon" href="/img/favicon.ico" rel="icon" type="image/x-icon" />
	<link rel="apple-touch-icon" href="/img/apple-touch-icon.png">
    <link rel="apple-touch-icon" sizes="57x57" href="/img/apple-touch-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/img/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/img/apple-touch-icon-114x114.png">
	

	<!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>

	<script src="/js/script.js"></script>

</head>
<body class="<?php echo $class?>">
<div class="container"> 
<div id="page">
	
	<header>
		<hgroup class="section">
			<h1 id="logo">
				<a href="<?php echo $page -> url ?>">
					Slimplr
				</a>
			</h1>
			<h2 id="claim" >
				<?php echo $page->getAttribute('name');?>
			</h2>
		</hgroup>
	</header>
	<div id="layout">
		<div id="main" role="main" class="section">
			<div id="content">
		
			<h3 class="auto"></h3>
			
			<blockquote>	
			
				<p>
				You can see the navigation data in routes/navigation-pub.xml
				</p>
				<p>
				Watch for nested elements (with no leading "/" --> goes up until an element has a leading "slash") and 
				<br />
				elements referring directly to the root '/' (with leading "/")-
				</p>
				<p>
				There are attributes/parameters called "auto()" which call the function  "_getFriendlyID ()". This also happens if no "path"-element is defined.
				</p>
				<p>
				The language attribute is handed down from the first parent object wíth a language value.
				</p>

			</blockquote>
			
			<h4>The $page object</h4>
			<?php
			
			show($page);
			
			?>
			<p>
			
			</p>
		
			</div>
		</div>
	
		<nav id="navigation" class="section">
			<h3 class="section">Navigation</h3>
			<div id="navigation-main">
				<h4 class="section">Main</h4>
				<?php echo $navigation['main'];?>
			</div>

			<div id="navigation-context">
				<h4 class="section">Context</h4>
				<?php echo $navigation['context'];?>
			</div>

			<div id="navigation-breadcrumb">
				<h4 class="section">Breadcrumb</h4>
		 		<?php echo $navigation['breadcrumb'];?>
			</div>
			<div id="navigation-global">
				<h4 class="section">Global</h4>
		 		<?php echo $navigation['global'];?>
			</div>
		</nav>
	
		<footer id="footer" class="section">
		<p>&copy; 2012
		</p>
		</footer>
	</div>
	</div>
</div>
</body>
</html>