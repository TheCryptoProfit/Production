<style type="text/css">

#wpmchimpas {
width: 500px;
height: 718px;
display: block;
background-color: {{theme.bg_c||'#333'}};
color:#fff;
position: relative;
}
#wpmchimpas .wpmchimpas-inner{
  top: 50%;
-webkit-transform: translateY(-50%);
-moz-transform: translateY(-50%);
-ms-transform: translateY(-50%);
-o-transform: translateY(-50%);
transform: translateY(-50%);
position: absolute;
padding: 15px;
margin: 25px;
width: 450px;
text-align: center;
}
#wpmchimpas h3{
color: {{theme.heading_fc||'#fff'}};
font-size: {{theme.heading_f.s||'18'}}px;
font-weight: {{theme.heading_f.w}};
font-family: {{theme.heading_f.f | livepf}};
font-style: {{theme.heading_f.st}};
}
#wpmchimpas p{
margin-bottom: 15px;
line-height: 20px;
font-size: {{theme.msg_f.s||'14'}}px;
font-family:Arial;
font-family: {{theme.msg_f.f | livepf}};
}
#wpmchimpas .wpmchimpa-groups{
display: inline-block;
overflow:auto; 
}
#wpmchimpas .wpmchimpa-item{
float:left;
margin: 2px 2px;
}


#wpmchimpas .slider_check .ctext {
cursor: pointer;
display: inline-block;
position: relative;
padding-left: 10px;
top: -5px;
margin-right: 10px;
color: {{theme.check_fc||'#686868'}};
font-size: {{theme.check_f.s}}px;
font-weight: {{theme.check_f.w}};
font-family: {{theme.check_f.f | livepf}};
font-style: {{theme.check_f.st}};
}

#wpmchimpas .slider_check .cbox{
display: inline-block;
width: 18px;
height: 18px;
background-color: #ACACAC;
-moz-border-radius: 3px;
-webkit-border-radius: 3px;
border-radius: 3px;
{{theme.check_borc? '-webkit-box-shadow: 0 0 1px 1px '+theme.check_borc+';-moz-box-shadow: 0 0 1px 1px '+theme.check_borc+';-ms-box-shadow: 0 0 1px 1px '+theme.check_borc+';-o-box-shadow: 0 0 1px 1px '+theme.check_borc+';box-shadow: 0 0 1px 1px '+theme.check_borc+';' :''}}
}


#wpmchimpas .slider_check .cbox.checked {
background-color: {{theme.check_c||'#158EC6'}};
}
#wpmchimpas .slider_check .cbox.checked:after,#wpmchimpas .slider_check:hover .cbox:after{
content: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABUAAAAVCAYAAACpF6WWAAAABmJLR0QAAAAAAAD5Q7t/AAAACXBIWXMAAC4jAAAuIwF4pT92AAAAvElEQVQ4T63RwQsBQRzF8U0kycVBScp/wU3yB8jVXS6uFAcHe1JykIu7/3O8qackO2/2t3P4HHZ7892mzZxzWWpyYCEHFnJgIQeR+tBMGR3DHeapolN4wer7vToUsoQn7KFWNdqADYPbfxsV+NWGA4M7fqBU9AELqPO5BzmDJ2gVnQ1FZwwcYQI3PvtwJ3BOXn/N0McFuuKMjPornhm8wkAFY6LekMFRxDY66hX+lCrRUuTAQg4s5MBCDizeX/c4P/MwE9UAAAAASUVORK5CYII=);
content: {{theme.check_shade | chshade}};
}
#wpmchimpas .slider_check:hover .cbox:after{
background-image:{{getIcon(theme.check_mark||'ch1',12,'#444')}};
opacity: 0.5;
}

#wpmchimpas .slider_tbox {
text-align: center;
outline:0;
border-radius: 1px;
-webkit-border-radius: 1px;
-moz-border-radius: 1px;
-ms-border-radius: 1px;
-o-border-radius: 1px;
width: 100%;
-webkit-box-sizing: border-box;
-moz-box-sizing: border-box;
box-sizing: border-box;
margin-bottom:10px;
display: block;
color: #000;
line-height: {{theme.tbox_h||'40'}}px;
color: {{theme.tbox_fc}};
font-size: {{theme.tbox_f.s||'14'}}px;
font-weight: {{theme.tbox_f.w}};
font-family: {{theme.tbox_f.f | livepf}};
font-family:Arial;
font-style: {{theme.tbox_f.st}};
background-color: {{theme.tbox_bgc||'#fff'}};
width: {{theme.tbox_w}}px;
height: {{theme.tbox_h||'40'}}px;
border: {{theme.tbox_bor||'1'}}px solid {{theme.tbox_borc||'#dddddd'}};
}

#wpmchimpas .wpmchimpa-subs-button{
margin: 12px 0;
width: 100%;
text-align: center;
background: #62bc33;
cursor:pointer;
-webkit-box-shadow:none;
-moz-box-shadow:none;
-ms-box-shadow:none;
-o-box-shadow:none;
box-shadow:none;
clear:both;
text-decoration:none;
text-shadow:none;
background: -moz-linear-gradient(left, #62bc33 0%, #8bd331 100%);
background: -webkit-gradient(linear, left top, right top, color-stop(0%,#62bc33), color-stop(100%,#8bd331));
background: -webkit-linear-gradient(left, #62bc33 0%,#8bd331 100%);
background: -o-linear-gradient(left, #62bc33 0%,#8bd331 100%);
background: -ms-linear-gradient(left, #62bc33 0%,#8bd331 100%);
background: linear-gradient(to right, #62bc33 0%,#8bd331 100%);

color: {{theme.button_fc||'#fff'}};
font-size: {{theme.button_f.s||'16'}}px;
font-weight: {{theme.button_f.w}};
font-family:Open Sans;
font-family: {{theme.button_f.f | livepf}};
font-style: {{theme.button_f.st}};
{{theme.button_bc? "background-color:"+theme.button_bc+";" : "background: -moz-linear-gradient(left, #62bc33 0%, #8bd331 100%);
background: -webkit-gradient(linear, left top, right top, color-stop(0%,#62bc33), color-stop(100%,#8bd331));
background: -webkit-linear-gradient(left, #62bc33 0%,#8bd331 100%);
background: -o-linear-gradient(left, #62bc33 0%,#8bd331 100%);
background: -ms-linear-gradient(left, #62bc33 0%,#8bd331 100%);
background: linear-gradient(to right, #62bc33 0%,#8bd331 100%);"}}
width: {{theme.button_w}}px;
height: {{theme.button_h||'40'}}px;
line-height: {{theme.button_h||'40'}}px;
-webkit-border-radius: {{theme.button_br||'1'}}px;
-moz-border-radius: {{theme.button_br||'1'}}px;
border-radius: {{theme.button_br||'1'}}px;
border: {{theme.button_bor||'0'}}px solid {{theme.button_borc}};
}
#wpmchimpas .wpmchimpa-subs-button:hover{
background:#8BD331;
color: {{theme.button_fch||'#fff'}};
background-color: {{theme.button_bch}};
{{theme.button_bch? "background-color:"+theme.button_bch+";" : "background: -moz-linear-gradient(left, #8BD331 0%, #8bd331 100%);
background: -webkit-gradient(linear, left top, right top, color-stop(0%,#8BD331), color-stop(100%,#8bd331));
background: -webkit-linear-gradient(left, #8BD331 0%,#8bd331 100%);
background: -o-linear-gradient(left, #8BD331 0%,#8bd331 100%);
background: -ms-linear-gradient(left, #8BD331 0%,#8bd331 100%);
background: linear-gradient(to right, #8BD331 0%,#8bd331 100%);"}}
border: {{theme.button_bor||'0'}}px solid {{theme.button_borc}};
}
#wpmchimpas .wpmchimpa-signal {
margin:10px auto;
}
#wpmchimpas-trig{
width: 50px;
height: 50px;
position: absolute;
display: block;
left: 500px;
top:{{theme.slider_trigger_top ||'50'}}%;
background: {{theme.slider_trigger_bg || '#000'}};
}
#wpmchimpas-trig:before{ 
content:{{getIcon('a01',32,theme.slider_trigger_c||'#fff')}};
height: 32px;
width: 32px;
display: block;
margin: 8px;
}
#wpmchimpas .wpmchimpa-tag{
display: {{theme.tag_en? 'block':'none'}};
}
#wpmchimpas .wpmchimpa-tag,
#wpmchimpas .wpmchimpa-tag *{
pointer-events: none;
color: {{theme.tag_fc||'#fff'}};
font-size: {{theme.tag_f.s||'10'}}px;
font-weight: {{theme.tag_f.w||'500'}};
font-family:Arial;
font-family: {{theme.tag_f.f | livepf}};
font-style: {{theme.tag_f.st}};
}
#wpmchimpas .wpmchimpa-tag:before{
content:{{getIcon('lock1',theme.tag_f.s||10,theme.tag_fc||'#fff')}};
margin: 5px;
top: 1px;
position: relative;
}
#wpmchimpas-over{
background: rgba(0, 0, 0, 0.4);
height: 100%;
width: 100%;
position: absolute;
display: block;
}
</style>
<div id="wpmchimpas-over"></div>
<div id="wpmchimpas-trig">
  <div class="chimpmate-live-sc" ng-click="prev.goto(9)" data-lhint="Go to Trigger Options" style="top:0;right:0;margin:-10px">7</div>
</div>
<div id="wpmchimpas">
<div class="wpmchimpas-inner">
<div class="chimpmate-live-sc" ng-click="prev.goto(8)" data-lhint="Go to Additional Theme Options" style="top:0">8</div>
	<div><div class="chimpmate-live-sc" ng-click="prev.goto(1)" data-lhint="Go to Custom Message Settings" style="left:30px;">1</div>
    <h3>{{theme.heading}}</h3>
    <div class="slider_msg"><p ng-bind-html="theme.msg | safe"></p></div>
  </div>
  <div><div class="chimpmate-live-sc righthov" ng-click="prev.goto(2)" data-lhint="Go to Text Box Settings" style="right:0;">2</div>
    <div class="slider_tbox"><div class="in-name">Name</div></div>
    <div class="slider_tbox"><div class="in-mail">Email address</div></div>
  </div>
  <div><div class="chimpmate-live-sc" ng-click="prev.goto(3)" data-lhint="Go to Checkbox Settings" style="left:30px;">3</div>
    <div class="wpmchimpa-groups">
     <div class="wpmchimpa-item">
        <div class="slider_check">
          <div class="cbox"></div>
          <div class="ctext">group1</div>
        </div>
      </div>
      <div class="wpmchimpa-item">
        <div class="slider_check">
          <div class="cbox checked"></div>
          <div class="ctext">group2</div>
        </div>
      </div>
    </div>
  </div>
  <div><div class="chimpmate-live-sc righthov" ng-click="prev.goto(4)" data-lhint="Go to Button Settings" style="right:0;">4</div>
    <div class="wpmchimpa-subs-button">{{theme.button}}</div>
  </div>

  <div><div class="chimpmate-live-sc" ng-click="prev.goto(7)" data-lhint="Go to Tag Settings">7</div>
          <div class="wpmchimpa-tag" ng-bind-html="theme.tag||'Secure and Spam free...' | safe"></div></div>
  <div>
    <div class="chimpmate-live-sc" ng-click="prev.goto(5)" data-lhint="Go to Spinner Settings" style="right:0;">5</div>
    <div class="wpmchimpa-signal" ng-bind-html="getSpin(theme.spinner_t||'1','wpmchimpas-inner',theme.spinner_c||'#fff')"></div>
  </div>
</div>
</div>