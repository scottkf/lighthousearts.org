<?xml version="1.0" encoding="UTF-8" ?>
<!--
	HTML5 Boilerplate XSLT Master Stylesheet (based on Paul Irish's HTML5 Boilerplate index.html file)
	Created by Brian Zerangue on 2010-11-15.
	Copyright (c) 2010 Paul Irish (Everything but the XSLT), Brian Zerangue (XSLT).
        HTML5 Boilerplate, http://html5boilerplate.com
-->

<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:output encoding="UTF-8" 
				indent="yes" 
				method="html" 
				media-type="text/html" 
				omit-xml-declaration="yes" 
				doctype-system="about:legacy-compat"/>

	<xsl:template match="/">
		<xsl:comment><![CDATA[[if lt IE 7]><html lang="en" class="no-js ie6"><![endif]]]></xsl:comment>
		<xsl:comment><![CDATA[[if IE 7]><html lang="en" class="no-js ie7"><![endif]]]></xsl:comment>
		<xsl:comment><![CDATA[[if IE 8]><html lang="en" class="no-js ie8"><![endif]]]></xsl:comment>
		<xsl:comment><![CDATA[[if IE 9]><html lang="en" class="no-js ie9"><![endif]]]></xsl:comment>
		<xsl:comment><![CDATA[[if IE]><![if (gt IE 9)|!(IE)]><![endif]]]></xsl:comment><html lang="en" class="no-js">
		<xsl:comment><![CDATA[[if IE]><![endif]><![endif]]]></xsl:comment>
			<head>
				
			  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/><!-- This does not validate -->

			  <title>Lighthousearts.org - Hi!</title>
			  <meta name="description" content=""/>
			  <meta name="author" content=""/>

			  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

			  <!-- Place favicon.ico & apple-touch-icon.png in the root of your domain and delete these references -->
			  <link rel="shortcut icon" href="/favicon.ico"/>
			  <link rel="apple-touch-icon" href="/apple-touch-icon.png"/>


			  <!-- CSS : implied media="all" -->
<!--			  <link rel="stylesheet" href="css/style.css?v=2"/> -->

			  <!-- Uncomment if you are specifically targeting less enabled mobile browsers
			  <link rel="stylesheet" media="handheld" href="css/handheld.css?v=2">  -->

			  <!-- All JavaScript at the bottom, except for Modernizr which enables HTML5 elements & feature detects -->
			  <script src="workspace/javascript/libs/modernizr-1.7.min.js"></script>

			</head>

			<body>

			  <div id="container">
			    <header>
					
			    </header>

			    <div id="main">
					<xsl:apply-templates />
			    </div>

			    <footer>
					
			    </footer>
			  </div> <!--! end of #container -->
			

			  <!-- Grab Google CDN's jQuery. fall back to local if necessary -->
			  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.js"></script>
			  <script><![CDATA[!window.jQuery && document.write(unescape('%3Cscript src="workspace/libs/jquery-1.5.2.min.js"%3E%3C/script%3E'))]]></script>


			  <!-- scripts concatenated and minified via ant build script-->
			  <!-- <script src="js/plugins.js"></script>
			  <script src="js/script.js"></script> -->
			  <!-- end concatenated and minified scripts-->


			  <xsl:comment><![CDATA[[if lt IE 7]>
			    <script src="js/libs/dd_belatedpng.js"></script>
			    <script> DD_belatedPNG.fix('img, .png_bg'); //fix any <img> or .png_bg background-images </script>
			  <![endif]]]></xsl:comment>


			  <script>
			   <![CDATA[var _gaq = [['_setAccount', 'UA-XXXXX-X'], ['_trackPageview']];
				   (function(d, t) {
				    var g = d.createElement(t),
				        s = d.getElementsByTagName(t)[0];
				    g.async = true;
				    g.src = ('https:' == location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
				    s.parentNode.insertBefore(g, s);
				   })(document, 'script');]]>
			  </script>
			
			</body>
		</html>
	</xsl:template>
</xsl:stylesheet>