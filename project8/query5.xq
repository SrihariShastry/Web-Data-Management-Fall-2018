for $in in distinct-values(doc("reed.xml")/root/course/instructor)
return ('&#xa;',
 <instructor>{'&#xa;'}<name>{
data($in)
 }</name>{'&#xa;'}
{
for $c in distinct-values(doc('reed.xml')/root/course[instructor=data($in)]/title)
  return (<course>{data($c)}</course>,'&#xa;')
}
 </instructor>,'&#xa;')
