for $c in doc('reed.xml')/root/course[subj='MATH' and place[building='LIB' and room='204']]
let $nl := "&#10;"
return('&#xa;',
<course>
{$nl}
  <title>{data($c/title)}</title>
  {$nl}
  <instructor>{data($c/instructor)}</instructor>
  {$nl}
  <starttime>{data($c/time/start_time)}</starttime>
  {$nl}
  <endtime>{data($c/time/end_time)}</endtime>
  {$nl}
</course>,'&#xa;')
