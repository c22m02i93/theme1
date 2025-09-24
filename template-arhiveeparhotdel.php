@import url(https://fonts.googleapis.com/css?family=Open+Sans:400,600&subset=latin,cyrillic);
@import url(https://fonts.googleapis.com/css?family=PT+Sans+Narrow&subset=latin,cyrillic);
@import url(https://fonts.googleapis.com/css?family=Roboto&subset=latin,cyrillic);
@font-face {
	font-family: 'Trajan';
	src: url('http://nne.ru/wp-content/themes/main-r/assets/fonts/3842211708.ttf');
}
* {
	outline: none;
}
button::-moz-focus-inner, input[type="reset"]::-moz-focus-inner, input[type="button"]::-moz-focus-inner, input[type="submit"]::-moz-focus-inner, input[type="submit"]::-moz-focus-inner, input[type="file"] > input[type="button"]::-moz-focus-inner {
	/* */
  border: none !important;
}
/* Yahoo! CSS Reset */
body, div, dl, dt, dd, ul, ol, li, h1, h2, h3, h4, h5, h6, pre, form, fieldset, input, textarea, p, blockquote, th, td {
	margin: 0;
	padding: 0;
}
table {
	border-collapse: collapse;
	border-spacing: 0;
	width: 100%;
}
fieldset, img {
	border: 0;
}
/* address, caption, cite, code, dfn, em, strong, th, var {
	font-style: normal;
	font-weight: normal;
} */

strong{
	font-weight: bold;
}
ol, ul {
	list-style: none;
}
caption, th {
	text-align: left;
}
h1, h2, h3, h4, h5, h6 {
	font-size: 100%;
	font-weight: normal;
}
q:before, q:after {
	content: '';
}
abbr, acronym {
	border: 0;
}
/* End of Yahoo! CSS Reset */
article, aside, details, figcaption, figure, footer, header, main, nav, section, summary {
	display: block;
}
@-moz-document url-prefix() {
	.title .separator{font-size: 0.9em;}
}

body {
	text-align: left;
	font-weight: 400;
	font-size: 16px;
	color: #000000;
	background-color: #e6eaec;
	font-family: Georgia;
}
h4{
	font-size: 18px;
}
.alignright {
	float: right;
	width: auto !important;
	margin: 20px 0 20px 20px;
}
.alignleft {
	float: left;
	width: auto !important;
	margin: 20px 20px 20px 0;
}
.aligncenter {
	width: auto !important;
	display: block;
	margin: 20px auto;
}
table.aligncenter{
	display: table;
}

.clear::after{
	display: table;
	content: "";
	clear: both;
}
a{
	text-decoration: none;
	color: #000000;
}
a>img{
	display: block;
}
img{
	/*max-width: 100%;*/
	display: block;
	height: auto;
}
.separator{
	margin: 0 6px;
	font-size: 0.85em;
	position: relative;
	top: -2px;
}
/*-------------------------------------helpers-------------------------------------*/
body > .wrapper{
	width: 1600px;
	/*min-width: 320px;*/
	margin: 0 auto;
}
.container{
	background-color: #ffffff;
	-webkit-box-shadow: 0 20px 50px -20px rgba(50, 50, 50, 0.3);
	-moz-box-shadow:    0 20px 50px -20px rgba(50, 50, 50, 0.3);
	box-shadow:         0 20px 50px -20px rgba(50, 50, 50, 0.3);
}
div.header{
	height: 290px;
	background: #ffffff;
	background: url('../img/header-image.jpg');
	padding: 40px 40px 0;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	text-align: right;
	position: relative;
}
.wrapper .content{
	display: block;
	float: left;
	/*width: 1200px;*/
	padding: 40px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
}
.search-results .content,
.archive .content{
	width: 1200px;
}
.post-type-archive-gallery .content{
	width: auto;
}
.home .wrapper .content{
	display: table-cell;
	float: none;
}

.has-no-sidebar .content{
	float: none;
	width: auto;
}

.has-sidebar .content,
.page .content{
	float: left;
	width: 75%;
}
.sidebar{
	float: right;
}
.post-type-archive-printed-issue .content,
.post-type-archive-education .content,
.post-type-archive-sainters .content,
.post-type-archive-saints .content,
.page-id-129483 .content,
.tax-obcategory .content{
	float: none;
	width: auto;
}
.home .sidebar{
	float: none;
}
.single .content,
.single .sidebar{
	display: block;
}
.header-menu{
	height: 78px;
	background-color: #ffffff;
	border-bottom: 2px solid #e6eaec;
	/* padding: 0 40px; */
}

.header-menu .menu{
	display: table;
	width: 100%;
	text-align: center;
	padding: 22px 0;
}

.header-menu li{
	display: inline-block;
	padding: 0 25px;

}
.obraz-link a, .obraz-link i,
.header-menu li a,
.menu-toggle{
	font-family: 'PT Sans Narrow', sans-serif;
	color: #155c7e;
	font-size: 26px; /* Приближение из-за подстановки шрифтов */
	font-weight: 300;
	line-height: 32px; /* Приближение из-за подстановки шрифтов */
}

.header-menu li a:hover{
	border-bottom: 2px solid #e03a3a;
}
.sub-menu {
	position: absolute;
	top: 100%;
	left: 5%;
	z-index: 1000;
	display: none;
	width: 250px;
	padding: 10px 0;
	text-align: left;
	background-color: white;
	border: 1px solid #155c7e;
	white-space: normal;
	box-shadow: 3px 3px 15px rgba(21,92,126,.7);
}
.sub-menu li{
	padding: 5px 15px;
	list-style: none;
	display: block;
	position: relative;
	
}
.sub-menu li a{
	color: #155c7e;
	line-height: 24px;
	font-size: 20px;
}
#menu-main-menu>li{
	position: relative;
}
#menu-main-menu li:hover > .sub-menu{
	display: block;
}
#menu-main-menu> li ul.sub-menu::before {
	position: absolute;
	top: -6px;
	left: 12px;
	height: 10px;
	width: 10px;
	display: block;
	background: inherit;
	-webkit-transform: rotate(45deg);
	-ms-transform: rotate(45deg);
	transform: rotate(45deg);
	content: "";
	border-left: inherit;
	border-top: inherit;
}

.sub-menu ul{
	top: 5%;
	left: 100%;
}

#menu-main-menu> li ul.sub-menu ul::before{
	left: -6px;
	top: 12px;
	border-left: inherit;
	border-top: none;
	border-bottom: inherit;
}

/*------------------------------sslider------------------------------*/

.scontainer{
	position: relative;
}
.sslide{
	display: none;
	position: absolute;
	top: 0;
	left: 0;
	width: 100%;
	height: 448px;
	overflow: hidden;
}
.sslide img{
	width: 100%;
	height: 448px;
	margin: auto;
	position: absolute;
	top: 0; left: 0; bottom: 0; right: 0;
}

.archive-common .pager .prev i,
.saints .pager .nav-links .prev i,
.photo-reports .pager .nav-links .prev i,
.block .pager .nav-links .prev i,
.archive-common .pager .next i,
.saints .pager .nav-links .next i,
.photo-reports .pager .nav-links .next i,
.block .pager .nav-links .next i{
	font-size: 32px;
}

.slider-dots {
	list-style: none;
	display: inline-block;
	padding-left: 0;
	margin-bottom: 0;
	position: absolute;
	bottom: 26px;
	left: 0;
	right: 0;
	text-align: center;
}

.slider-dots li{
	display: inline-block;
	margin: 0 16px 0 0;
}

.slider-dots li.dot {
	width: 12px;
	height: 12px;
	background: #ffffff;
	border: 6px solid #006ead;
	border-radius: 50%;
	margin-bottom: 5px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
}

.slider-dots li.active-dot {
	border-width: 4px;
}

.slider-dots li:last-child{
	margin-right: 0;
}

/*------------------------------sslider------------------------------*/
.menu-toggle{
	margin-right: 10px;
	display: none;
	float: right;
}
.link-img{
	float: left;
	margin-right: 20px;
}
.news-block .link-img img{
	width: 180px;
}
.main-news{
	border: 2px solid #155c7e;
	/*height: 316px;*/
	margin-bottom: 36px;
}

.right-text{
	/* padding: 40px 25px 0 40px; */
	padding: 18px 18px 0 18px;
	display: table-cell;
	vertical-align: top;
}
.date,
.widget_rss li .rss-date,
.report-author{
	font-family: 'Open Sans', sans-serif;
	color: #929292;
	font-size: 14px; /* Приближение из-за подстановки шрифтов */
	font-weight: 600;
	line-height: 24px; /* Приближение из-за подстановки шрифтов */
}

.right-text .title-link{
	font-size: 26px;
	line-height: 34px;
	margin-top: 0px;
	margin-bottom: 3px;
	display: block;
}

.right-text .title-link:hover{
	color: #155c7e;
}

.right-text .desc{
	font-size: 18px;
	line-height: 28px;
}

.title{
	font-family: 'PT Sans Narrow', sans-serif;
	font-size: 30px;
	font-weight: 300;
	line-height: 32px;
}

.blue-link{
	color: #195e80;
}
.home .content h2{
	border-bottom: 2px solid #155c7e;
	margin-bottom: 24px; 
}
.news-block{
	width: 560px;
	line-height: 24px;
	float: left;
}
.news-block .news-item {
  min-height: 220px;
  margin-bottom: 30px;
}
.widget_rss li .rsswidget,
.news-item .news-link{
	color: #155c7e;
	font-size: 20px;
	display: block;
	margin: 8px 0 12px;
}
.widget_rss li .rsswidget{
	margin: 10px 0 0;
	font-size: 17px;
}

.widget_rss li .rsswidget:hover,
.news-block .news-link:hover{
	text-decoration: underline;
}
.news-item .text {
  /*display: table-cell;*/
  vertical-align: top;
  padding-left: 20px;
}

.wrapper .content .image-count{
	float: right;
	font-family: 'OpenSans', sans-serif;
	color: #acacac;
	font-size: 14px;/* Приближение из-за подстановки шрифтов */
	font-weight: 700;
	line-height: 24px;
}

.image-count i{
	font-size: 17px;
	margin-left: 3px;
}
.right-block{
	width: 500px;
	float: right;
}

.right-block > div{
	margin-bottom: 16px;
}

.sidebar{
	width: 400px;
	border-left: 2px solid #e6eaec;
	padding: 0 40px 40px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	/* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#f3f3f3+0,ffffff+100 */
	background: #f3f3f3; /* Old browsers */
	/* IE9 SVG, needs conditional override of 'filter' to 'none' */
	background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIxMDAlIiB5Mj0iMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iI2YzZjNmMyIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiNmZmZmZmYiIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
	background: -moz-linear-gradient(left,  #f3f3f3 0%, #ffffff 100%); /* FF3.6+ */
	background: -webkit-gradient(linear, left top, right top, color-stop(0%,#f3f3f3), color-stop(100%,#ffffff)); /* Chrome,Safari4+ */
	background: -webkit-linear-gradient(left,  #f3f3f3 0%,#ffffff 100%); /* Chrome10+,Safari5.1+ */
	background: -o-linear-gradient(left,  #f3f3f3 0%,#ffffff 100%); /* Opera 11.10+ */
	background: -ms-linear-gradient(left,  #f3f3f3 0%,#ffffff 100%); /* IE10+ */
	background: linear-gradient(to right,  #f3f3f3 0%,#ffffff 100%); /* W3C */
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#f3f3f3', endColorstr='#ffffff',GradientType=1 ); /* IE6-8 */
}

.home .sidebar{
	display: table-cell;
	vertical-align: top;
}
.sidebar .title{
	line-height: 24px;
	margin: 40px 0 23px;
}

.sidebar .strange-calen{
	text-align: center;
	margin: 0 0 44px;
}

.sidebar .strange-calen .one-day .dev-date,
.sidebar .strange-calen .one-day .day-desc{
	display: none;
}

.sidebar .strange-calen .day-desc{
	font-family: Georgia;
	color:  #000000;
}
.sidebar .strange-calen .time-events{
	font-weight: 700;
	line-height: 24px;
}

.sidebar .strange-calen .temple-fame{
	color: #006ead;
	line-height: 24px;
}

.sidebar .strange-calen .temple-place {
	margin-bottom: 33px;
}

.sidebar .strange-calen .one-day{
	cursor: pointer;
	margin-left: -40px;
	margin-right: -40px;
	border-top: 1px solid #e6eaec;
	line-height: 38px;
	background-color: #ffffff;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
}

.sidebar .strange-calen .red-day{
	font-family: 'Open Sans', sans-serif;
	color: #e03a3a;
	font-size: 15px; /* Приближение из-за подстановки шрифтов */
	font-weight: 700;
	line-height: 24px; /* Приближение из-за подстановки шрифтов */
	/*margin-bottom: 12px;*/
	border-top: 1px solid #e6eaec;
	height: auto;
	cursor: default;
}
.sidebar .strange-calen .one-day:last-child{
	border-bottom: 1px solid #e6eaec;
}

.sidebar .jp-play i{
	font-size: 25px;
	color: #006ead;
	margin-left: 14px;
}

.sin-link{
	font-family: 'Open Sans', sans-serif;
	color: #155c7e;
	font-size: 15px; /* Приближение из-за подстановки шрифтов */
	line-height: 18px; /* Приближение из-за подстановки шрифтов */
	text-decoration: underline;
}

.sin-link:hover{
	text-decoration: none;
}

.sidebar .video{
	margin-bottom: 24px;
	padding-top: 6px;
	max-width: 318px;
	/*min-height: 244px;*/
}

.sidebar .video iframe {
	width: 100%;
	height: 100%;
}

.video .video-play{
	position: relative;
	display: block;
}
.video .video-play .play-icon{
	position: absolute;
	top: 0;
	bottom: 0;
	left: 0;
	right: 0;
	margin: auto;
}

.sidebar .video > img{
	border: 2px solid #155c7e;
}
.video .video-play .play-image{
	width: 100%;
}

.sidebar .orthodox-calendar .new-old-date{
	color: #e03a3a;
	font-size: 22px;
	line-height: 24px;
}

.sidebar .orthodox-calendar .week-post{
	color: #155c7e;
	line-height: 26px;
}

.sidebar .orthodox-calendar .saints{
	font-size: 14px;
	line-height: 22px;
}

.sidebar .orthodox-calendar .saints a{
	text-decoration: underline;
}

.sidebar .orthodox-calendar .saints-photo{
	text-align: center;
	background: #e6eaec;
	margin: 13px 0 12px;
}

.sidebar .orthodox-calendar .saints-photo a{
	display: inline-block;
	width: 100%;
}

.sidebar .orthodox-calendar .saints-photo img{
	margin: 0 auto;
}

.sidebar .title._mini{
	font-size: 27px;
	margin-bottom: 8px;
}

.sidebar .phone-number{
	font-family: 'Open Sans', sans-serif;
	font-size: 34px;/* Приближение из-за подстановки шрифтов */
	color: #155c7e;
	text-align: right;
	margin-right: -7px;
}

.sidebar .phone-number img{
	margin-left: 7px;
	display: inline-block;
}

.footer{
	height: 284px;
	padding: 40px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
}
.footer-left,
.footer-left a{
	font-family: 'Open Sans', sans-serif;
	color: #919ea5;
	font-size: 13px; /* Приближение из-за подстановки шрифтов */
	line-height: 22px; /* Приближение из-за подстановки шрифтов */
}
.footer-left a{
	display: inline-block;
	margin: 10px 15px 0 0;
	text-decoration: underline;
}

.footer-bottom{
	margin-top: 40px;
}
.footer-right{
	float: right;
}
.footer-right a{
	width: 88px;
	height: 31px;
	display: inline-block;
	margin-right: 32px;
	vertical-align: top;
}

.footer-right .img-link-9{ 
    margin-right: 0;
    height: 52px;
    margin-top: -21px;
}
.footer-right .img-link-6{
	width: 119px;
	height: 41px;
	margin-top: -10px;
}
.footer-right .img-link-7{
	height: 52px;
	width: 120px;
	margin-top: -21px;
}
.footer .menu li{
	margin-right: 90px;
	display: inline-block;
}

.footer .menu li:nth-last-child(1){
	margin-right: 0;
}

.footer .menu li a{
	font-family: 'Open Sans', sans-serif;
	color: #738087;
	font-size: 14px; /* Приближение из-за подстановки шрифтов */
	font-weight: 600;
}

.header .logo {
	position: absolute;
	left: 464px;
	top: 42px;
	text-align: center;
	font-family: 'Trajan', serif;
	width: 361px;
	height: 109px;
	text-transform: uppercase;
	display: block;
}
.header .logo-subtitle{
	color: #1a2732;
	font-size: 15.12px;
	letter-spacing: 1.21px;
	line-height: 30px;
}
.header .logo-first-title{
	color:  #e03a3a;
	font-size: 39.82px;
	line-height: 48px;
}
.header .logo-second-title{
	color: #e03a3a;
	font-size: 32.52px;
	line-height: 32px;
	display: block;
}
.header .logo span{
	text-shadow: 0 0 25px rgba(255,255,255,0.93);
}
.search-archive{
	text-align: left;
	display: inline-block;
	
}

.search-archive .input-group{
	font-size: 0;
}
.search-archive .search-input,
.search-archive .btn{
	opacity: 0.451;
}
.search-archive .search-input{
	width: 247px;
	height: 40px;
	border: solid 1px rgb( 184, 194, 200 );
	background: #ffffff;
	font-family: 'Open Sans', sans-serif;
	color: #000;
	line-height: 32px;
	font-size: 16px;
	padding: 0 20px;
	border-right: none;
	-webkit-appearance: none;
	border-radius: 0;
}
.search-archive .btn{
	height: 42px;
	border: solid 1px rgb( 184, 194, 200 );
	background: #ffffff;
	vertical-align: top;
	border-right: none;
	cursor: pointer;
	font-size: 16px;
	color: rgba(128, 142, 150, 0.8);
	-moz-transform: scale(-1, 1);
	-webkit-transform: scale(-1, 1);
	-o-transform: scale(-1, 1);
	transform: scale(-1, 1);
	filter: FlipH;
	-ms-filter: "FlipH"; 
}

.search-archive .search-input:hover,
.search-archive .search-input:active,
.search-archive .search-input:focus,
.search-archive .search-input:hover+.btn,
.search-archive .search-input:active+.btn,
.search-archive .search-input:focus+.btn,
.search-archive .btn:hover+.search-input,
.search-archive .btn:active+.search-input,
.search-archive .btn:focus+.search-input{
	border-color: #155c7e;
	opacity: 1;
}

.search-archive .link-archive{
	font-family: Candara;
	color: #006ead;
	font-size: 18px;
	line-height: 18px;
	border-bottom: 1px solid rgba(0, 110, 173,0.5);
	display: inline-block;
	margin: 20px 0 0 20px;
}

.search-archive .link-archive:hover{
	border-bottom: none;
}


.header .six-plus{
	border-radius: 50%;
	border: 2px solid #ffffff;
	width: 60px;
	height: 60px;
	display: inline-block;
	margin-top: 10px;
	font-family: 'Open Sans', sans-serif;
	color: #ffffff;
	font-weight: 700;
}
.header .six-plus::before{
	content: "6";
	font-size: 40px;
	display: inline-block;
	position: relative;
	right: 11px;
	top: 3px;
}
.header .six-plus::after{
	content: "+";
	font-size: 20px;
	display: inline-block;
	position: relative;
	right: 12px;
	bottom: 12px;
}

/* .obraz-link{	
	margin-top: 22px;
} */

.obraz-link>div{
	display: inline-block;
	padding: 5px;
}

.obraz-link>div:last-child{
	margin-left: 20px;
}

.obraz-link a,
.obraz-link i{
	color: #ffffff;
	font-size: 20px;
}

.obraz-link>div:hover a{
	border-bottom: 1px solid #fff;
}

.obraz-link i{
	display: inline-block;
	margin-right: 10px;
}

.broad-cat{
	display: block;
	float: left;
	width: 480px;
	padding: 40px;
	margin-bottom: 40px;
	margin-right: 40px;
	border: 1px solid #e6eaec;
	background-color: #f5f5f5;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
}

.broad-cat:last-child{
	margin-right: 0;
}

.broad-cat h3{
	font-size: 20px;
}

.broad-cat:hover {
	background-color: #c4d9e3;
	-webkit-box-shadow: 0 0 0 10px #C4D9E3;
	-moz-box-shadow: 0 0 0 10px rgba(196,217,227,1);
	box-shadow: 0 0 0 10px #C4D9E3;
}

.broad-cat img{
	width: 100%;
}

.broad-cat-row .broad-cat .desc{
	margin-top: 20px;
}


.sidebar-text{
	font-size: 14px;
	font-family: 'Open Sans', sans-serif;
	margin-bottom: 15px;
}

.sidebar .sans{
	font-family: 'Open Sans', sans-serif;
	font-size: 15px; /* Приближение из-за подстановки шрифтов */
	line-height: 25px; /* Приближение из-за подстановки шрифтов */
	text-decoration: underline;
	color: #155c7e;
}

.cat-title{
	font-size: 36px;
	margin-bottom: 15px;
}

.block-breadcrumbs{
	font-family: 'Roboto', sans-serif;
	color: #777777;
	font-size: 15px;
	margin-top: -19px;
	margin-bottom: 24px;
}
.block-breadcrumbs a{
	color: #006ead;
	text-decoration: underline;
}

.br-block .left{
	float: left;
	margin-right: 40px;
	margin-bottom: 20px;
}

.br-block h3{
	font-size: 24px;
	line-height: 50px;
	margin-bottom: 16px;
}
.br-block .left img{
	width: 100%;
}

.subjects{
	width: 100%;
	text-align: center;
	background-color: #1b1b1b;
	padding: 28px 40px 94px;
	position: relative;
	float: left;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
}

.subjects h2{
	border: none;
	color: #ffffff;
	font-size: 34px;
	margin-bottom: 15px;
}

.subject{
	margin: 20px 55px;
	display: inline-block;
	max-width: 380px;
	width: 30%;
}
.subject a:hover{
	-webkit-box-shadow: 0 0 5px #ffffff;
	box-shadow: 0 0 5px #ffffff;
}
.subjects .subject-title{
	font-family: 'Roboto', sans-serif;
	color: rgba(255, 255, 255, 0.75);
	font-size: 15px;/* Приближение из-за подстановки шрифтов */
	font-weight: 300;
	text-align: center;
	background-color: rgba(0, 0, 0, 0.5);
	/*height: 40px;*/
	position: absolute;
	bottom: 0;
	width: 100%;
	/*line-height: 40px;*/
	padding: 5px 0;
}

.subjects .attachment-cat-slider-thumb{
	width: 100%;
}

.subjects .broad-cat-archive{
	display: block;
	font-family: 'Roboto', sans-serif;
	color:  #6c6f70;
	font-size: 16px;
	line-height: 24px;
	border-radius: 5px;
	border: 1px solid #3e4545;
	float: right;
	margin: 10px 70px 0 0;
	padding: 3px 19px;
}
.players .broad-cat-archive{
	margin-right: 16px;
}
.broad-cat-archive:hover{
	color: #fff;
	border-color: #fff;
}
.carusel .broad-cat-archive i{
	font-size: 23px;
}
.current-report .photo-arrow,
.carusel .carusel-arrow{
	display: inline-block;
	font-size: 44px;
	color: #909090;
	position: absolute;
	top: 0;
	bottom: 0;
	z-index: 10;
	width: 25px;
	height: 50px;
	margin: auto;
}
.carusel .carusel-arrow{
	height: 114px;
}
.current-report .photo-arrow:hover,
.carusel .carusel-arrow:hover{
	text-shadow: 0 0 10px rgba(255, 255, 255, 1);
}
.current-report .arrow-prev-photo,
.carusel .button-left{
	left: 40px;
}
.current-report .arrow-next-photo,
.carusel .button-right{
	right: 40px;
}
.current-report .arrow-prev-photo{
	left: -60px;
}
.current-report .arrow-next-photo{
	right: -60px;
}


.current-report .arrow-next-photo,
.carusel .button-right{
	-moz-transform: scale(-1, 1);
	-webkit-transform: scale(-1, 1);
	-o-transform: scale(-1, 1);
	transform: scale(-1, 1);
	filter: FlipH;
	-ms-filter: "FlipH"; 
}

.carusel-wrapper {
  overflow: hidden;
  position: relative;
  height: 590px;
}

.carusel-items {
  width: 100%;
  position: relative;
}

.carusel-block{
	width: 100%;
	display: none;
	position: absolute;
	top: 0;
	text-align: center;
}

.carusel .carusel-arrow .icon-arrow-left:before{
	width: auto;
	margin: 0;
}

.sidebar-image{
	width: 100%;
}

.subjects .slider-dots{
	bottom: 44px;
}

.subjects .slider-dots li.dot{
	border-color: rgba(253, 253, 253, 0.75);
	background-color: transparent;
}

.carusel .active{
	display: block;
}

.player{
	position: relative;
	float: left;
	width: 50%;
	margin-top: 15px;
	text-align: center;
	background-color: black;
}

.player-nav{
	width: 100%;
}

.player button,
.player progress,
.player .progressBar,
.player #timeline{
	border: none;
	border-right: 1px solid #454545;
	border-left: 1px solid #161616;
	height: 27px;
	color: white;
	display: block;
	float: left;
	background: -webkit-linear-gradient(90deg, #0c0c0c 0%, #282828 100%);
	background: -moz-linear-gradient(90deg, #0c0c0c 0%, #282828 100%);
	background: -o-linear-gradient(90deg, #0c0c0c 0%, #282828 100%);
	background: -ms-linear-gradient(90deg, #0c0c0c 0%, #282828 100%);
	background: linear-gradient(0deg, #0c0c0c 0%, #282828 100%);
}

::-webkit-progress-bar {
	background: -webkit-linear-gradient(90deg, #0c0c0c 0%, #282828 100%);
	background: -moz-linear-gradient(90deg, #0c0c0c 0%, #282828 100%);
	background: -o-linear-gradient(90deg, #0c0c0c 0%, #282828 100%);
	background: -ms-linear-gradient(90deg, #0c0c0c 0%, #282828 100%);
	background: linear-gradient(0deg, #0c0c0c 0%, #282828 100%);
}
::-webkit-progress-value {
	background-color:  #cf0000;
	background: -webkit-linear-gradient(90deg, rgba(0, 0, 0, 0.7) 0%, rgba(255, 0, 0, 0.7) 100%);
	background: -moz-linear-gradient(90deg, rgba(0, 0, 0, 0.7) 0%, rgba(255, 0, 0, 0.7) 100%);
	background: -o-linear-gradient(90deg, rgba(0, 0, 0, 0.7) 0%, rgba(255, 0, 0, 0.7) 100%);
	background: -ms-linear-gradient(90deg, rgba(0, 0, 0, 0.7) 0%, rgba(255, 0, 0, 0.7) 100%);
	background: linear-gradient(0deg, rgba(0, 0, 0, 0.7) 0%, rgba(255, 0, 0, 0.7) 100%);
}
::-moz-progress-bar {
	background-color:  #cf0000;
	background: -webkit-linear-gradient(90deg, rgba(0, 0, 0, 0.7) 0%, rgba(255, 0, 0, 0.7) 100%);
	background: -moz-linear-gradient(90deg, rgba(0, 0, 0, 0.7) 0%, rgba(255, 0, 0, 0.7) 100%);
	background: -o-linear-gradient(90deg, rgba(0, 0, 0, 0.7) 0%, rgba(255, 0, 0, 0.7) 100%);
	background: -ms-linear-gradient(90deg, rgba(0, 0, 0, 0.7) 0%, rgba(255, 0, 0, 0.7) 100%);
	background: linear-gradient(0deg, rgba(0, 0, 0, 0.7) 0%, rgba(255, 0, 0, 0.7) 100%);
}

.players{
	padding: 25px 40px 40px;
	text-align: left;
}

.player-list{
	width: 50%;
	float: right;
	overflow: auto;
	height: 390px;
}
.player-desc{
	display: none;
	width: 100%;
	background-color: rgba(0,0,0,.7);
	color: #ffffff;
	font-family: 'Roboto', sans-serif;
	position: absolute;
	bottom: 27px;
	text-align: left;
	line-height: 18px;
	padding: 12px 20px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
}

.player-desc .desc-title{
	font-size: 15px;
	display: block;
	margin-bottom: 1px;
}
.player-desc .desc-text{
	font-size: 13px;
	font-weight: 300;
}
.player:hover .player-desc{
	display: block;
}
.players .subject{
	margin: 15px;
	width: 220px;
}

.players .subjects .subject-title{
	font-size: 13px;
}

.players .broad-cat-archive{
	right: 56px;
}

.players #timeline{
	font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
	font-size: 13px;
	line-height: 28px;
	padding: 0 8px;
}

.players .play-pause,
.players .volume,
.players .fullscreen{
	width: 34px;
}

.players #timeline{
	width: 76px;
}

.players .fullscreen{
	float: right;
}

.players .progressBar{
	width: auto;
	position: relative;
	float: none;
	margin: 0 34px 0 160px;
}

.players .progressBar .timeBar{
	position: absolute;
	top: 0;
	left: 0;
	width: 0;
	height: 100%;
	background-color:  #cf0000;
	background: -webkit-linear-gradient(90deg, rgba(0, 0, 0, 0.7) 0%, rgba(255, 0, 0, 0.7) 100%);
	background: -moz-linear-gradient(90deg, rgba(0, 0, 0, 0.7) 0%, rgba(255, 0, 0, 0.7) 100%);
	background: -o-linear-gradient(90deg, rgba(0, 0, 0, 0.7) 0%, rgba(255, 0, 0, 0.7) 100%);
	background: -ms-linear-gradient(90deg, rgba(0, 0, 0, 0.7) 0%, rgba(255, 0, 0, 0.7) 100%);
	background: linear-gradient(0deg, rgba(0, 0, 0, 0.7) 0%, rgba(255, 0, 0, 0.7) 100%);
}

.players video{
	max-width: 720px;
}

.archives .cat-title{
	margin-right: 30px;
}

.archives .cat-title,
.archives .cat-archive-link{
	display: inline-block;
}

.archives .cat-archive-link{
	font-family: 'Roboto', sans-serif;
	color: #006ead;
	font-size: 18px;
	margin-right: 30px;
	text-decoration: underline;
}

.archives .cat-archive-link.current-term{
	text-decoration: none;
	color: #000000;
}

.archives .search-archive{
	float: right;
}

.br-block.archives{
	width: 100%;
	border-bottom: 2px solid #e6eaec;
	padding-bottom: 23px;
}

.archive-year{
	width: 80px;
	float: left;
	text-align: center;
}

.archive-year a{
	font-family: 'Roboto', sans-serif;
	color: #006ead;
	font-size: 20px;
	text-decoration: underline;
	display: block;
	margin: 15px auto;
}
.archive-year a.current-year{
	color: #454545;
	text-decoration: none;
}
.subjects.archives{
	text-align: left;
	float: right;
	width: 1412px;
	padding: 20px;
}
.archives .subject{
	width: 320px;
	margin: 10px;
}

.year-stamp{
	font-family: 'Roboto', sans-serif;
	color: #454545;
	font-size: 26px;
	text-align: left;
	margin: 50px 0 20px;
	padding-left: 108px;
}

.pager{
	font-family: 'Roboto', sans-serif;
	color:  #454545;
	font-size: 18px;/* Приближение из-за подстановки шрифтов */
	font-weight: 500;
	line-height: 24px;/* Приближение из-за подстановки шрифтов */
	text-align: center;
	width: 100%;
	float: right;
	margin-top: 40px;
}

.pager .current{
	font-size: 22px;
}

.churches-items,
.photo-reports-items{
	margin-left: -20px;
	margin-right: -20px;
}

.churches .church,
.photo-reports .report{
	display: block;
	width: 268px;
	float: left;
	border: 2px solid #e6eaec;
	margin: 20px;
	text-align: center;
	padding-bottom: 15px;
}

.single-clergy .churches .church{
	width: 242px;
}
.photo-reports .report{
	width: 346px;
	position: relative;
}
.photo-reports .report .img-thumb{
	height: 220px;
	overflow: hidden;
	width: 100%;
}
.saints .saint:hover,
.churches .church:hover,
.photo-reports .report:hover{
	background-color: #c4d9e3;
	-webkit-box-shadow: 0 0 0 10px rgba(196,217,227,1);
	-moz-box-shadow: 0 0 0 10px rgba(196,217,227,1);
	box-shadow: 0 0 0 10px rgba(196,217,227,1);
}

.churches .church img,
.photo-reports .report img{
	width: 100%;
}

.churches .church .church-title{
	font-size: 24px;
	margin: 20px 0;
	padding: 0 10px;
}

.churches .church .church-address{
	font-family: 'Open Sans', sans-serif;
	font-size: 15px;
	color: #777777;
	padding: 0 10px;
}

.photo-reports .report .date{
	float: left;
}

.photo-reports .report .reports-top{
	padding: 20px;
}

.photo-reports .report .report-title{
	font-size: 18px;
	padding: 0 20px;
	margin-bottom: 50px;
}

.photo-reports .report .report-author{
	position: absolute;
	bottom: 20px;
	right: 20px;
}


.q-block .pager nav,
.archive-common .pager nav,
.saints .pager nav,
.block .pager nav,
.photo-reports .pager nav{
	display: inline-block;
	white-space: normal;
}

.q-block .pager .first-last-link,
.archive-common .pager .first-last-link,
.saints .pager .first-last-link,
.block .pager .first-last-link,
.photo-reports .pager .first-last-link{
	font-family: 'Open Sans', sans-serif;
	font-size: 13px;
	font-weight: 600;
	line-height: 28px;
	text-transform: uppercase;
	display: inline-block;
	border: 2px solid #d4d9dc;
	color: #738087;
	-webkit-box-sizing: content-box;
	-moz-box-sizing: content-box;
	box-sizing: content-box;
	width: 100px;
	height: 28px;
	-webkit-border-radius: 40px;
	border-radius: 40px;
	text-align: center;
	background: rgba(0,0,0,0);
	-webkit-transition: background-color 0.3s cubic-bezier(0, 0, 0, 0), color 0.3s cubic-bezier(0, 0, 0, 0), width 0.3s cubic-bezier(0, 0, 0, 0), border-width 0.3s cubic-bezier(0, 0, 0, 0), border-color 0.3s cubic-bezier(0, 0, 0, 0);
	-moz-transition: background-color 0.3s cubic-bezier(0, 0, 0, 0), color 0.3s cubic-bezier(0, 0, 0, 0), width 0.3s cubic-bezier(0, 0, 0, 0), border-width 0.3s cubic-bezier(0, 0, 0, 0), border-color 0.3s cubic-bezier(0, 0, 0, 0);
	-o-transition: background-color 0.3s cubic-bezier(0, 0, 0, 0), color 0.3s cubic-bezier(0, 0, 0, 0), width 0.3s cubic-bezier(0, 0, 0, 0), border-width 0.3s cubic-bezier(0, 0, 0, 0), border-color 0.3s cubic-bezier(0, 0, 0, 0);
	transition: background-color 0.3s cubic-bezier(0, 0, 0, 0), color 0.3s cubic-bezier(0, 0, 0, 0), width 0.3s cubic-bezier(0, 0, 0, 0), border-width 0.3s cubic-bezier(0, 0, 0, 0), border-color 0.3s cubic-bezier(0, 0, 0, 0);
}

.q-block .pager .first-last-link:hover,
.archive-common .pager .first-last-link:hover,
.saints .pager .first-last-link:hover,
.block .pager .first-last-link:hover,
.photo-reports .pager .first-last-link:hover{
	color: rgba(255,255,255,1);
	background: #006ead;
}

.q-block .pager .page-numbers,
.archive-common .pager .page-numbers,
.saints .pager .page-numbers,
.block .pager .page-numbers,
.photo-reports .pager .page-numbers{
	font-family: 'Open Sans', sans-serif;
	color: #006ead;
	font-size: 15px; /* Приближение из-за подстановки шрифтов */
	font-weight: 700;
	line-height: 24px; /* Приближение из-за подстановки шрифтов */
	text-align: center;
	display: inline-block;
	min-width: 26px;
	vertical-align: middle;
	margin: 0 10px;
}

.q-block .pager a.page-numbers:hover,
.archive-common .pager a.page-numbers:hover,
.saints .pager a.page-numbers:hover,
.block .pager a.page-numbers:hover,
.photo-reports .pager a.page-numbers:hover{
	border-bottom: 3px solid #006ead;
}
.q-block .pager a.next:hover,
.archive-common .pager a.next:hover,
.saints .pager a.next:hover,
.block .pager a.next:hover,
.photo-reports .pager a.next:hover,
.q-block .pager a.prev:hover,
.archive-common .pager a.prev:hover,
.saints .pager a.prev:hover,
.block .pager a.prev:hover,
.photo-reports .pager a.prev:hover{
	border: none;
}
.q-block .pager .current,
.archive-common .pager .current,
.saints .pager .current,
.block .pager .current,
.photo-reports .pager .current{
	color: #7a878d;
	border-bottom: 3px solid #bec7cb;
}

.q-block .pager .nav-links .prev,
.archive-common .pager .nav-links .prev,
.saints .pager .nav-links .prev,
.block .pager .nav-links .prev,
.photo-reports .pager .nav-links .prev,
.q-block .pager .nav-links .next,
.archive-common .pager .nav-links .next,
.saints .pager .nav-links .next,
.block .pager .nav-links .next,
.photo-reports .pager .nav-links .next{
	display: inline-block;
	border-radius: 50%;
	background-color: #ffffff;
	color: #006ead;
	width: auto;
	margin: 0 20px;
}

.current-report{
	text-align: center;
}

.report-top{
	width: 100%;
	position: relative;
	margin-bottom: 30px;
	margin-top: 30px;
}

.report-top-content{
	width: 740px;
	margin: 0 auto;
	position: relative;
	text-align: left;
	padding-bottom: 30px;
}

.report-top .report-arrow {
	font-family: 'Open Sans', sans-serif;
	font-size: 13px;
	font-weight: 600;
	line-height: 28px;
	text-transform: uppercase;
	display: inline-block;
	border: 2px solid #494949;
	color: #494949;
	-webkit-box-sizing: content-box;
	-moz-box-sizing: content-box;
	box-sizing: content-box;
	width: 270px;
	height: 28px;
	-webkit-border-radius: 40px;
	border-radius: 40px;
	text-align: center;
	position: absolute;
	top: 0;
	bottom: 0;
	margin: auto;
	padding: 0;
}

.report-top .report-arrow:hover{
	color: #fff;
	border-color: #fff;
}

.report-top .prev-report-link{
	left: 140px;
}

.report-top .next-report-link{
	right: 140px;
}

.report-top .report-author{
	position: absolute;
	bottom: 0;
	right: 0;
}

.report-top .report-name{
	color: #bebebe;
	font-size: 18px;
}

.report-content{
	width: 1100px;
	text-align: left;
	margin: 0 auto;
}

.report-content .report-photo{
	width: 200px;
	height: 130px;
	overflow: hidden;
	display: inline-block;
	margin: 10px;
}
.current-report-photo{
	max-width: 800px;
	height: 520px;
	margin: 10px auto;
	position: relative;
}

.report-content .active-current-report{
	-webkit-box-shadow: 0 0 10px 5px #ffffff;
	box-shadow: 0 0 10px 5px #ffffff;
}

.report-photo img{
	width: 100%;
}

.current-report-photo img{
	max-width: 800px;
}
.block .cat-title{
	font-family: 'PT Sans Narrow', sans-serif;
	margin-bottom: 38px;
}
.block-items .news-item{
	display: block;
	margin-bottom: 30px;
}

.block-items .link-img{
	width: 160px;
}

.block-items .link-img img{
	width: 100%;
}

.block-items .news-link:hover{
	text-decoration: underline;
}

.post-single h1{
	font-size: 32px;
	font-weight: 400;
	line-height: 40px;
	margin-bottom: 14px;
}

.post-single p{
	text-align: justify;
	font-size: 16px;
	line-height: 25px;
	margin-bottom: 15px;
}

/* .post-single li{
	list-style: disc inside;
	margin-bottom: 10px;
} */
.post-single ul{
	list-style-type: disc;
	padding-left: 40px;
}
.post-single .clear > ul,
.post-single > ul{
	padding: 20px;
}
.post-single ul ul{
	list-style-type: circle;
}

.post-single img{
	max-width: 100%;
	height: auto;
}

.post-single h4{
	margin: 15px 0;
}

.post-single h2{
	font-size: 22px;
	line-height: 30px;
	border: none;
	margin: 28px 0 14px;
}

.post-single a{
	color: #155c7e;
}

.post-single a:visited{
	color: #61157e;
}

.post-single h3{
	font-size: 18px;
	font-weight: bold;
	margin: 24px 0 12px;
}

.post-single em{
	font-style: italic;
}

.post-single h6{
	font-size: 13px;
	font-style: italic;
	color: #929292;
	margin: 20px 0;
}

.district-one{
	cursor: pointer;
	border-top: 1px solid #000;
	line-height: 38px;
	background-color: #ffffff;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
}

.district-one:last-child {
	border-bottom: 1px solid #000;
}

.district-one .district-inputs-content{
	display: none;
}

.district-one .district-inputs-title{
	font-weight: bold;
}

dt.gallery-icon img{
	margin: 0 auto;
} 

.saints-items{
	margin-left: -16px;
	margin-right: -16px;
}

.saints .saints-items+h3{
	margin-bottom: 15px;
}
.saints .saint{
	display: block;
	width: 226px;
	float: left;
	border: 2px solid #e6eaec;
	margin: 16px;
	text-align: center;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
}
.saints .saint img{
	display: block;
	width: 100%;
}

.saints h3{
	font-size: 22px;
	margin: 50px 0;
}

.saints .saint .saint-title {
	padding: 20px;
}
.martyrs-items{
	padding-left: 22px;
}
.martyrs-items .martyr{
	color: #006ead;
	line-height: 30px;
	position: relative;
}
.martyrs-items span.martyr{
	color: #000;
}

.martyrs-items .martyr:before{
	content: "";
	display: block;
	width: 16px;
	height: 16px;
	position: absolute;
	left: -20px;
	top: 1px;
	background-image: url(../img/icon-flower.png);
}

.bic_calendar{
	font-family: 'Open Sans', sans-serif;
	color: #1f2f36;
	font-size: 15px;
	font-weight: 600;
	text-align: center;
	position: relative;
}

.bic_calendar .header tr div{
	font-weight: 600;
}

.bic_calendar .header tr{
	margin: 0 25px;
}

.bic_calendar .table{
	margin: 0 auto;
}

.bic_calendar [class^="button-year-"],
.bic_calendar [class*=" button-year-"],
.bic_calendar [class^="button-month-"],
.bic_calendar [class*=" button-month-"]{
	display: block;
	font-size: 24px;
	width: 13px;
	height: 32px;
}

.bic_calendar .button-month-previous{
	left: 0;
}

.bic_calendar .button-month-next{
	right: 0;
}

.bic_calendar [class^="icon-"]:before,
.bic_calendar [class*=" icon-"]:before{
	width: 13px;
	height: 24px;
	margin: 0;
}

.bic_calendar .days-month{
	color: rgba(0, 0, 0, 0.5);
	font-size: 14px;
	font-weight: 600;
	background: transparent;
	height: 38px;
	border-top: 1px solid  #d0d8da;
	border-bottom: 1px solid  #d0d8da;
}

.bic_calendar tr td.day{
	color: rgba(0, 0, 0, 0.5);
	font-size: 16px;
}

.bic_calendar tr td.day a:hover{
	color: #006ead;
}

.sidebar .archive-categories .one-cat {
	font-family: 'Open Sans', sans-serif;
	color: #777777;
	font-size: 15px;
	margin-left: -40px;
	margin-right: -40px;
	border-top: 1px solid #e6eaec;
	line-height: 18px;
	background-color: #ffffff;
	text-align: left;
	/*padding: 10px 0 10px 50px;*/
	padding: 10px 0 10px 20px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
}
.sidebar .archive-categories .sub-cat{
	padding-left: 80px;
	padding-left: 40px;
}
.sidebar .show-all{
	text-align: right;
}
.sidebar .show-all a{
	text-decoration: underline;
	font-family: 'Open Sans', sans-serif;
	color: #777777;
}
.sidebar .show-all a:hover{
	text-decoration: none;
	color: #000;
}

/* Cначала обозначаем стили для IE8 и более старых версий
т.е. здесь мы немного облагораживаем стандартный чекбокс. */
.checkbox {
	vertical-align: top;
	margin: 0 3px 0 0;
	width: 18px;
	height: 18px;
}
/* Это для всех браузеров, кроме совсем старых, которые не поддерживают
селекторы с плюсом. Показываем, что label кликабелен. */
.checkbox + label {
	cursor: pointer;
}

/* Далее идет оформление чекбокса в современных браузерах, а также IE9 и выше.
Благодаря тому, что старые браузеры не поддерживают селекторы :not и :checked,
в них все нижеследующие стили не сработают. */

/* Прячем оригинальный чекбокс. */
.checkbox:not(checked) {
	position: absolute;
	opacity: 0;
}
.icon-check-trebuchet:before {
    display: none;
}
.checkbox + label:hover,
.checkbox:checked + label{
	color: #000;
}
/* .checkbox:not(checked) + label {
	position: relative; будем позиционировать псевдочекбокс относительно label
}
Оформление первой части чекбокса в выключенном состоянии (фон).
.checkbox:not(checked) + label:before {
	content: '';
	position: absolute;
	top: 1px;
	left: -30px;
	margin: auto;
	border-radius: 3px;
	border: 1px solid  #e6eaec;
	background-color: #e6eaec;
	width: 18px;
	height: 18px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
}
Оформление второй части чекбокса в выключенном состоянии (переключатель).
.checkbox:not(checked) + label:after {
	position: absolute;
	top: 1px;
	left: -25px;
	width: 14px;
	height: 14px;
	opacity: 0;
	transition: all .2s; анимация, чтобы чекбокс переключался плавно
}
Меняем фон чекбокса, когда он включен.
.checkbox:checked + label:before {
	background: #f9e19d;
	border-color: #ffb611;
}
Сдвигаем переключатель чекбокса, когда он включен.
.checkbox:checked + label:after {
	opacity: 1;
}
Показываем получение фокуса.
.checkbox:focus + label:before {
	box-shadow: 0 0 0 3px rgba(255,255,0,.5);
} */
.icon-check-trebuchet:after{
	margin: 0;
	width: 14px;
	height: 14px;
	font-size: 14px;
}
.archives .news-block{
	width: 100%;
}
.news-block .news-item{
	min-height: auto;
}
.archives .news-block .news-item .text{
	width: 100%;
}
.archives .news-block hr{
	border-color: #d0d8da;
	height: 1px; 
	border-width: 1px 0 0;
	margin: 6px 0 0;
}
.archives .news-block .news-item .news-link{
	color: #155c7e;
	font-size: 18px;
	line-height: 24px;
	margin-bottom: 2px;
}
.archives .news-block .news-item .desc{
	line-height: 20px;
}
.archives .news-block .news-item span.category {
    float: right;
    font-size: 10px;
    color: white;
    padding: 0 5px;
    text-transform: uppercase;
    font-weight: 600;
	font-family: 'Open Sans', sans-serif;
	border-radius: 1px;
}
.archives .news-block .news-item span.news{
	background-color: #62a641;
}
.archives .news-block .news-item span.arhierejskie-sluzheniya{
	background-color: #41a684;
}
.archives .news-block .news-item span.announcement{
	background-color: #ffb0b0;
}
.archives .news-block .news-item span.special-project{
	background-color: #dd9c00;
}
.archives .news-block .news-item span.post{
	background-color: #df4343;
}
.archives .news-block .news-item span.gallery{
	background-color: #986bbb;
}
.archives .news-block .news-item span.broadcast{
	background-color: #0000ff;
}

.page-id-131252 .archives{
	border-bottom: none;
}

.tax-brcategory .content{
	width: 100%;
}
.loader{
	display: none;
	width: 128px;
	height: 43px;
	background: url(../img/loader.gif);
	margin: 15px auto;
}

.pager a i{
	font-size: 32px;
}
.post-type-archive-object .content h3,
.post-type-archive-orthodox-media .content h3,
.post-type-archive-district .content h3{
	font-weight: 700;
	margin: 20px 0;
}
.post-type-archive-object .content ul,
.post-type-archive-orthodox-media .content ul,
.post-type-archive-district .content ul{
	padding-left: 20px;
}
.post-type-archive-object .content li,
.post-type-archive-orthodox-media .content li,
.post-type-archive-district .content li{
	margin: 10px 0;
	list-style: disc;
}
.post-type-archive-object .content li a,
.post-type-archive-orthodox-media .content li a,
.post-type-archive-district .content li a{
	color: #006ead;
	text-decoration: underline;
}
.post-type-archive-orthodox-media .content .cat-parent{
	font-weight: 400;
	font-size: 22px;
	margin: 20px 0;
}

.post-type-archive-broadcast .broad-cat:nth-child(even) {
	margin-left: 24px;
}

.post-single .post-thumb{
	margin-top: 0;
}
.head-council{
	width: 100%;
}
.head-council .saint{
	float: none;
	margin: 0 auto;
}

.mitrop{
	text-align: center;
	
}

.mitrop > .saint{
	float: none;
	display: inline-block;
	width: 180px;
	vertical-align: top;
}
table p{
	text-align: inherit;
}

.blagchin .saint-thumb{
/* 	overflow: hidden;
max-height: 239px; */
	width: 100%;
}

.churches h2{
	margin: 15px 0;
	font-size: 26px;
}

.churches h3{
	font-size: 20px;
}

.edu .church .church-title{
	font-size: 18px;
}

	/*------------------------------slider------------------------------*/


	.slider-wrapper{
	    margin: 0 auto;
	    position: relative;
	    width: 500px;
	    height: 448px;
	    overflow: hidden;
	}
	.slider-block{
	    display: none;
	    position: absolute;
	    top: 0;
	    left: 0;
	    width: 100%;
	    height: 448px;
	    overflow: hidden;
	    background-color: #e6eaec;
	}
	.slider-block img{
	    max-width: 100%;
	    height: 448px;
	    margin: auto;
	    position: absolute;
	    top: 0; left: 0; bottom: 0; right: 0;
	}
	.slider .active{
	    display: block;
	}
	.slider .arrow{
		line-height: 32px;
		display: inline-block;
		border-radius: 50%;
		background-color: #ffffff;
		width: 32px;
		height: 32px;
		color: #006ead;
		position: absolute;
		margin: auto;
		bottom: 0;
		top: 0;
	}

	.slider .arrow [class^="icon-"]:before, 
	.slider .arrow [class*=" icon-"]:before{
		margin: 0;
	}

	.slider .arrow i{
		font-size: 32px;
	}

	.slider .arrow-prev{
	    left: 40px;
	}
	.slider .arrow-next{
	    right: 40px;
	}

	.slider .slider-layer{
		width: 100%;
		background-color: rgba(255,255,255,0.7);
		position: absolute;
		bottom: 0;
		padding: 14px 20px 20px;
		-webkit-box-sizing: border-box;
		-moz-box-sizing: border-box;
		box-sizing: border-box;
	}

	.slider .slider-layer .slider-link{
		font-size: 20px;
		line-height: 24px;
		overflow: hidden;
		display: block;
		color: #155c7e;
	}

	.slider .slider-layer .slider-link:hover{
		text-decoration: underline;
	}

	.slider .slider-layer .desc{
		margin-top: 10px;
		font-size: 14px;
	}
	/*------------------------------slider------------------------------*/

.edu .blue-link{
	text-decoration: underline;
}
.edu .blue-link:hover{
	text-decoration: none;
}

.edu ul li{
	margin: 7px 0;
}

.edu .title{
	font-size: 20px;
}

.wp-video{
	margin: 0 auto;
}

.sidebar img{
	max-width: 100%
}

.content .gal-link:hover{
	color: #155c7e;
}
#fancybox-outer{
	background-color: transparent !important;
}
#fancybox-content{
	box-shadow: 0 0 10px 5px #FFF;
	border-width: 0 !important;
	margin: 0 auto;
}
.video img{
	max-width: 100%;
}

.show-video-desc,
.video-desc{
	color: #ffffff;
	background-color: rgba(0,0,0,.7);
	font-size: 13px;
	font-family: 'Roboto', sans-serif;
	text-align: left;
	position: absolute;
	bottom: 50px;
	margin: auto;
	padding-top: 15px;
}

.show-video-desc{
	right: 1%;
	display: none;
	cursor: pointer;
	padding: 0;
}

.video-desc .video-title{
	font-size: 15px;
}

.icon-cancel-circled2{
	color: #ffffff;
	position: absolute;
	right: 0;
	cursor: pointer;
	top: 0;
}
.tax-picategory{
	font-family: 'Roboto', sans-serif;
}
.issue-item{
	display: inline-block;
	margin-right: 10px;
	margin-bottom: 10px;
	width: 206px;
	vertical-align: top;
}
.issue-item:hover{
	box-shadow: 0 0 1px #155c7e;
}
/* .issue-item:nth-child(5n){
	margin-right: 0;
} */
.first-issue-item{
	border: 2px solid #155c7e;
	margin-bottom: 10px;
	padding: 10px;
}

.issue-item .link-img{
	float: none;
	width: 100%;
	margin: 0;
}

.issue-item .text{
	padding: 10px;
	text-align: center;
}

.first-issue-item .link-img{
	width: 300px;
}

.first-issue-item .link-img img{
	max-width: 100%;
}
.widget_rss .title{
	margin: 20px 0 10px;
}
.widget_rss .title .rsswidget{
	display: inline-block;
}

.pi-years{
	margin-bottom: 20px;
}

.pi-years .cat-year-link{
	display: inline-block;
	margin: 0 5px;
}

.pi-years .active{
	font-size: 20px;
	font-weight: bold;
}

.pi-years .cat-year-link:hover{
	text-decoration: underline;
}

.post-type-archive-printed-issue .broad-cat h3{
	text-align: center;
	margin-bottom: 10px;
}
.post-type-archive-printed-issue .desc p,
.tax-picategory .desc p{
	margin: 7px 0;
	font-family: Georgia;
	line-height: 25px;
}

.block .block-items .cat-title{
	font-size: 22px;
	margin: 30px 0 15px;
}

.tax-picategory .sidebar .sin-link{
	display: block;
	margin: 7px 0;
}

.tax-picategory .sidebar .sin-link+a{
	display: block;
}

.piside-years{
	text-align: center;
}
.piside-years>div{
	display: block;
	margin: 7px 0;
}
.piside-years a{
	font-family: 'PT Sans Narrow', sans-serif;
	color: #155c7e;
	font-size: 24px;
	text-decoration: underline;
}
.piside-years a:hover{
	text-decoration: none;
}

.printed .broad-cat{
	width: 288px;
	margin-right: 20px;
	padding: 20px;
	position: relative;
}

.printed .broad-cat:last-child{
	margin-right: 0 !important;
}

.printed .broad-cat img{
	max-width: 100% !important;
}

.printed .desc{
	display: none;
}

.printed .more{
	width: 100%;
	background: #ffffff;
	border-bottom-left-radius: 10px;
	border-bottom-right-radius: 10px;
	height: 30px;
	position: absolute;
	top: 100%;
	margin: auto;
	left: 0;
	right: 0;
	border: 1px solid;
	border-top: none;
	cursor: pointer;
}

.printed .more:after{
	content:"";
	position: absolute;
	width: 0;
	height: 0;
	margin: auto;
	left: 0;
	right: 0;
	bottom: -10px;
	border-top: 10px solid #000;
	border-right: 10px solid transparent;
	border-left: 10px solid transparent;
}
.gallery br{
	display: none;
}
.gallery .gallery-item{
	width: auto !important;
	margin: 5px !important;
	opacity: 1;
	float: none !important;
	display: inline-block;
	vertical-align: top;
	-webkit-transition: opacity 0.2s;
	-o-transition: opacity 0.2s;
	transition: opacity 0.2s;
}

.gallery .gallery-item img{
	border: none !important;
}

.gallery .gallery-item a{
	display: block;
}

.gallery .gallery-item:hover{
    opacity: .5;
}
/*----------------mfp------------------*/
.mfp-figure figure {
    margin: 0;
}
.single .mfp-figure:after{
	box-shadow: 0 0 8px 5px rgba(255, 255, 255, 0.6);
}
.mfp-bottom-bar{
	padding-top: 10px;
}
/* overlay at start */
.mfp-fade.mfp-bg {
  opacity: 0;

  -webkit-transition: all 1s ease-out;
  -moz-transition: all 1s ease-out;
  transition: all 1s ease-out;
}
/* overlay animate in */
.mfp-fade.mfp-bg.mfp-ready {
  opacity: 0.8;
}
/* overlay animate out */
.mfp-fade.mfp-bg.mfp-removing {
  opacity: 0;
}

/* content at start */
.mfp-fade.mfp-wrap .mfp-content {
  opacity: 0;

  -webkit-transition: all 1s ease-out;
  -moz-transition: all 1s ease-out;
  transition: all 1s ease-out;
}
/* content animate it */
.mfp-fade.mfp-wrap.mfp-ready .mfp-content {
  opacity: 1;
}
/* content animate out */
.mfp-fade.mfp-wrap.mfp-removing .mfp-content {
  opacity: 0;
}

@-webkit-keyframes fadeIn {
	0% {opacity: 0;}	
	100% {opacity: 1;}
}

@-moz-keyframes fadeIn {
	0% {opacity: 0;}	
	100% {opacity: 1;}
}

@-o-keyframes fadeIn {
	0% {opacity: 0;}	
	100% {opacity: 1;}
}

@keyframes fadeIn {
	0% {opacity: 0;}	
	100% {opacity: 1;}
}
.mfp-figure { 
  -webkit-animation-name: fadeIn;
	-moz-animation-name: fadeIn;
	-o-animation-name: fadeIn;
	animation-name: fadeIn;
	-webkit-animation-duration: 1s;
	-moz-animation-duration: 1s;
	-ms-animation-duration: 1s;
	-o-animation-duration: 1s;
	animation-duration: 1s;
	-webkit-animation-fill-mode: both;
	-moz-animation-fill-mode: both;
	-ms-animation-fill-mode: both;
	-o-animation-fill-mode: both;
	animation-fill-mode: both;
  -webkit-backface-visibility: hidden;
  -moz-backface-visibility:    hidden;
  -ms-backface-visibility:     hidden;
}

/*----------------mfp------------------*/

.post-type-archive-district .content{
	position: relative;
}
.districts .district-block{
	margin-bottom: 60px;
}
.district-block .map{
	float: right;
}
.districts .district-block .map{
	margin-top: -50px;
}
.district-block ul{
	float: left;
}
.district-block:nth-child(2) ul{
	float: right;
}
.district-block:nth-child(2) h3{
	text-align: right;
	padding-right: 120px;
}

.content .district-block ul li a:hover,
.content .district-block ul li a.highlight{
	color: #86d3ff;
	text-decoration: none;
}

.tax-picategory.has-sidebar .container>div{
	float: none;
	display: table-cell;
}

.gallery .gallery-item .wp-caption-text{
	display: none;
}

.term-nativity-cathedral .desc{
	line-height: 24px;
}

.archive .wrapper .container .content #mapcontainer{
	max-width: 100% !important;
}

.page-id-128939 .district-block .map{
	float: left;
}
.page-id-128939 .district-block ul{
	float: right;
}
.page-id-128939 .district-block ul li a{
	font-size: 20px;
}

.page-id-128939 .district-block ul{
	display: block;
	float: none;
	margin-bottom: 30px;
}
.news-switcher{
	float: right;
}
.news-switcher .news-switcher-button{
	display: block;
	width: 30px;
	height: 30px;
	cursor: pointer;
	background-color: #dae2e9;
	opacity: 0.8;
}

.news-switcher .view-plates{
	-moz-border-radius: 2px 0 0 2px;
	-webkit-border-radius: 2px 0 0 2px;
	border-radius: 2px 0 0 2px;
	float: left;
}

.news-switcher .view-list{
	-moz-border-radius: 0 2px 2px 0;
	-webkit-border-radius: 0 2px 2px 0;
	border-radius: 0 2px 2px 0;
	float: right;
}

.news-switcher .view-list:before{
	margin: 7px;
}

.news-switcher .view-plates:before{
	margin: 6px;
	font-size: 18px;
}
.news-switcher .news-switcher-button:hover{
	opacity: 1;
}

.news-switcher .active{
	background-color: #155c7e;
	opacity: 1;
	color: #ffffff;
}

.plates-active .news-item{
	display: inline-block;
	margin-right: 40px;
	width: 338px;
	vertical-align: top;
	margin-bottom: 60px;
}

.plates-active .news-item:nth-child(3n){
	margin-right: 0;
}

.plates-active .news-item .link-img{
	float: none;
	display: block;
	width: 100%;
	margin: 0 0 15px 0;
}

.spec .news-item{
	/* width: 530px; */
	width: 513px;
}

.spec .news-item:nth-child(2n){
	margin-right: 0;
}

.spec .news-item:nth-child(3n){
	margin-right: 40px;
}

.plates-active .news-item .text{
	display: block;
	padding: 0;
}
.churches .desc p strong a{
	text-decoration: underline;
	color: #155c7e;
}
.churches .desc p strong a:hover{
	text-decoration: none;
}

.radiolink-widget #wonderpluginaudio-1{
	display: inline-block !important;
	margin-left: 20px !important;
}
.social-share{
	margin-top: 30px;
}

.content .one-day{
	margin-bottom: 15px;
}

.content .one-day .title-day{
	font-weight: bold;
}

.content .one-day .day-date{
	font-size: 18px;
}

.content .one-day .adv-info,
.content .one-day .time-events{
	font-size: 15px;
}

.content .one-day .temple-fame{
	color: #006ead;
	text-decoration: underline;
}

.icon-play-circled2:before{
	font-size: 22px;
	color: #155c7e;
}

#wonderpluginaudio-2 .amazingaudioplayer-prev,
#wonderpluginaudio-2 .amazingaudioplayer-next,
#wonderpluginaudio-2 .amazingaudioplayer-loop{
	display: none !important;
}
.page-id-132708{
	height: 96px;
	position: relative;
}

#wonderpluginaudio-2{
	position: absolute !important;
	margin: auto !important;
	top: 0;
	bottom: 0;
	left: 0;
	right: 0;
	height: 34px !important;
}
#wonderpluginaudio-2 .amazingaudioplayer-volume-bar{
	left: 100% !important;
	top: 0;
	bottom: 0 !important;
	margin: auto !important;
}

.mfp-ajax-holder .mfp-content .wp-video{
	background-color: #000000;
	position: relative;
}

.mfp-ajax-holder .mfp-close {
	color: #FFF !important;
	top: -40px;
	right: -40px;
}

.mfp-ajax-holder .mfp-close:active{
	top: -41px;
}
.sorting-filter{
	font-weight: bold;
	margin-bottom: 20px;
}
.sorting-filter .item {
	float: left;
	padding: 10px;
}
.sorting-filter .item a {
	text-decoration: none;
	color: #155c7e;
	border-bottom: 1px dotted;
}
.sorting-filter .item.active {
	background-color: rgba(21,92,126,.5);
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
	border-radius: 4px;
}
.sorting-filter .item.active a {
	color: #FFF;
}

.post-single-content .sort-block{
	display: none;
}

.post-single-content .active{
	display: block;
}

.sort-block .photo-reports .report {
	width: 324px;
}
#map2{
	margin-top: -130px;
}
.mejs-overlay{
	max-width: 100% !important;
}
.rss-link img{
	display: inline-block;
	margin-right: 5px;
}

.search-archive .rss-link{
	display: block;
	float: right;
	margin-top: 20px 0 0 0;
}

.alphabeth{
	width: 100%;
}

.alphabeth li{
	display: inline-block;
	margin: 0 18px;
}


.alphabeth li a{
	text-transform: uppercase;
	text-decoration: none;
	color: #155c7e;
	border-bottom: 1px dotted;
}
.alphabeth li .active{
	color: rgba(21,92,126,.5);
	border: 1px solid rgba(21,92,126,.5);
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
	border-radius: 4px;
	padding: 5px;
}

.clergy-items h3{
	font-size: 18px;
	font-weight: bold;
	margin: 24px 0 12px;
	text-align: center;
}

.clergy-items .object-title,
.clergy-items .object-title+a{
	text-align: left;
}

.clergy-deanery {
	position: relative;
	padding-bottom: 50px;
}

.clergy-deanery .cliric-block {
	display: none;
}

.clergy-deanery .cliric-block.current {
	display: block;
}

.clergy-deanery a {
	display: block;
	line-height: 24px;
	margin: 7px 0;
}

.clergy-deanery .block-nums {
	position: absolute;
	left: 60px;
	bottom: 20px;
	margin: auto;
	height: 20px;
	text-align: center;
}

.sidebar .clergy-deanery .block-nums a.current {
	color: #424242;
}
.sidebar .clergy-deanery .block-nums a {
	display: inline-block;
	width: 10px;
	margin: 0 10px;
	line-height: 20px;
	color: #afafaf;
	font-weight: 700;
	text-decoration: none;
}

.sidebar .clergy-deanery .block-nums a.current {
	color: #424242;
}

.first-issue-item p{
	margin: 1em 0;
}

.right-block .slider .desc{
	display: none;
}

.right-block .post-block .slider .desc{
	display: block;
}


.mfp-wrap .mfp-inline-holder .mfp-content, .mfp-wrap .mfp-ajax-holder .mfp-content{
	width: auto;
}
.mfp-content .player{
	width: auto;
}

.q-cat{
	width: 100%;
}

.q-cat li{
	display: inline-block;
	width: 300px;
	margin-bottom: 20px;
}

.q-cat li a{
	font-family: 'Open Sans', sans-serif;
	color: #155c7e;
	line-height: 18px;
	text-decoration: underline;
	font-size: 18px;
}

.q-cat li a:hover {
	text-decoration: none;
}

.question-item{
	margin-bottom: 40px;
}

.question-item .question-link{
	color: #155c7e;
	font-size: 20px;
	display: block;
	margin: 8px 0 12px;
}

.question-item .text{
	border-top: 1px solid #e5e5e5;
	border-bottom: 1px solid #e5e5e5;
	min-height: 348px;
}

.single-clergy .content .sin-link{
	font-size: 18px;
}

.single-clergy .content .small-title{
	margin-bottom: 10px;
}

.brc-link i{
	font-size: 16px;
}

.cutep-link{
	float: right;
}

.soc-link{
	float: right;
	margin-top: 20px;
	margin-bottom: 40px;
}

.soc-link a{
	display: inline-block;
	font-size: 18px;
	color: #fff;
	vertical-align: top;
}

.soc-link .icon-tw{
	margin-right: 2px;
}

.soc-link .icon-vk{
	margin-right: 4px;
}
.soc-link .icon-fb:before{
	margin-right: 0;
}

.soc-link .icon-el:before{
	margin-right: 0;
	width: 15px;
}

.soc-link .icon-el{
	font-size: 22px;
}

.soc-link a:hover{
	color: #155c7e;
}
.authorneatekfix {
	margin-bottom: 10px;
	font-family: 'OpenSans', sans-serif;
	color: #acacac;
	font-size: 14px;/* Приближение из-за подстановки шрифтов */
	font-weight: 700;
	line-height: 24px;
	margin-left: 3px;
}

.saints .saint .saint-title .saint-pos {
	color: #666;
}

.footer .img-link-hidden{
	display: none;
}

.widget_rss .title .rsswidget:first-child {
	display: none;
}
#rss-block .title a {
	color: #195e80;
}

#rss-block ul {
	height: 400px;
	overflow: auto;
}

.post-single .wp-caption-text{
	font-size: 12px;
	text-align: center;
}

/* .mfp-title{
	display: none;
} */

.link-img ul.rig {
	margin-left: auto;
	font-size: inherit;
}

.link-img ul.rig li{
	width: 100% !important;
	display: block;
	margin: 0;
	padding: 0;
	background: #fff;
	border: none;
	font-size: inherit;
	box-shadow: none;
}

.link-img ul.rig li img{
	margin: 0;
}

.first-issue-item .link-img{
	position: relative;
}

.first-issue-item .link-img .read-pdf{
	position: absolute;
	bottom: 0;
	left: 0;
	right: 0;
	height: 30px;
	line-height: 30px;
	text-align: center;
	background: rgba(255,255,255,.7);
}

.first-issue-item .link-img:hover .read-pdf{
	background: rgba(255,255,255,.8);
}

.right-block .slider .slider-dots{
	display: none !important;
}

.right-block .slider .arrow{
	display: none;
}

.right-block .slider:hover .arrow{
	display: block;
}

.orlec{
	display: block;
	margin: 0 auto;
}
.orlec.small{
	width: 40px;
	display: none;
}

.poslush ul{
	list-style-position: inside;
}

.super-admin .gallery .gallery-item img{
	width: 358px;
	height: auto;
}
.wp-video-shortcode video, video.wp-video-shortcode {
	height: auto;
}