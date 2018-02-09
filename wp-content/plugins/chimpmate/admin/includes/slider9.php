<style type="text/css">

#wpmchimpas {
width: 500px;
height: 718px;
display: block;
background-color: {{theme.slider_canvas_c||'#333'}};
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
text-align: center;
width: 400px;
margin:0 50px;
padding:0 20px;
background:  {{theme.bg_c||'#27313B'}} no-repeat;
background-size: contain;
-webkit-border-radius:1px;
-moz-border-radius:1px;
border-radius:1px;
}
#wpmchimpas h3{
padding-top:20px;
margin: 0;
color: {{theme.heading_fc||'#F4233C'}};
font-size: {{theme.heading_f.s||'24'}}px;
line-height: {{theme.heading_f.s||'24'}}px;
font-weight: {{theme.heading_f.w}};
font-family: {{theme.heading_f.f | livepf}};
font-style: {{theme.heading_f.st}};
}
#wpmchimpas .slider_msg, #wpmchimpas .slider_msg *{
color: #959595;
font-size: {{theme.msg_f.s||'15'}}px;
font-family: {{theme.msg_f.f | livepf}};
}
#wpmchimpas .slider_msg{
  margin-top: 15px;
}
#wpmchimpas .wpmchimpas{
margin-top: 20px;
}

.wpmchimpas .wpmchimpa_formbox > div,
#wpmchimpas .wpmchimpas > div{
position: relative;
}
#wpmchimpas .wpmchimpa_formbox > div:first-of-type{
  width: 65%;
  float: left;
}
#wpmchimpas .wpmchimpa_formbox > div:first-of-type + div{
  width: 35%;
  float: left;
}
#wpmchimpas .wpmchimpa_formbox .slider_tbox{
border-radius: 3px 0 0 3px;
}
#wpmchimpas .slider_tbox{
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
font-family: {{theme.tbox_f.f | livepf}};
font-style: {{theme.tbox_f.st}};
background-color: {{theme.tbox_bgc||'#fff'}};
width: {{theme.tbox_w}}px;
height: {{theme.tbox_h||'40'}}px;
line-height: {{theme.tbox_h||'40'}}px;
border: {{theme.tbox_bor||'1'}}px solid {{theme.tbox_borc||'#efefef'}};
}
#wpmchimpas .slider_tbox .in-text{
top: 50%;
-webkit-transform: translatey(-50% );
-moz-transform: translatey(-50% );
-ms-transform: translatey(-50% );
-o-transform: translatey(-50% );
transform: translatey(-50% );
position: relative;
}
.slider_tbox.mailicon:before,
.slider_tbox.pericon:before{
content:'';
display: block;
width: 40px;
height: {{theme.tbox_h||'40'}}px;
position: absolute;
top: 0;
left: 0;
}
.slider_tbox.mailicon:before{
background: {{getIcon('a02',15,theme.inico_c||theme.tbox_fc||'#000')}} no-repeat center;
}
.slider_tbox.pericon:before{
background: {{getIcon('c06',15,theme.inico_c||theme.tbox_fc||'#000')}} no-repeat center;
}
#wpmchimpas .wpmchimpa-groups{
display: block;
  margin:5px auto;
}
#wpmchimpas .wpmchimpa-item{
display:inline-block;
margin: 2px 15px;
}
#wpmchimpas .slider_check {
cursor: pointer;
display: inline-block;
position: relative;
padding-left: 30px;
line-height: 14px;
}
#wpmchimpas .slider_check .cbox{
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
#wpmchimpas .slider_check .ctext{
color: {{theme.check_fc||'#fff'}};
font-size: {{theme.check_f.s||'12'}}px;
font-weight: {{theme.check_f.w}};
font-family: {{theme.check_f.f | livepf}};
font-style: {{theme.check_f.st}};
}
#wpmchimpas .slider_check .cbox.checked:after,#wpmchimpas .slider_check:hover .cbox:after{
content:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABUAAAAVCAYAAACpF6WWAAAAtklEQVQ4y2P4//8/A7Ux1Q0cxoaCADIbCUgCMTvVXAoE5kA8CYidyXYpGrAH4iVAHIXiCwoMDQTimUBcBsRMlBrKCsTpUANzkC0j11BuIK6EGlgKsoAkQ4FgChD7AzELVI8YEDdDDawDYk6YQaQY6gg1oAqILYC4D8oHGcyLbBAphoJAKtQgGO4EYiHk2CLHUJAXm6AG9gCxNHoSIMdQEJCFGqiALaGSayjMxQwUGzq0S6nhZygA2ojsbh6J67kAAAAASUVORK5CYII=);
content: {{theme.check_shade | chshade}};
margin: -4px;
display: block;
}
#wpmchimpas .slider_check:hover .cbox:after{
opacity: 0.5;
}
#wpmchimpas .slider_button{
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
#wpmchimpas .slider_button:hover{
color: {{theme.button_fch}};
background-color: {{theme.button_bch||'#FA0B38'}};
}
#wpmchimpas .in-mail+div{
position: absolute;
top: 0;
right: 0;
}
.slider_spinner {
top: 0;
right: 0;
margin: 6px 5px;
-webkit-transform: scale(0.8);
-ms-transform: scale(0.8);
transform: scale(0.8);
}
#slider_tag{
text-align: center;
margin: 5px auto;
display: {{theme.tag_en? 'block':'none'}};
}
#slider_tag,
#slider_tag *{
pointer-events: none;
color: {{theme.tag_fc||'#fff'}};
font-size: {{theme.tag_f.s||'10'}}px;
font-weight: {{theme.tag_f.w}};
font-family:Arial;
font-family: {{theme.tag_f.f | livepf}};
font-style: {{theme.tag_f.st}};
}
#slider_tag:before{
content:{{getIcon('lock1',theme.tag_f.s||10,theme.tag_fc||'#fff')}};
margin: 5px;
top: 1px;
position: relative;
}
#wpmchimpas-trig{
width: 50px;
height: 50px;
position: absolute;
display: block;
left: 500px;
margin: 0 3px;
top:{{theme.slider_trigger_top ||'50'}}%;
background: {{theme.slider_trigger_bg || '#27313B'}};
}
#wpmchimpas-trig:before{ 
content:{{getIcon('b06',32,theme.slider_trigger_c||'#fff')}};
height: 32px;
width: 32px;
display: block;
margin: 8px;
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
  <div class="chimpmate-live-sc" ng-click="prev.goto(8)" data-lhint="Go to Additional Theme Options" style="margin:-15px">8</div>
        <div><div class="chimpmate-live-sc" ng-click="prev.goto(1)" data-lhint="Go to Custom Message Settings">1</div>
            <h3>{{theme.heading}}</h3>
            <div class="slider_msg"><p ng-bind-html="theme.msg | safe"></p></div>
        </div>
        <div class="wpmchimpas">
            <div><div class="chimpmate-live-sc righthov" ng-click="prev.goto(2)" data-lhint="Go to Text Box Settings" style="right: -20px;  top: 10px;">2</div>
              <div class="slider_tbox pericon"><div class="in-text in-name">Name</div></div>
            </div>
          <div class="wpmchimpa_formbox">  
            <div class="slider_tbox mailicon"><div class="in-text in-mail">Email address</div>
              <div>
                  <div class="chimpmate-live-sc righthov" ng-click="prev.goto(5)" data-lhint="Go to Spinner Settings" style="left: 15px;top:25px;">5</div>
                  <div class="slider_spinner" ng-bind-html="getSpin(theme.spinner_t||'8','wpmchimpas',theme.spinner_c||'#000')"></div>
              </div>
            </div>
            <div><div class="chimpmate-live-sc righthov righthov" ng-click="prev.goto(4)" data-lhint="Go to Button Settings" style="right: -20px;">4</div>
              <div class="slider_button">{{theme.button}}</div>
            </div>
            <div style="clear:both"></div>
          </div>
            <div><div class="chimpmate-live-sc" ng-click="prev.goto(3)" data-lhint="Go to Checkbox Settings" style="left:20px;">3</div>
              <div class="wpmchimpa-groups">
                <div class="slider_check_c"></div>
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
            <div><div class="chimpmate-live-sc" ng-click="prev.goto(7)" data-lhint="Go to Tag Settings" style="left:20px;">6</div>
          <div id="slider_tag" ng-bind-html="theme.tag||'Secure and Spam free...' | safe"></div></div>
 </div>
</div>
</div>