var scripts = document.getElementsByTagName('script');
var index = scripts.length - 1;
var myScript = scripts[index];
var myScriptsrc = myScript.src;
var myScriptsub = myScriptsrc.substr(43,12);
var eventIDsub = myScriptsrc.substr(60,13);
var formatforum = "format=forum";
var formatsmall = "format=small";
var formatlarge = "format=large";
var formatverti = "format=verti";
var formatwibar = "format=wibar";
//document.write(myScriptsub);
//document.write(eventIDsub);
/////////////////
//If src substring = ?small then load small widget
/////////////////

var host = "http://"+window.location.hostname;

if (myScriptsub == formatforum){

if (typeof jQuery == 'undefined') {
    document.write("<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js' type='text/javascript'></script>");
} else {
    // jQuery is loaded
}


function loadWidgetforum(){

var url = host+'/Donation-Center/widget/load-forum.php?eid='+eventIDsub;
$('#tsgDonationWidget').attr('src', url);
}

window.setInterval("loadWidgetforum()",300000 );



document.write("<iframe scrolling='no' style='margin:0;padding:0;border:none;' frameBorder='0' width='100%' height='80' style='' id='tsgDonationWidget' src= "+host+"/Donation-Center/widget/load-forum.php?eid="+eventIDsub+"></iframe>");

} else if (myScriptsub == formatwibar) {

//////////////////
//Else if load small sized widget
//////////////////

if (typeof jQuery == 'undefined') {
    document.write("<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js' type='text/javascript'></script>");
} else {
    // jQuery is loaded
}


function loadWidgetbar(){
var url = 'http://donate.thespeedgamers.com/widget/load-bar.php?id='+eventIDsub;
$('#tsgDonationWidget').attr('src', url);
}

window.setInterval("loadWidgetbar()",300000 );



document.write("<iframe scrolling='no' style='margin:0;padding:0;border:none;' frameBorder='0' width='100%' height='84px' style='' id='tsgDonationWidget' src='http://donate.thespeedgamers.com/widget/load-bar.php?id="+eventIDsub+"'></iframe>");

} else if (myScriptsub == formatverti) {

//////////////////
//Else if load small sized widget
//////////////////

if (typeof jQuery == 'undefined') {
    document.write("<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js' type='text/javascript'></script>");
} else {
    // jQuery is loaded
}


function loadWidgetvert(){
var url = 'http://donate.thespeedgamers.com/widget/load-vert.php?id='+eventIDsub;
$('#tsgDonationWidget').attr('src', url);
}

window.setInterval("loadWidgetvert()",300000 );



document.write("<iframe scrolling='no' style='margin:0;padding:0;border:none;' frameBorder='0' width='142' height='417' style='' id='tsgDonationWidget' src='http://donate.thespeedgamers.com/widget/load-vert.php?id="+eventIDsub+"'></iframe>");


} else if (myScriptsub == formatsmall) {

//////////////////
//Else if load small sized widget
//////////////////

if (typeof jQuery == 'undefined') {
    document.write("<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js' type='text/javascript'></script>");
} else {
    // jQuery is loaded
}


function loadWidgetsmall(){
var url = 'http://donate.thespeedgamers.com/widget/load-small.php?id='+eventIDsub;
$('#tsgDonationWidget').attr('src', url);
}

window.setInterval("loadWidgetsmall()",300000 );



document.write("<iframe scrolling='no' style='margin:0;padding:0;border:none;' frameBorder='0' width='272' height='77' style='' id='tsgDonationWidget' src='http://donate.thespeedgamers.com/widget/load-small.php?id="+eventIDsub+"'></iframe>");

 } else if (myScriptsub == formatlarge) {
//////////////////
//Else load regular size widget
//////////////////

if (typeof jQuery == 'undefined') {
    document.write("<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js' type='text/javascript'></script>");
} else {
    // jQuery is loaded
}


function loadWidget(){

var url = 'http://donate.thespeedgamers.com/widget/load.php?id='+eventIDsub;
$('#tsgDonationWidget').attr('src', url);
}

window.setInterval("loadWidget()",300000 );

document.write("<iframe scrolling='no' style='border:none;' frameBorder='0' width='290' height='280' style='' id='tsgDonationWidget' src='http://donate.thespeedgamers.com/widget/load.php?id="+eventIDsub+"'></iframe>");
}
