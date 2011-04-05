<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:php="http://php.net/xsl">

<xsl:import href="../utilities/master.xsl"/>

<xsl:template match="/data">

	<h1><xsl:value-of select="$page-title"/></h1>
	<xsl:value-of select="php:function('generate_calendar', calendar-main-events, 'calendar')" disable-output-escaping="yes"/>
</xsl:template>
</xsl:stylesheet>