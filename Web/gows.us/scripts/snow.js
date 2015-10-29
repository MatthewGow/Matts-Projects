/*
Snow Fall 1 - no images - Java Script
http://rainbow.arch.scriptmania.com/scripts/bg/snow_fall_1.html

// Updated by Napalm 2nd Dec 2012
// Now supports strict mode and other multi-browser event handling
//
*/

// Set the number of snowflakes (more than 30 - 40 not recommended)
var snowmax=30

// Set the colors for the snow. Add as many colors as you like
var snowcolor=new Array("#AAAACC","#DDDDFF","#CCCCDD","#F3F3F3","#F0FFFF")

// Set the fonts, that create the snowflakes. Add as many fonts as you like
var snowtype=new Array("Arial Black","Arial Narrow","Times","Comic Sans MS")

// Set the letter that creates your snowflake (recommended: * )
var snowletter="*"

// Set the speed of sinking (recommended values range from 0.3 to 2)
var sinkspeed=0.6

// Set the maximum-size of your snowflakes
var snowmaxsize=22

// Set the minimal-size of your snowflakes
var snowminsize=8

// Set the snowing-zone
// Set 1 for all-over-snowing, set 2 for left-side-snowing 
// Set 3 for center-snowing, set 4 for right-side-snowing
var snowingzone=1

/*
//   * NO CONFIGURATION BELOW HERE *
*/

// Do not edit below this line
var snow=new Array()
var marginbottom
var marginright
var timer
var i_snow=0
var x_mv=new Array();
var crds=new Array();
var lftrght=new Array();
var browserinfos=navigator.userAgent 
var ie5=document.all&&document.getElementById&&!browserinfos.match(/Opera/)
var ns6=(document.getElementById&&!document.all)?1:0;
var opera=browserinfos.match(/Opera/)  
var browserok=ie5||ns6||opera

function randommaker(range) {       
    rand=Math.floor(range*Math.random())
    return rand
}

function initsnow() {
    if (ie5 || opera) {
        marginbottom = document.body.clientHeight
        marginright = document.body.clientWidth
    }
    else if (ns6) {
        marginbottom = window.innerHeight
        marginright = window.innerWidth
        // use this
    }
    
    var snowsizerange=snowmaxsize-snowminsize
    for (i=0;i<=snowmax;i++) {
        crds[i] = 0;                      
        lftrght[i] = Math.random()*15;         
        x_mv[i] = 0.03 + Math.random()/10;
        snow[i]=document.getElementById("s"+i)
        snow[i].style.fontFamily=snowtype[randommaker(snowtype.length)]
        snow[i].size=randommaker(snowsizerange)+snowminsize
        snow[i].style.fontSize=snow[i].size + "px";
        snow[i].style.color=snowcolor[randommaker(snowcolor.length)]
        snow[i].sink=sinkspeed*snow[i].size/5
        if (snowingzone==1) {snow[i].posx=randommaker(marginright-snow[i].size)}
        if (snowingzone==2) {snow[i].posx=randommaker(marginright/2-snow[i].size)}
        if (snowingzone==3) {snow[i].posx=randommaker(marginright/2-snow[i].size)+marginright/4}
        if (snowingzone==4) {snow[i].posx=randommaker(marginright/2-snow[i].size)+marginright/2}
        snow[i].posy=randommaker(2*marginbottom-marginbottom-2*snow[i].size)
        snow[i].style.left=snow[i].posx + "px";
        snow[i].style.top=snow[i].posy + "px";
    }
    setInterval('movesnow();', 50);
}

function movesnow() {
    var hscrll=(ns6)?window.pageYOffset:document.body.scrollTop;
    for(i=0;i<=snowmax;i++) {
        crds[i] += x_mv[i];
        snow[i].posy+=snow[i].sink
        snow[i].style.left=snow[i].posx+lftrght[i]*Math.sin(crds[i]) + "px";
        snow[i].style.top=snow[i].posy + "px";
        
        if((snow[i].posy >= ((hscrll + marginbottom) - (2 * snow[i].size))) || parseInt(snow[i].style.left)>(marginright-3*lftrght[i])){
            if (snowingzone==1) {snow[i].posx=randommaker(marginright-snow[i].size)}
            if (snowingzone==2) {snow[i].posx=randommaker(marginright/2-snow[i].size)}
            if (snowingzone==3) {snow[i].posx=randommaker(marginright/2-snow[i].size)+marginright/4}
            if (snowingzone==4) {snow[i].posx=randommaker(marginright/2-snow[i].size)+marginright/2}
            snow[i].posy=hscrll
        }
    }
}

if(browserok){
    for(i=0;i<=snowmax;i++){
        document.write("<span id='s"+i+"' style='position:absolute;top:-"+snowmaxsize+";z-index: 1000;'>"+snowletter+"</span>")
    }
    if(window.addEventListener){
        window.addEventListener('load', initsnow, false);
    }
    else if(window.attachEvent){
        window.attachEvent('onload', initsnow);
    }
    else{
        window.onload=initsnow;
    }
}

