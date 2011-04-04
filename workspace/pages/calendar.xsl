<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:php="http://php.net/xsl">

<xsl:import href="../utilities/master.xsl"/>

<xsl:template match="/data">
	<style type="text/css">
		.weekendday {
			color: red;
		}

		.current {
			background: orange;
		}

		.weekdays {
			background: yellow;
		}

		.weekdays td {
			color: blue;
			padding: 10px;
			text-align: center;
		}

		.month {
			color: green;
		}

		.year {
			color: magenta;
		}

		.days {
			text-align: center;
		}

	</style>
	<h1><xsl:value-of select="$page-title"/></h1>
	<xsl:value-of select="php:function('generate_calendar', calendar-main-events)" disable-output-escaping="yes"/><br />
</xsl:template>
</xsl:stylesheet>