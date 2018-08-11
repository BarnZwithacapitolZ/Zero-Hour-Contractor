<?php
    header("Content-type: text/css; charset: UTF-8");
?>

/* 
html5doctor.com Reset Stylesheet
v1.6.1
Last Updated: 2010-09-17
Author: Richard Clark - http://richclarkdesign.com 
Twitter: @rich_clark
*/

html, body, div, span, object, iframe,
h1, h2, h3, h4, h5, h6, p, blockquote, pre,
abbr, address, cite, code,
del, dfn, em, img, ins, kbd, q, samp,
small, strong, sub, sup, var,
b, i,
dl, dt, dd, ol, ul, li,
fieldset, form, label, legend,
table, caption, tbody, tfoot, thead, tr, th, td,
article, aside, canvas, details, figcaption, figure, 
footer, header, hgroup, menu, nav, section, summary,
time, mark, audio, video {
    margin:0;
    padding:0;
    border:0;
    outline:0;
    font-size:100%;
    vertical-align:baseline;
    background:transparent;
}

body {
    line-height:1;
}

article,aside,details,figcaption,figure,
footer,header,hgroup,menu,nav,section { 
    display:block;
}

nav ul {
    list-style:none;
}

blockquote, q {
    quotes:none;
}

blockquote:before, blockquote:after,
q:before, q:after {
    content:'';
    content:none;
}

a {
    margin:0;
    padding:0;
    font-size:100%;
    vertical-align:baseline;
    background:transparent;
}

/* change colours to suit your needs */
ins {
    background-color:#ff9;
    color:#000;
    text-decoration:none;
}

/* change colours to suit your needs */
mark {
    background-color:#ff9;
    color:#000; 
    font-style:italic;
    font-weight:bold;
}

del {
    text-decoration: line-through;
}

abbr[title], dfn[title] {
    border-bottom:1px dotted;
    cursor:help;
}

table {
    border-collapse:collapse;
    border-spacing:0;
}

/* change border colour to suit your needs */
hr {
    display:block;
    height:1px;
    border:0;   
    border-top:1px solid #cccccc;
    margin:1em 0;
    padding:0;
}

input, select {
    vertical-align:middle;
}

/* CSS START */

html, body{
    width: 100%;
    height: 100%;
    color: black; /* Default font color */
    font-family: 'Open Sans', sans-serif;
}

#side-navigation{
    height: 100%;
    width: 80px;
    float: left;
    background-color: #28303d;
    overflow: hidden;
}

#overview-manager{
    margin-left: 80px;
    height: 100%;
}

ul.icon-list{
    color: white;
    list-style-type: none;
    position: relative; /* for bottom + */
    height: 100%;
}

.icon-list .icon{
    width: 80px;
    height: 80px;
    padding: 7px 0;
    position: relative; /* For notification bubbles */
    transition: .3s;
}

.icon-list .icon.profile{
    background-color: #49b67c;
    padding: 0;
}

.icon-list .icon.last{
    position: absolute;
    bottom: 0;
}

.icon-list .icon img{
    width: 45%;
    height: 45%;
    margin: 27.5%;
}

.icon-list .icon.profile img{
    border-radius: 20px;
}


.icon-list .icon.button:hover{
    border-left: 3.2px solid #49b67c;
    transition: .3s;
    background-color: #323d4e;
 
}

.notification-bubble{
    position: absolute;
    width: 20px;
    height: 20px;
    background-color: #ff6666;
    color: white;
    top: 5px;
    right: 7px;
    text-align: center;
    vertical-align: middle;
    line-height: 20px;
    border-radius: 10px;
    display: none; 
}

@media (max-width: 650px){
    #overview-manager{
        height: auto;
    }

    #side-navigation{
        width: 100%;
        height: 55px;
        float: none;
    }
    
    .icon-list .icon{
        padding: 0;
        width: 55px;
        height: 55px;
    }

    #overview-manager{
        margin-left: 0;
    }

    ul.icon-list li{
        float: left;
    }

    .icon-list .icon.last{
        right: 0;
        bottom: auto;
    }

    .icon-list .icon.button:hover{
        border-left: none;
        border-top: 2px solid #49b67c;
    }
}

@media (max-width: 300px){
    #side-navigation{
        height: 40px;
    }

    .icon-list .icon{
        width: 40px;
        height: 40px;
    }
}