
var gameStart,gameReset;    //Boolean values to Start and Stop the game.
var gameSpeed;              //The speed at which the ball moves
var intervalID;             //stop the game when the ball mmisses the paddle
var x,y;                    //Co-ordinates of the ball

function startGame(event){
  var strikes=0,maxScore=0;
  var vx=0,vy=0;
  //do the initial setup.
    setup();
  // the increment should not be 0 and hence we run it until we get a non-zero value for vx and vy
  while(vy==0||vx==0){
    // Choose random angle
    var piValue = Math.random()*(Math.PI/4+Math.PI/4)-(Math.PI/4);
    vx = Math.cos(piValue);
    vy = Math.sin(piValue);
  }
  gameStart = true;
  gameReset = false;

  //disable start button
  document.getElementById('start').disabled = true;
  document.getElementById('reset').disabled = false;

  intervalID = setInterval(function (){
    moveBall();
// Move the ball to its next co-ordinates
    function moveBall() {

      var paddleLeft = parseInt(document.getElementById('paddle').getBoundingClientRect().left,10);
      var paddleTop = parseInt(document.getElementById('paddle').getBoundingClientRect().top,10);
      var paddleRight = parseInt(document.getElementById('paddle').getBoundingClientRect().width,10);
      var paddleHeight= document.getElementById('paddle').naturalHeight;
      var paddleBottom = paddleTop+paddleHeight;
      var courtTop = parseInt(document.getElementById('court').getBoundingClientRect().top,10);
      var courtHeight = parseInt(document.getElementById('court').getBoundingClientRect().height,10);
      var courtBottom = courtTop+courtHeight;
      var ballRadius = document.getElementById('ball').getBoundingClientRect().height;

    if(x+vx<=0){
      // Ball hitting the left edge of the court
      vx = -vx;
    } else if (x+vx>=paddleLeft-paddleRight-ballRadius && y+vy>=paddleTop-courtTop && y+vy<=paddleBottom-courtTop-ballRadius) {
      // Ball hitting the paddle
      vx=-vx;
      strikes++;
      document.getElementById('strikes').innerHTML = strikes;
    }else if (x+vx>paddleLeft && (y+vy<paddleTop-courtTop || y+vy>paddleBottom-courtTop)) {
      // Ball missing the paddle
      resetGame();
    }else if(y+vy<=0||y+vy>=courtBottom-courtTop-ballRadius){
      // Ball hitting the top or bottom of the court
      vy=-vy;
    }
    // Calculate the updated co-ordinates of the ball
      x = x+vx*gameSpeed;
      y = y+vy*gameSpeed;

    // Assign new co-ordinates to the ball
    document.getElementById('ball').style.left = x + 'px';
    document.getElementById('ball').style.top = y + 'px';
    }
  },10);

}

function initialize(){
  setup();
}
function setup(){
  var courtTop = parseInt(document.getElementById('court').getBoundingClientRect().top,10);
  var courtHeight= parseInt(document.getElementById('court').getBoundingClientRect().height,10);
  var ballRadius = parseInt(document.getElementById('ball').getBoundingClientRect().height,10);

  gameReset = false;
  gameStart = false;
  // Set the speed accoording to the radio button selected
  if(document.getElementById('speed0').checked){
    setSpeed(0);
  }else if (document.getElementById('speed1').checked) {
    setSpeed(1);
  }else if (document.getElementById('speed2').checked) {
    setSpeed(2);
  }

// Keep the ball at random place to startGame
  var ballPosition = Math.random()*(courtTop-courtHeight) + (courtHeight-ballRadius);
  y = ballPosition-ballRadius;
  x = 0;
  document.getElementById('ball').style.top = ballPosition+"px";
}


function movePaddle(event){
  var paddleHeight= document.getElementById('paddle').naturalHeight;
  var courtTop = parseInt(document.getElementById('court').getBoundingClientRect().top,10);
  var courtHeight= parseInt(document.getElementById('court').getBoundingClientRect().height,10);
  var courtBottom = courtTop+courtHeight;
  courtTop = courtTop+paddleHeight;

    //move paddle only if game is on.
    if(gameStart)
    //move the paddle inside the court only.
    if(event.clientY>courtTop&&event.clientY< courtBottom)
    document.getElementById('paddle').style.top = event.clientY-paddleHeight +'px';
}


function resetGame(){
  //stop the game
  gameReset = true;
  gameStart = false;
  clearInterval(intervalID);
  document.getElementById('start').disabled = false;
  document.getElementById('reset').disabled = true;
  if(document.getElementById('strikes').innerHTML >document.getElementById('score').innerHTML){
    document.getElementById('score').innerHTML = document.getElementById('strikes').innerHTML;
  }
  document.getElementById('strikes').innerHTML = 0;
  setup();
}

function setSpeed(speed){
  //set Speed of the game
  switch (speed) {
    case 0: gameSpeed = 2;
      break;
    case 1: gameSpeed = 4;
      break;
    case 2: gameSpeed = 6;
      break;
  }
}
