<style type="text/css">

#wpmchimpab {
min-height: 100px;
display: inline-block;
border: solid #dadbdc;
border-width: 1px 0;
padding: 50px;
padding-bottom: 10px;
width: 600px;
left: 50px;
top: 200px;
position: relative;
background: {{theme.bg_c||'#fff'}};
border: {{theme.addon_bor_w||1}}px solid {{theme.addon_bor_c||'#000'}};
}
#wpmchimpab .wpmchimpa-leftpane{
display: {{theme.addon_dissoc?'none':'inline-block'}};
padding: 0 20px;
float: left;
}
#wpmchimpab .wpmchimpa{
position: relative;
display: inline-block;
width: 59%;
}
#wpmchimpab .addon_heading{
line-height: 34px;
color: {{theme.heading_fc||'#454545'}};
font-size: {{theme.heading_f.s||'18'}}px;
font-weight: {{theme.heading_f.w||'bold'}};
font-family: {{theme.heading_f.f | livepf}};
font-style: {{theme.heading_f.st}};
}
#wpmchimpab .addon_msg, #wpmchimpab .addon_msg *{
font-size: {{theme.msg_f.s}}px;
font-family: {{theme.msg_f.f | livepf}};
}
#wpmchimpab .addon_tbox{
    margin: 10px 0;
    width: 90%;
   -moz-border-radius: 5px;
    -webkit-border-radius: 5px;
    -ms-border-radius: 5px;
    -o-border-radius: 5px;
    border-radius: 5px;
    padding: 0 20px;
    outline:0;
    display: block;
color: {{theme.tbox_fc||'#353535'}};
font-size: {{theme.tbox_f.s||'16'}}px;
font-weight: {{theme.tbox_f.w||'bold'}};
font-family: {{theme.tbox_f.f | livepf}};
font-style: {{theme.tbox_f.st}};
background-color: {{theme.tbox_bgc||'#f8fafa'}};
width: {{theme.tbox_w}}px;
height: {{theme.tbox_h||'45'}}px;
border: {{theme.tbox_bor||'1'}}px solid {{theme.tbox_borc||'#e4e9e9'}};
}
#wpmchimpab .addon_tbox div{
top: 50%;
-webkit-transform: translatey(-50% );
-moz-transform: translatey(-50% );
-ms-transform: translatey(-50% );
-o-transform: translatey(-50% );
transform: translatey(-50% );
position: relative;
}
#wpmchimpab .wpmchimpa-groups{
display: block;
}
#wpmchimpab .wpmchimpa-item{
display:inline-block;
margin: 2px 15px;
}
#wpmchimpab .addon_check {
cursor: pointer;
display: inline-block;
position: relative;
padding-left: 30px;
line-height: 25px;
min-width: 80px;
}
#wpmchimpab .addon_check .cbox{
display: inline-block;
width: 22px;
height: 22px;
left: 0;
bottom: 0;
text-align: center;
position: absolute;
-webkit-box-shadow: 0 0 1px 1px {{theme.check_borc||'#ccc'}};
-moz-box-shadow: 0 0 1px 1px {{theme.check_borc||'#ccc'}};
-ms-box-shadow: 0 0 1px 1px {{theme.check_borc||'#ccc'}};
-o-box-shadow: 0 0 1px 1px {{theme.check_borc||'#ccc'}};
box-shadow: 0 0 1px 1px {{theme.check_borc||'#ccc'}};
-webkit-border-radius: 3px;
-moz-border-radius: 3px;
border-radius: 3px;
-ms-transition: all 0.3s ease-in-out;
-moz-transition: all 0.3s ease-in-out;
-o-transition: all 0.3s ease-in-out;
-webkit-transition: all 0.3s ease-in-out;
transition: all 0.3s ease-in-out;
}
#wpmchimpab .addon_check .ctext{
color: {{theme.check_fc}};
font-size: {{theme.check_f.s}}px;
font-weight: {{theme.check_f.w}};
font-family: {{theme.check_f.f | livepf}};
font-style: {{theme.check_f.st}};
}
#wpmchimpab .addon_check .cbox.checked{
background-color: {{theme.check_c}};
}
#wpmchimpab .addon_check .cbox.checked:after,#wpmchimpab .addon_check:hover .cbox:after{
content:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABUAAAAVCAYAAACpF6WWAAAAtklEQVQ4y2P4//8/A7Ux1Q0cxoaCADIbCUgCMTvVXAoE5kA8CYidyXYpGrAH4iVAHIXiCwoMDQTimUBcBsRMlBrKCsTpUANzkC0j11BuIK6EGlgKsoAkQ4FgChD7AzELVI8YEDdDDawDYk6YQaQY6gg1oAqILYC4D8oHGcyLbBAphoJAKtQgGO4EYiHk2CLHUJAXm6AG9gCxNHoSIMdQEJCFGqiALaGSayjMxQwUGzq0S6nhZygA2ojsbh6J67kAAAAASUVORK5CYII=);
content: {{theme.check_shade | chshade}};
margin-top: 1px;
display: block;
}
#wpmchimpab .addon_check:hover .cbox:after{
opacity: 0.5;
}

#wpmchimpab .addon_button{
color: #fff;
line-height: 45px;
text-align: center;
cursor: pointer;
margin-top: 15px;
text-align: center;
width:100%;
color: {{theme.button_fc||'#fff'}};
font-size: {{theme.button_f.s || "22"}}px;
font-weight: {{theme.button_f.w||'bold'}};
font-family: {{theme.button_f.f | livepf}};
font-style: {{theme.button_f.st}};
{{theme.button_bc? "background-color:"+theme.button_bc+";" : "background-color: #4d90fe;
background-image: -webkit-linear-gradient(top,#4d90fe,#4787ed);
background-image: -moz-linear-gradient(top,#4d90fe,#4787ed);
background-image: -mz-linear-gradient(top,#4d90fe,#4787ed);
background-image: -o-linear-gradient(top,#4d90fe,#4787ed);
background-image: linear-gradient(top,#4d90fe,#4787ed);"}}
width: {{theme.button_w}}px;
height: {{theme.button_h||'45'}}px;
-webkit-border-radius: {{theme.button_br||'3'}}px;
-moz-border-radius: {{theme.button_br||'3'}}px;
border-radius: {{theme.button_br||'3'}}px;
border: {{theme.button_bor||'1'}}px solid {{theme.button_borc||'#3079ed'}};
}
#wpmchimpab .addon_button:hover{
color: {{theme.button_fch}};
background-color: {{theme.button_bch}};
}

.addon_spinner {
margin-top: 15px;
}

.addon_status{
position: relative;
font-size: 18px;
margin-bottom: 15px;
}

#wpmchimpab .wpmchimpa-social{
display: block;
}
#wpmchimpab .wpmchimpa-social::before{
content:"{{theme.soc_head||'Subscribe with'}}";
line-height: 30px;
display: block;
color: {{theme.soc_fc||'#b3b3b3'}};
font-size: {{theme.soc_f.s||'20'}}px;
font-weight: {{theme.soc_f.w}};
font-family: {{(theme.soc_f.f | livepf)}};
font-style: {{theme.soc_f.st}};
}

#wpmchimpab .wpmchimpa-social .wpmchimpa-soc{
margin: 5px;
cursor: pointer;
width:150px;
height: 35px;
margin-bottom: 5px;
border-radius: 5px;
}
#wpmchimpab .wpmchimpa-social .wpmchimpa-soc::before{
display: block;
margin: 4px 6px;
padding: 0px 5px;
display: inline-block;
border-right: 1px solid #cccccc;
height: 23px;
}

#wpmchimpab .wpmchimpa-social .wpmchimpa-soc::after{
position: absolute;
line-height: 35px;
padding-left: 10px;
color: #fff;
}

#wpmchimpab .wpmchimpa-social .wpmchimpa-soc.wpmchimpa-fb {
background: #2d609b;
}
#wpmchimpab .wpmchimpa-social .wpmchimpa-soc.wpmchimpa-fb::before {
content: {{getIcon('fb',25,'#fff')}}
}
#wpmchimpab .wpmchimpa-social .wpmchimpa-soc.wpmchimpa-gp {
background: #eb4026;
}
#wpmchimpab .wpmchimpa-social .wpmchimpa-soc.wpmchimpa-gp::before {
content: {{getIcon('gp',25,'#fff')}}
}
#wpmchimpab .wpmchimpa-social .wpmchimpa-soc.wpmchimpa-ms {
background: #00BCF2;
}
#wpmchimpab .wpmchimpa-social .wpmchimpa-soc.wpmchimpa-ms::before {
content: {{getIcon('ms',25,'#fff')}}
}

#wpmchimpab .wpmchimpa-social .wpmchimpa-soc.wpmchimpa-fb::after {
    content:"Facebook";
}
#wpmchimpab .wpmchimpa-social .wpmchimpa-soc.wpmchimpa-gp::after {
    content:"Google Plus";
}
#wpmchimpab .wpmchimpa-social .wpmchimpa-soc.wpmchimpa-ms::after {
    content:"Outlook";
}

#wpmchimpab .wpmchimpa-tag{
text-align: center;
display: {{theme.tag_en? 'block':'none'}};
}
#wpmchimpab .wpmchimpa-tag,
#wpmchimpab .wpmchimpa-tag *{
pointer-events: none;
color: {{theme.tag_fc||'#000'}};
font-size: {{theme.tag_f.s||'10'}}px;
font-weight: {{theme.tag_f.w||'500'}};
font-family:Arial;
font-family: {{theme.tag_f.f | livepf}};
font-style: {{theme.tag_f.st}};
}
#wpmchimpab .wpmchimpa-tag:before{
content:{{getIcon('lock1',theme.tag_f.s||10,theme.tag_fc||'#000')}};
margin: 5px;
top: 1px;
position: relative;
}
</style>

<div id="wpmchimpab" class="wpmchimpab" >
  <div class="chimpmate-live-sc" ng-click="prev.goto(8)" data-optno="0" data-lhint="Go to Additional Theme Options" style="margin:-30px">7</div>
        <div><div class="chimpmate-live-sc" ng-click="prev.goto(1)" data-optno="3" data-lhint="Go to Custom Message Settings">1</div>
            <div class="addon_heading">{{theme.heading}}</div>
            <div class="addon_msg"><p ng-bind-html="theme.msg | safe"></p></div>
            </div>
        <div class="wpmchimpa-leftpane">
            <div class="wpmchimpa-social">
                <div class="wpmchimpa-soc wpmchimpa-fb"></div>
                <div class="wpmchimpa-soc wpmchimpa-gp"></div>
                <div class="wpmchimpa-soc wpmchimpa-ms"></div>
            </div>
        </div>
        <div class="wpmchimpa" id="wpmchimpa">            
            <div><div class="chimpmate-live-sc righthov" ng-click="prev.goto(2)" data-optno="4" data-lhint="Go to Text Box Settings" style="right: -50px;">2</div>
              <div class="addon_tbox"><div class="in-name">Name</div></div>
              <div class="addon_tbox"><div class="in-mail">Email address</div></div>
            </div>
            <div><div class="chimpmate-live-sc" ng-click="prev.goto(3)" data-optno="5" data-lhint="Go to Checkbox Settings" style="left: 10px;">3</div>
              <div class="wpmchimpa-groups">
                <div class="addon_check_c"></div>
                <div class="wpmchimpa-item">
                    <div class="addon_check">
                      <div class="cbox"></div>
                      <div class="ctext">group1</div>
                    </div>
                </div>
                <div class="wpmchimpa-item">
                    <div class="addon_check">
                      <div class="cbox checked"></div>
                      <div class="ctext">group2</div>
                    </div>
                </div>
              </div>
            </div>
            <div><div class="chimpmate-live-sc righthov" ng-click="prev.goto(4)" data-optno="6" data-lhint="Go to Button Settings" style="right: -50px;">4</div>
              <div class="addon_button">{{theme.button}}</div>
            </div>
          <div><div class="chimpmate-live-sc" ng-click="prev.goto(7)" data-lhint="Go to Tag Settings">5</div>
          <div class="wpmchimpa-tag" ng-bind-html="theme.tag||'Secure and Spam free...' | safe"></div></div>
            <div>
              <div class="chimpmate-live-sc" ng-click="prev.goto(5)" data-optno="7" data-lhint="Go to Spinner Settings" style="right: -50px;">6</div>
              <div class="addon_spinner" ng-bind-html="getSpin(theme.spinner_t||'7','wpmchimpab',theme.spinner_c||'#000')"></div>
            </div>
            
          </div>
  </div>
</div>
