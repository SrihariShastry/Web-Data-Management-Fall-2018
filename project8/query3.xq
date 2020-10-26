for $d in distinct-values(doc("reed.xml")/root/course/subj)
return ('&#xa;',
 <department>{'&#xa;'}<code>{
data($d)
 }</code>{'&#xa;'}
{
let $b:=doc('reed.xml')/root/course[subj=data($d)]
  return (<noofcourses>{count($b)}</noofcourses>,'&#xa;')
}
 </department>,'&#xa;')
