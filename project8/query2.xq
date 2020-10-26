for $c in distinct-values(doc("reed.xml")/root/course/title)
return('&#xa;',
 <course>{'&#xa;'}<title>{
data($c)
 }</title>{'&#xa;'}
{
for $in in distinct-values(doc('reed.xml')/root/course[title=data($c)]/instructor)
  return (<instructor>{data($in)}</instructor>,'&#xa;')
}
 </course>,'&#xa;')
