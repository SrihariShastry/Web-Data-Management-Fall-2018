
var get="GET";
var genreRequest          = "proxy.php?method=/3/genre/movie/list";
var movieSearchRequest    = "proxy.php?method=/3/search/movie&query=";
var movieDetailRequest    = "proxy.php?method=/3/movie/";

function initialize () {
}

// Search for a movie
function search(){
   var query = encodeURI(document.getElementById("form-input").value);
   sendRequest(movieSearchRequest,query);
}

// Get Details of a particular movie
function getMovieDetails(id){
  var query = encodeURI(id);
  sendRequest(movieDetailRequest,query);
}

// Handle Ajax responses
function sendRequest (request,query) {
  var xhr = new XMLHttpRequest();
  xhr.open(get, request + query);
  xhr.setRequestHeader("Accept","application/json");
  switch (request){
    case movieSearchRequest:
        xhr.onreadystatechange = function () {
            if (this.readyState == 4) {
               var json = JSON.parse(this.responseText);
               var movies = json.results;
               var mov="";

               // Parsing Movie results
               for(var i in movies){
                 mov  += "<tr><td>"+"<a href =\"#\" onclick = \"getMovieDetails("+ movies[i].id+")\">"+
                            movies[i].original_title+"</a>"+"</td>"+
                            "<td>"+ movies[i].vote_average + "</td>"+
                            "<td>"+ movies[i].original_language +"</td>"+
                            "<td>" + movies[i].release_date.substring(0,4) + "</td>";
               }
               document.getElementById('movieList').innerHTML =
               "<table><tr><th>Movie Name</th><th>Rating</th><th>Original language</th><th>Year of Release</th></tr>"+mov+"</table>";
            }
        };
        break;
    case movieDetailRequest:
    xhr.onreadystatechange = function () {
            if (this.readyState == 4) {
                 // get JSON Response
                 var json = JSON.parse(this.responseText);

                 // Get Movie poster
                 var movDet = "<img width = 40% src = " + "\"http://image.tmdb.org/t/p/w185/"+ json.poster_path+ "\"</img> <br/>";
                 var cast;
                 movDet += "<h3>" +json.original_title+ "</h3>";            //Original Title of the movie
                 movDet += "<h4> -&nbsp" + json.tagline+"</h4><br/>";       //Tagline of the movie
                 movDet += "<h4><b> Genre(s): </b></h4><p>";                //Listing the movie genres

                 for(var i=0; i<json.genres.length;i++){  
                  movDet += json.genres[i].name;                            //Genre Listing
                  if(i!= json.genres.length -1){                            //Put comma only if its not the last genre
                    movDet +=",";
                  }
                  else{
                    movDet += ".";
                  }
               }

               // Show the Overview of the movie
               movDet   +=  "</p><br/>";
               movDet   +=  "<h4> Overview:</h4>"+"<p>"+json.overview+"</p><br/><h5>";
               movDet   +=  "<h4><b>Cast: </b></h4>"

               // Get Cast Details for the movie
                var castXhr = new XMLHttpRequest();
                var castLink = movieDetailRequest+json.id+"/credits";
                castXhr.open(get, castLink);
                castXhr.setRequestHeader("Accept","application/json");

                // Handle JSON Data of Cast members
                castXhr.onreadystatechange = function() {
                  if(this.readyState == 4){                                   // Parse JSON when the operation is complete
                    var json = JSON.parse(this.responseText);
                    cast = "<table><tr><th>Cast Image</th><th>Actor name</th><th>Character Name</th></tr>"
                    for(var i in json.cast){
                      if(i>=5) 
                      break;                                                   /*Get Top 5 Casts for the movie. The JSON data seems to be 
                                                                                ordered according to the order ID(Actor/Actress Order) 
                                                                                and hence No ordering is required here*/
                        
                      //Get Cast Details
                       cast+= "<tr><td><img height =\"44\" width =\"32\" src = \"http://image.tmdb.org/t/p/w185/"
                                  +json.cast[i].profile_path+"\" alt=\"" + json.cast[i].name+ "\" </img></td>";          
                      cast += "<td>"+json.cast[i].name+"</td>"+"<td>"+json.cast[i].character+"</td></tr>";
                    }
                    cast+= "</table>";
                     document.getElementById('movieDetail').innerHTML = movDet+cast;
                  }
                };
                // Send Ajax Request
                castXhr.send(null);
            }
        };
  }
  // Send Ajax Request
   xhr.send(null);
}
