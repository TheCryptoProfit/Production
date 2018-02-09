<style type="text/css">
.wpmchimpa-overlay-bg {
background: rgba(0,0,0,{{theme.lite_bg_op/100 ||'0.4'}});
height: 778px;
width: 1024px;
}

.wpmchimpa-overlay-bg #wpmchimpa-main {
width:650px;
background: {{theme.bg_c || '#27313B'}};
display: inline-block;
position: relative;
left: 50%;
margin: 100px auto;
border-radius: 2px;
text-align: center;
padding:0 125px;
-webkit-transform: translatex(-50% );
-moz-transform: translatex(-50% );
-ms-transform: translatex(-50% );
-o-transform: translatex(-50% );
transform: translatex(-50% );
}
#wpmchimpa{
  position:relative;
}
#wpmchimpa .lite_heading{
margin-top:20px;
color: {{theme.heading_fc||'#F4233C'}};
font-size: {{theme.heading_f.s||'24'}}px;
line-height: {{theme.heading_f.s||'24'}}px;
font-weight: {{theme.heading_f.w}};
font-family: {{theme.heading_f.f | livepf}};
font-style: {{theme.heading_f.st}};
}
#wpmchimpa .lite_msg,#wpmchimpa .lite_msg *{
color: #959595;
font-size: {{theme.msg_f.s||'15'}}px;
font-family: {{theme.msg_f.f | livepf}};
}
#wpmchimpa .lite_msg{
  margin-top: 15px;
}
#wpmchimpa .wpmchimpa_form{
margin-top: 20px;
}

#wpmchimpa .wpmchimpa_formbox > div,
#wpmchimpa .wpmchimpa_form > div{
position: relative;
}
#wpmchimpa .wpmchimpa_formbox > div:first-of-type{
  width: 65%;
  float: left;
}
#wpmchimpa .wpmchimpa_formbox > div:first-of-type + div{
  width: 35%;
  float: left;
}
#wpmchimpa .wpmchimpa_formbox .lite_tbox{
border-radius: 3px 0 0 3px;
}
#wpmchimpa .lite_tbox{
text-align: left;
margin-bottom: 10px;
width: 100%;
border-radius: 3px;
outline:0;
display: block;
 padding: 0 10px 0 40px;

color: {{theme.tbox_fc||'#353535'}};
font-size: {{theme.tbox_f.s||'17'}}px;
font-weight: {{theme.tbox_f.w}};
font-family:Arial;
font-family: {{theme.tbox_f.f | livepf}};
font-style: {{theme.tbox_f.st}};
background-color: {{theme.tbox_bgc||'#fff'}};
width: {{theme.tbox_w}}px;
height: {{theme.tbox_h||'40'}}px;
line-height: {{theme.tbox_h||'40'}}px;
border: {{theme.tbox_bor||'1'}}px solid {{theme.tbox_borc||'#efefef'}};
}
#wpmchimpa .lite_tbox .in-text{
top: 50%;
-webkit-transform: translatey(-50% );
-moz-transform: translatey(-50% );
-ms-transform: translatey(-50% );
-o-transform: translatey(-50% );
transform: translatey(-50% );
position: relative;
}
.lite_tbox.mailicon:before,
.lite_tbox.pericon:before{
content:'';
display: block;
width: 40px;
height: {{theme.tbox_h||'40'}}px;
position: absolute;
top: 0;
left: 0;
}
.lite_tbox.mailicon:before{
background: {{getIcon('a02',15,theme.inico_c||theme.tbox_fc||'#000')}} no-repeat center;
}
.lite_tbox.pericon:before{
background: {{getIcon('c06',15,theme.inico_c||theme.tbox_fc||'#000')}} no-repeat center;
}
#wpmchimpa .wpmchimpa-groups{
display: block;
margin:5px auto;
}
#wpmchimpa .wpmchimpa-item{
display:inline-block;
margin: 2px 15px;
}
#wpmchimpa .lite_check {
cursor: pointer;
display: inline-block;
position: relative;
padding-left: 30px;
line-height: 14px;
}
#wpmchimpa .lite_check .cbox{
display: inline-block;
width: 12px;
height: 12px;
left: 0;
bottom: 0;
text-align: center;
position: absolute;
-webkit-border-radius: 2px;
-moz-border-radius: 2px;
border-radius: 2px;
-ms-transition: all 0.3s ease-in-out;
-moz-transition: all 0.3s ease-in-out;
-o-transition: all 0.3s ease-in-out;
-webkit-transition: all 0.3s ease-in-out;
transition: all 0.3s ease-in-out;
border:1px solid {{theme.check_borc}};
background-color: {{theme.check_c||'#fff'}};
}
#wpmchimpa .lite_check .ctext{
color: {{theme.check_fc||'#fff'}};
font-size: {{theme.check_f.s||'12'}}px;
font-weight: {{theme.check_f.w}};
font-family: {{theme.check_f.f | livepf}};
font-style: {{theme.check_f.st}};
}
#wpmchimpa .lite_check .cbox.checked:after,#wpmchimpa .lite_check:hover .cbox:after{
content:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABUAAAAVCAYAAACpF6WWAAAAtklEQVQ4y2P4//8/A7Ux1Q0cxoaCADIbCUgCMTvVXAoE5kA8CYidyXYpGrAH4iVAHIXiCwoMDQTimUBcBsRMlBrKCsTpUANzkC0j11BuIK6EGlgKsoAkQ4FgChD7AzELVI8YEDdDDawDYk6YQaQY6gg1oAqILYC4D8oHGcyLbBAphoJAKtQgGO4EYiHk2CLHUJAXm6AG9gCxNHoSIMdQEJCFGqiALaGSayjMxQwUGzq0S6nhZygA2ojsbh6J67kAAAAASUVORK5CYII=);
content: {{theme.check_shade | chshade}};
margin: -4px;
display: block;
}
#wpmchimpa .lite_check:hover .cbox:after{
opacity: 0.5;
}

#wpmchimpa .lite_button{
width: 100%;
text-align: center;
cursor: pointer;
border-radius: 0 3px 3px 0;
color: {{theme.button_fc||'#fff'}};
font-size: {{theme.button_f.s || "17"}}px;
font-weight: {{theme.button_f.w}};
font-family: {{theme.button_f.f | livepf}};
font-style: {{theme.button_f.st}};
background-color:{{theme.button_bc||'#FF1F43'}};
width: {{theme.button_w}}px;
height: {{theme.button_h||'40'}}px;
line-height: {{theme.button_h||'40'}}px;
border-radius: {{theme.button_br}}px;
border: {{theme.button_bor||'1'}}px solid {{theme.button_borc||'#FA0B38'}};
}
#wpmchimpa .lite_button:hover{
color: {{theme.button_fch}};
background-color: {{theme.button_bch||'#FA0B38'}};
}
#wpmchimpa .in-mail+div{
position: absolute;
top: 0;
right: 0;
}
#wpmchimpa-main .wpmchimpa-close-button{
position: absolute;
display: block;
top: 0;
right: 0;
width: 25px;
text-align: center;
cursor: pointer;
}
#wpmchimpa-main .wpmchimpa-close-button::before{
content: "\00D7";
font-size: 25px;
line-height: 25px;
font-weight: 100;
color: {{theme.lite_close_col||'#999'}};
opacity: 0.4;
}
#wpmchimpa-main .wpmchimpa-close-button:hover:before{
opacity: 1;
}


#wpmchimpa .wpmchimpa-tag{
margin: 5px auto;
display: {{theme.tag_en? 'block':'none'}};
}
#wpmchimpa .wpmchimpa-tag,
#wpmchimpa .wpmchimpa-tag *{
pointer-events: none;
color: {{theme.tag_fc||'#fff'}};
font-size: {{theme.tag_f.s||'10'}}px;
font-weight: {{theme.tag_f.w}};
font-family:Arial;
font-family: {{theme.tag_f.f | livepf}};
font-style: {{theme.tag_f.st}};
}
#wpmchimpa .wpmchimpa-tag:before{
content:{{getIcon('lock1',theme.tag_f.s||10,theme.tag_fc||'#fff')}};
margin: 5px;
top: 1px;
position: relative;
}
.lite_spinner {
top: 0;
right: 0;
margin: 6px 5px;
-webkit-transform: scale(0.8);
-ms-transform: scale(0.8);
transform: scale(0.8);
}
</style>

<div class="wpmchimpa-overlay-bg wpmchimpa-wrapper" id="lightbox1">
	<div id="wpmchimpa-main">
        <div class="chimpmate-live-sc" ng-click="prev.goto(8)" data-lhint="Go to Additional Theme Options" style="margin:-25px;">7</div>
		<div id="wpmchimpa-newsletterform">
			<div class="wpmchimpa" id="wpmchimpa">
    			<div><div class="chimpmate-live-sc" ng-click="prev.goto(1)" data-lhint="Go to Custom Message Settings">1</div>
            <div class="lite_heading">{{theme.heading}}</div>
      			<div class="lite_msg"><p ng-bind-html="theme.msg | safe"></p></div>
          </div>
        <div class="wpmchimpa_form">
          <div><div class="chimpmate-live-sc righthov" ng-click="prev.goto(2)" data-lhint="Go to Text Box Settings" style="right: -20px;">2</div>
            <div class="lite_tbox pericon"><div class="in-text in-name">Name</div></div>
      			
          </div>
          <div class="wpmchimpa_formbox">
            <div class="lite_tbox mailicon"><div class="in-text in-mail">Email address</div>
              <div>
                    <div class="chimpmate-live-sc" ng-click="prev.goto(5)" data-lhint="Go to Spinner Settings" style="top: 25px;">5</div>
                    <div class="lite_spinner" ng-bind-html="getSpin(theme.spinner_t||'8','wpmchimpa-wrapper',theme.spinner_c||'#000')"></div>
               </div>
            </div>
            <div><div class="chimpmate-live-sc righthov" ng-click="prev.goto(4)" data-lhint="Go to Button Settings" style="right: -20px;">4</div>
          		<div class="lite_button">{{theme.button}}</div>
            </div>
            <div style="clear:both"></div>
          </div>
          <div><div class="chimpmate-live-sc" ng-click="prev.goto(3)" data-lhint="Go to Checkbox Settings">3</div>
         		<div class="wpmchimpa-groups">
              <div class="lite_check_c"></div>
              <div class="wpmchimpa-item">
                  <div class="lite_check">
                    <div class="cbox"></div>
                    <div class="ctext">group1</div>
                  </div>
              </div>
              <div class="wpmchimpa-item">
                  <div class="lite_check">
                    <div class="cbox checked"></div>
                    <div class="ctext">group2</div>
                  </div>
              </div>
            </div>
          </div>
			<div><div class="chimpmate-live-sc" ng-click="prev.goto(7)" data-lhint="Go to Tag Settings">6</div>
	    		<div class="wpmchimpa-tag" ng-bind-html="theme.tag||'Secure and Spam free...' | safe"></div></div>
           </div>
          </div>
			</div>
      <div class="wpmchimpa-close-button"></div>
	</div>        
</div>