<style type="text/css">

#wpmchimpab {
text-align: center;
min-height: 100px;
display: inline-block;
border-width: 1px 0;
width: 495px;
left: 50px;
top: 200px;
position: relative;
background: {{theme.bg_c||'#262E43'}};
border: {{theme.addon_bor_w||0}}px solid {{theme.addon_bor_c||'#000'}};
-webkit-border-radius: 2px;
-moz-border-radius: 2px;
border-radius: 2px;
}
#wpmchimpab .addon_heading{
padding-top:20px;
color: {{theme.heading_fc||'#fff'}};
font-size: {{theme.heading_f.s||'20'}}px;
font-weight: {{theme.heading_f.w}};
font-family: {{theme.heading_f.f | livepf}};
font-style: {{theme.heading_f.st}};
}
#wpmchimpab .addon_msg, #wpmchimpab .addon_msg *{
color: #ADBBE0;
font-size: {{theme.msg_f.s||'12'}}px;
font-family: {{theme.msg_f.f | livepf}};
}
#wpmchimpab .addon_msg{
  margin: 15px 30px 0;
}
#wpmchimpab .wpmchimpa{
margin-top: 20px;
}
#wpmchimpab .wpmchimpa_formbox{
margin: 0 auto;
width: calc(100% - 50px);
}
#wpmchimpab .wpmchimpa_formbox > div{
position: relative;
}
#wpmchimpab .addon_tbox{
text-align: left;
margin-bottom: 10px;
width: 100%;
 padding: 0 10px;
border-radius: 3px;
outline:0;
display: block;
color: {{theme.tbox_fc||'#353535'}};
font-size: {{theme.tbox_f.s||'17'}}px;
font-weight: {{theme.tbox_f.w}};
font-family: {{theme.tbox_f.f | livepf}};
font-style: {{theme.tbox_f.st}};
background-color: {{theme.tbox_bgc||'#fff'}};
width: {{theme.tbox_w}}px;
height: {{theme.tbox_h||'40'}}px;
line-height: {{theme.tbox_h||'40'}}px;
border: {{theme.tbox_bor||''}}px solid {{theme.tbox_borc||'#efefef'}};
}
#wpmchimpab .wpmchimpa-groups{
display: block;
  margin:12px auto 0;
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
min-width: 70px;
}
#wpmchimpab .addon_check .cbox{
display: inline-block;
width: 12px;
height: 12px;
left: 0;
bottom: 0;
text-align: center;
position: absolute;
-webkit-border-radius: 3px;
-moz-border-radius: 3px;
border-radius: 3px;
-ms-transition: all 0.3s ease-in-out;
-moz-transition: all 0.3s ease-in-out;
-o-transition: all 0.3s ease-in-out;
-webkit-transition: all 0.3s ease-in-out;
transition: all 0.3s ease-in-out;
border:1px solid {{theme.check_borc}};
background-color: {{theme.check_c||'#fff'}};
}
#wpmchimpab .addon_check .ctext{
color: {{theme.check_fc||'#fff'}};
font-size: {{theme.check_f.s||'12'}}px;
font-weight: {{theme.check_f.w}};
font-family: {{theme.check_f.f | livepf}};
font-style: {{theme.check_f.st}};
}
#wpmchimpab .addon_check .cbox.checked:after,#wpmchimpab .addon_check:hover .cbox:after{
content:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABUAAAAVCAYAAACpF6WWAAAAtklEQVQ4y2P4//8/A7Ux1Q0cxoaCADIbCUgCMTvVXAoE5kA8CYidyXYpGrAH4iVAHIXiCwoMDQTimUBcBsRMlBrKCsTpUANzkC0j11BuIK6EGlgKsoAkQ4FgChD7AzELVI8YEDdDDawDYk6YQaQY6gg1oAqILYC4D8oHGcyLbBAphoJAKtQgGO4EYiHk2CLHUJAXm6AG9gCxNHoSIMdQEJCFGqiALaGSayjMxQwUGzq0S6nhZygA2ojsbh6J67kAAAAASUVORK5CYII=);
content: {{theme.check_shade | chshade}};
margin: -4px;
display: block;
}
#wpmchimpab .addon_check:hover .cbox:after{
opacity: 0.5;
}

#wpmchimpab .addon_button{
border-radius: 3px;
width: 100%;
text-align: center;
cursor: pointer;
color: {{theme.button_fc||'#fff'}};
font-size: {{theme.button_f.s || "17"}}px;
font-weight: {{theme.button_f.w}};
font-family: {{theme.button_f.f | livepf}};
font-style: {{theme.button_f.st}};
background-color:{{theme.button_bc||'#73C557'}};
width: {{theme.button_w}}px;
height: {{theme.button_h||'42'}}px;
line-height: {{theme.button_h||'42'}}px;
-webkit-border-radius: {{theme.button_br||'3'}}px;
-moz-border-radius: {{theme.button_br||'3'}}px;
border-radius: {{theme.button_br||'3'}}px;
border: {{theme.button_bor||'1'}}px solid {{theme.button_borc||'#50B059'}};
}
#wpmchimpab .addon_button:hover{
color: {{theme.button_fch}};
background-color: {{theme.button_bch||'#50B059'}};
}

#wpmchimpab .addon_button+div{
position: absolute;
top: 0;
right: 0;
}
.addon_spinner {
top: 0;
right: 0;
margin: 6px 5px;
-webkit-transform: scale(0.8);
-ms-transform: scale(0.8);
transform: scale(0.8);
}


#wpmchimpab .wpmchimpa-socialc{
overflow: hidden;
}
#wpmchimpab .wpmchimpa-social{
display: inline-block;
margin: 12px auto 0;
height: 90px;
width: 100%;
background: rgba(75, 75, 75, 0.3);
box-shadow: 0px 1px 1px 1px rgba(116, 116, 116, 0.94);
}
#wpmchimpab .wpmchimpa-social::before{
content:"{{theme.soc_head||'Subscribe with'}}";
width: 100%;
display: block;
margin: 15px auto 5px;
color: {{theme.soc_fc||'#ADACB2'}};
font-size: {{theme.soc_f.s||'13'}}px;
line-height: {{theme.soc_f.s||'13'}}px;
font-weight: {{theme.soc_f.w}};
font-family: {{(theme.soc_f.f | livepf)}};
font-style: {{theme.soc_f.st}};
}

#wpmchimpab .wpmchimpa-social .wpmchimpa-soc{
display: inline-block;
width:40px;
height: 40px;
border-radius: 2px;
cursor: pointer;
border:1px solid {{theme.bg_c || '#262E43'}};
}
#wpmchimpab .wpmchimpa-social .wpmchimpa-soc::before{
content: '';
display: block;
width:40px;
height: 40px;
background: no-repeat center;
}

#wpmchimpab .wpmchimpa-social .wpmchimpa-soc.wpmchimpa-fb::before {
background-image: {{getIcon('fb1',20,'#fff')}}
}
#wpmchimpab .wpmchimpa-social .wpmchimpa-soc.wpmchimpa-fb:hover:before {
background-image: {{getIcon('fb1',20,'#2d609b')}}
}
#wpmchimpab .wpmchimpa-social .wpmchimpa-soc.wpmchimpa-gp::before {
background-image: {{getIcon('gp1',20,'#fff')}}
}
#wpmchimpab .wpmchimpa-social .wpmchimpa-soc.wpmchimpa-gp:hover:before {
background-image: {{getIcon('gp1',20,'#eb4026')}}
}
#wpmchimpab .wpmchimpa-social .wpmchimpa-soc.wpmchimpa-ms::before {
background-image: {{getIcon('ms1',20,'#fff')}}
}
#wpmchimpab .wpmchimpa-social .wpmchimpa-soc.wpmchimpa-ms:hover:before {
background-image: {{getIcon('ms1',20,'#00BCF2')}}
}

#wpmchimpab.wosoc .wpmchimpa-social {
display: none;
}
#wpmchimpab .wpmchimpa-tag{
margin: 5px auto;
display: {{theme.tag_en? 'block':'none'}};
}
#wpmchimpab .wpmchimpa-tag,
#wpmchimpab .wpmchimpa-tag *{
pointer-events: none;
color: {{theme.tag_fc||'#68728D'}};
font-size: {{theme.tag_f.s||'10'}}px;
font-weight: {{theme.tag_f.w}};
font-family:Arial;
font-family: {{theme.tag_f.f | livepf}};
font-style: {{theme.tag_f.st}};
}
#wpmchimpab .wpmchimpa-tag:before{
content:{{getIcon('lock1',theme.tag_f.s||10,theme.tag_fc||'#68728D')}};
margin: 5px;
top: 1px;
position: relative;
}
</style>

<div id="wpmchimpab" class="wpmchimpab"  ng-class="{'wosoc':theme.addon_dissoc}">
  <div class="chimpmate-live-sc" ng-click="prev.goto(8)" data-lhint="Go to Additional Theme Options" style="margin:-30px">7</div>
        
          <div><div class="chimpmate-live-sc" ng-click="prev.goto(1)" data-lhint="Go to Custom Message Settings">1</div>
            <div class="addon_heading">{{theme.heading}}</div>
            <div class="addon_msg"><p ng-bind-html="theme.msg | safe"></p></div>
            </div>
        <div class="wpmchimpa" id="wpmchimpa">  
              <div class="wpmchimpa_formbox">
          
            <div><div class="chimpmate-live-sc righthov" ng-click="prev.goto(2)" data-lhint="Go to Text Box Settings" style="right: -50px;">2</div>
              <div class="addon_tbox">Name</div>
              <div class="addon_tbox">Email address</div>
            </div>
            <div><div class="chimpmate-live-sc righthov" ng-click="prev.goto(4)" data-lhint="Go to Button Settings" style="right: -50px;">4</div>
              <div class="addon_button">{{theme.button}}</div>
              <div>
                <div class="chimpmate-live-sc" ng-click="prev.goto(5)" data-lhint="Go to Spinner Settings" style="right: -50px;top:25px">5</div>
                <div class="addon_spinner" ng-bind-html="getSpin(theme.spinner_t||'3','wpmchimpab',theme.spinner_c||'#000')"></div>
              </div>
            </div>
            <div><div class="chimpmate-live-sc" ng-click="prev.goto(3)" data-lhint="Go to Checkbox Settings" style="left: 10px;">3</div>
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
      <div><div class="chimpmate-live-sc" ng-click="prev.goto(7)" data-lhint="Go to Tag Settings">6</div>
          <div class="wpmchimpa-tag" ng-bind-html="theme.tag||'Secure and Spam free...' | safe"></div></div>
            </div>
            <div class="wpmchimpa-socialc">
              <div class="wpmchimpa-social">
                  <div class="wpmchimpa-soc wpmchimpa-fb"></div>
                  <div class="wpmchimpa-soc wpmchimpa-gp"></div>
                  <div class="wpmchimpa-soc wpmchimpa-ms"></div>
              </div>
            </div>
            
          </div>

     
           
  </div>
</div>
