<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:template match="/">
<html> 
<body>
  <h2>MATH Courses</h2>
  <table border="1">
    <tr bgcolor="#9acd32">
      <th style="text-align:left">Title</th>
      <th style="text-align:left">Course</th>
      <th style="text-align:left">Instructor</th>
    </tr>
    <xsl:for-each select="root/course">
      <xsl:if test ="subj='MATH'">
        <tr>
          <td><xsl:value-of select="title"/></td>
          <td><xsl:value-of select="crse"/></td>
          <td><xsl:value-of select="instructor"/></td>
        </tr>
      </xsl:if>
    </xsl:for-each>
  </table>
</body>
</html>
</xsl:template>
</xsl:stylesheet>

