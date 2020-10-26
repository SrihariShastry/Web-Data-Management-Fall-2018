package assignment_7;

import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;
import javax.xml.xpath.XPath;
import javax.xml.xpath.XPathConstants;
import javax.xml.xpath.XPathFactory;

import org.w3c.dom.Document;
import org.w3c.dom.NodeList;

public class xpath {
	public static void main(String args[])throws Exception{
		DocumentBuilderFactory dbf = DocumentBuilderFactory.newInstance();
		try {
			 DocumentBuilder db = dbf.newDocumentBuilder();
//			 Path relative to system
			 Document doc = db.parse("reed.xml");
			 
			 XPathFactory xpf= XPathFactory.newInstance();
			 XPath path = xpf.newXPath();
			 NodeList titles = (NodeList) path.evaluate("//course[subj='MATH' and place[building='LIB' and room='204']]/title", doc,XPathConstants.NODESET);
			 NodeList instructor = (NodeList) path.evaluate("//course[subj='MATH' and crse='412']/instructor", doc,XPathConstants.NODESET);
			 NodeList courses = (NodeList) path.evaluate("//course[instructor='Wieting']/title", doc,XPathConstants.NODESET);
			 System.out.println("\n Titles of MATH subjects taught in LIB 204");
			 for(int i = 0; i<titles.getLength();i++) {
				 System.out.println(titles.item(i).getTextContent());
			 }
			 System.out.println("\n Names of Instructors who teach MATH 412");
			 for(int i = 0; i<instructor.getLength();i++) {
				 System.out.println(instructor.item(i).getTextContent());
			 }
			 System.out.println("\n Courses taught by Wieting");
			 for(int i = 0; i<courses.getLength();i++) {
				 System.out.println(courses.item(i).getTextContent());
			 }
			
		}catch(Exception e) {
			e.printStackTrace();
		}
	}

}
