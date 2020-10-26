package assignment_7;
//import javax.xml.bind.Element;
//import java.io.IOException;

import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;
//import javax.xml.parsers.ParserConfigurationException;

import org.w3c.dom.Document;
import org.w3c.dom.Element;
import org.w3c.dom.Node;
import org.w3c.dom.NodeList;
//import org.xml.sax.SAXException;


class Dom {
	public static void main(String args[])throws Exception{
		 DocumentBuilderFactory dbf = DocumentBuilderFactory.newInstance();
		try {
		 DocumentBuilder db = dbf.newDocumentBuilder();
		 Document doc = db.parse("reed.xml");
		 NodeList courses = doc.getElementsByTagName("course");
		 for (int i = 0; i < courses.getLength(); i++) {
		    if(courses.item(i).getNodeType()==Node.ELEMENT_NODE) {
		    	Element c = (Element) courses.item(i);
		    	String subj= c.getElementsByTagName("subj").item(0).getTextContent();
		    	if(subj.equalsIgnoreCase("MATH")){
		    		String title = c.getElementsByTagName("title").item(0).getTextContent();		    		
		    		NodeList places = c.getElementsByTagName("place");
		    		Element p = (Element) places.item(0);
		    		String building = p.getElementsByTagName("building").item(0).getTextContent();
		    		String room = p.getElementsByTagName("room").item(0).getTextContent();
		    		if(building.equalsIgnoreCase("LIB")&&room.equalsIgnoreCase("204")) {
		    			System.out.println(title);	    			
		    			}	    		
		    		}
		    	}
		    }
		}catch(Exception e) {
			e.printStackTrace();
		}
	}	
}
	