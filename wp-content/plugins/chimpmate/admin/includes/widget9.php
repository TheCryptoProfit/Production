<style type="text/css">

#wpmchimpaw {
width: 300px;
display: block;
left: 624px;
text-align: center;
top: 95px;
background: {{theme.bg_c||'#262E43'}};
position: relative;
padding: 0 5px;
}
#wpmchimpaw h3{
padding-top:20px;
color: {{theme.heading_fc||'#F4233C'}};
font-size: {{theme.heading_f.s||'20'}}px;
line-height: {{theme.heading_f.s||'20'}}px;
font-weight: {{theme.heading_f.w}};
font-family: {{theme.heading_f.f | livepf}};
font-style: {{theme.heading_f.st}};
}
#wpmchimpaw .widget_msg, #wpmchimpaw .widget_msg *{
color: #959595;
font-size: {{theme.msg_f.s||'12'}}px;
font-family: {{theme.msg_f.f | livepf}};
}
#wpmchimpaw .widget_msg{
  margin-top: 15px;
  line-height: 14px;
}
#wpmchimpaw .wpmchimpa_form{
margin-top: 20px;
}
#wpmchimpaw .wpmchimpa_formbox > div,
#wpmchimpaw .wpmchimpa_form > div{
position: relative;
}
#wpmchimpaw .wpmchimpa_formbox > div:first-of-type{
  width: 65%;
  float: left;
}
#wpmchimpaw .wpmchimpa_formbox > div:first-of-type + div{
  width: 35%;
  float: left;
}
#wpmchimpaw .wpmchimpa_formbox .widget_tbox{
border-radius: 3px 0 0 3px;
}
#wpmchimpaw .widget_tbox{
text-align: left;
margin-bottom: 10px;
width: 100%;
 padding: 0 10px 0 40px;
border-radius: 3px;
outline:0;
display: block;
color: {{theme.tbox_fc||'#353535'}};
font-size: {{theme.tbox_f.s||'14'}}px;
font-weight: {{theme.tbox_f.w}};
font-family: {{theme.tbox_f.f | livepf}};
font-style: {{theme.tbox_f.st}};
background-color: {{theme.tbox_bgc||'#fff'}};
width: {{theme.tbox_w}}px;
height: {{theme.tbox_h||'35'}}px;
line-height: {{theme.tbox_h||'35'}}px;
border: {{theme.tbox_bor||'1'}}px solid {{theme.tbox_borc||'#efefef'}};
}
#wpmchimpab .widget_tbox .in-text{
top: 50%;
-webkit-transform: translatey(-50% );
-moz-transform: translatey(-50% );
-ms-transform: translatey(-50% );
-o-transform: translatey(-50% );
transform: translatey(-50% );
position: relative;
}
.widget_tbox.mailicon:before,
.widget_tbox.pericon:before{
content:'';
display: block;
width: 40px;
height: {{theme.tbox_h||'35'}}px;
position: absolute;
top: 0;
left: 0;
}
.widget_tbox.mailicon:before{
background: {{getIcon('a02',13,theme.inico_c||theme.tbox_fc||'#000')}} no-repeat center;
}
.widget_tbox.pericon:before{
background: {{getIcon('c06',13,theme.inico_c||theme.tbox_fc||'#000')}} no-repeat center;
}
#wpmchimpaw .wpmchimpa-groups{
display: block;
  margin: 5px auto 5px;
}
#wpmchimpaw .wpmchimpa-item{
display:inline-block;
margin: 2px;
}
#wpmchimpaw .widget_check {
cursor: pointer;
display: inline-block;
position: relative;
padding-left: 30px;
line-height: 14px;
min-width: 85px;
}
#wpmchimpaw .widget_check .cbox{
display: inline-block;
width: 12px;
height: 12px;
left: 0;
top:3px;
text-align: center;
position: absolute;
-webkit-border-radius: 1px;
-moz-border-radius: 1px;
border-radius: 1px;
-ms-transition: all 0.3s ease-in-out;
-moz-transition: all 0.3s ease-in-out;
-o-transition: all 0.3s ease-in-out;
-webkit-transition: all 0.3s ease-in-out;
transition: all 0.3s ease-in-out;
border:1px solid {{theme.check_borc}};
background-color: {{theme.check_c||'#fff'}};
}
#wpmchimpaw .widget_check .ctext{
color: {{theme.check_fc||'#fff'}};
font-size: {{theme.check_f.s||'12'}}px;
font-weight: {{theme.check_f.w}};
font-family: {{theme.check_f.f | livepf}};
font-style: {{theme.check_f.st}};
}
#wpmchimpaw .widget_check .cbox.checked:after,#wpmchimpaw .widget_check:hover .cbox:after{
content:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABUAAAAVCAYAAACpF6WWAAAAtklEQVQ4y2P4//8/A7Ux1Q0cxoaCADIbCUgCMTvVXAoE5kA8CYidyXYpGrAH4iVAHIXiCwoMDQTimUBcBsRMlBrKCsTpUANzkC0j11BuIK6EGlgKsoAkQ4FgChD7AzELVI8YEDdDDawDYk6YQaQY6gg1oAqILYC4D8oHGcyLbBAphoJAKtQgGO4EYiHk2CLHUJAXm6AG9gCxNHoSIMdQEJCFGqiALaGSayjMxQwUGzq0S6nhZygA2ojsbh6J67kAAAAASUVORK5CYII=);
content: {{theme.check_shade | chshade}};
margin: -4px;
display: block;
}
#wpmchimpaw .widget_check:hover .cbox:after{
opacity: 0.5;
}
#wpmchimpaw .wpmchimpa_formbox > div{
  position: relative;
}
#wpmchimpaw .widget_button{
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
height: {{theme.button_h||'35'}}px;
line-height: {{theme.button_h||'35'}}px;
-webkit-border-radius: {{theme.button_br||'3'}}px;
-moz-border-radius: {{theme.button_br||'3'}}px;
border-radius: {{theme.button_br||'3'}}px;
border: {{theme.button_bor||'1'}}px solid {{theme.button_borc||'#FA0B38'}};
}
#wpmchimpaw .widget_button:hover{
color: {{theme.button_fch}};
background-color: {{theme.button_bch||'#FA0B38'}};
}

#wpmchimpaw .in-mail+div{
position: absolute;
top: 0;
right: 0;
}
.widget_spinner {
top: 0;
right: 0;
margin: 4px 5px;
-webkit-transform: scale(0.5);
-ms-transform: scale(0.5);
transform: scale(0.5);
transform-origin:right;
}
#wpmchimpaw .wpmchimpa-tag{
margin: 5px auto;
display: {{theme.tag_en? 'block':'none'}};
}
#wpmchimpaw .wpmchimpa-tag,
#wpmchimpaw .wpmchimpa-tag *{
pointer-events: none;
color: {{theme.tag_fc||'#fff'}};
font-size: {{theme.tag_f.s||'10'}}px;
font-weight: {{theme.tag_f.w}};
font-family:Arial;
font-family: {{theme.tag_f.f | livepf}};
font-style: {{theme.tag_f.st}};
}
#wpmchimpaw .wpmchimpa-tag:before{
content:{{getIcon('lock1',theme.tag_f.s||10,theme.tag_fc||'#fff')}};
margin: 5px;
top: 1px;
position: relative;
}
</style>

<div id="wpmchimpaw">
  <div class="chimpmate-live-sc" ng-click="prev.goto(8)" data-lhint="Go to Additional Theme Options" style="margin:-25px">7</div>
        
        <div class="wpmchimpaw">
            <div><div class="chimpmate-live-sc" ng-click="prev.goto(1)" data-lhint="Go to Custom Message Settings">1</div>
            <h3>{{theme.heading}}</h3>
            <div class="widget_msg"><p ng-bind-html="theme.msg | safe"></p></div>
            </div>
            <div class="wpmchimpa_form">
            <div><div class="chimpmate-live-sc righthov" ng-click="prev.goto(2)" data-lhint="Go to Text Box Settings" style="right: -20px;">2</div>
              <div class="widget_tbox pericon"><div class="in-text in-name">Name</div></div>
            </div>
            <div class="wpmchimpa_formbox">
              <div class="widget_tbox mailicon"><div class="in-text in-mail">Email address</div>
                <div>
                  <div class="chimpmate-live-sc righthov" ng-click="prev.goto(5)" data-lhint="Go to Spinner Settings" style="left:10px;top:25px">5</div>
                  <div class="widget_spinner" ng-bind-html="getSpin(theme.spinner_t||'8','wpmchimpaw',theme.spinner_c||'#000')"></div>
                </div>
              </div>
              <div><div class="chimpmate-live-sc righthov" ng-click="prev.goto(4)" data-lhint="Go to Button Settings" style="right: -20px;">4</div>
                <div class="widget_button">{{theme.button}}</div>
              </div>
              <div style="clear:both"></div>
            </div>
            <div><div class="chimpmate-live-sc" ng-click="prev.goto(3)" data-lhint="Go to Checkbox Settings">3</div>
              <div class="wpmchimpa-groups">
                <div class="widget_check_c"></div>
                <div class="wpmchimpa-item">
                    <div class="widget_check">
                      <div class="cbox"></div>
                      <div class="ctext">group1</div>
                    </div>
                </div>
                <div class="wpmchimpa-item">
                    <div class="widget_check">
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
</div>
