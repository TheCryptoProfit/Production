<?php 
$theme['msg'] = htmlspecialchars_decode($theme['msg']);
$this->social=true;
?><style type="text/css">
.wpmchimpa-overlay-bg.wpmchimpselector {
display: none;
top: 0;
left: 0;
height:100%;
width: 100%;
cursor: pointer;
z-index: 999999;
background: #000;
background: rgba(0,0,0,0.40);
<?php  if(isset($theme["lite_bg_op"])){
      echo 'background:rgba(0,0,0,'.($theme["lite_bg_op"]/100).');';
    }?>
cursor: default;
position: fixed!important;
}
.wpmchimpa-overlay-bg #wpmchimpa-main *{
 transition: all 0.5s ease;
}
.wpmchimpa-overlay-bg .wpmchimpa-mainc,
.wpmchimpa-overlay-bg .wpmchimpa-maina{
-webkit-transform: translate(0,0);
    height:100%;}
.wpmchimpa-overlay-bg #wpmchimpa-main {
position: absolute;
top: 50%;
left: 50%;
border-radius: 3px;
-webkit-transform: translate(-50%, -50%);
-moz-transform: translate(-50%, -50%);
-ms-transform: translate(-50%, -50%);
-o-transform: translate(-50%, -50%);
transform: translate(-50%, -50%);
width: calc(100% - 20px);
max-width:350px;
background: #262E43;
text-align: center;
<?php  if(isset($theme["bg_c"])){
    echo 'background-color:'.$theme["bg_c"].';';
}?>
}
#wpmchimpa-main #wpmchimpa-newsletterform{
height: 100%;overflow: hidden;
}
#wpmchimpa-main .wpmchimpa{
padding-bottom: 12px;
}
#wpmchimpa h3{
line-height: 20px;
margin-top:20px;
color: #fff;
font-size: 20px;
<?php 
    if(isset($theme["heading_f"]['f'])){
    array_push($wpmc_font, $theme["heading_f"]['f']);
      echo 'font-family:'.$theme["heading_f"]['f'].';';
    }
    if(isset($theme["heading_f"]['s'])){
        echo 'font-size:'.$theme["heading_f"]['s'].'px;';
        echo 'line-height:'.$theme["heading_f"]['s'].'px;';
    }
    if(isset($theme["heading_f"]['w'])){
        echo 'font-weight:'.$theme["heading_f"]['w'].';';
    }
    if(isset($theme["heading_f"]['st'])){
        echo 'font-style:'.$theme["heading_f"]['st'].';';
    }
    if(isset($theme["heading_fc"])){
        echo 'color:'.$theme["heading_fc"].';';
    }
?>
}
#wpmchimpa .wpmchimpa_para{
  margin: 15px 30px 0;
}
#wpmchimpa .wpmchimpa_para,#wpmchimpa .wpmchimpa_para * {
font-size: 12px;
color: #ADBBE0;
<?php if(isset($theme["msg_f"]['f'])){
    array_push($wpmc_font, $theme["msg_f"]['f']);
  echo 'font-family:'.$theme["msg_f"]['f'].';';
}if(isset($theme["msg_f"]['s'])){
    echo 'font-size:'.$theme["msg_f"]['s'].'px;';
}?>
}

#wpmchimpa form{
margin-top: 20px;
overflow: hidden;
}
#wpmchimpa .formbox{
margin: 0 auto;
width: calc(100% - 50px);
}

#wpmchimpa  .wpmchimpa-field{
position: relative;
width:100%;
margin: 0 auto 10px auto;
text-align: left;
}
#wpmchimpa .inputicon{
display: none;
}
#wpmchimpa .wpmc-ficon .inputicon {
display: block;
width: 40px;
height: 40px;
position: absolute;
top: 0;
left: 0;
pointer-events: none;
<?php 
if(isset($theme["tbox_h"])){
  echo 'width:'.$theme["tbox_h"].'px;';
  echo 'height:'.$theme["tbox_h"].'px;';
}
?>
}
#wpmchimpa .wpmchimpa-field.wpmc-ficon input[type="text"],
#wpmchimpa .wpmchimpa-field.wpmc-ficon .inputlabel{
  padding-left: 40px;
  <?php 
if(isset($theme["tbox_h"])){
  echo 'padding-left:'.$theme["tbox_h"].'px;';
  }?>
}
<?php
$col = ((isset($theme["inico_c"]))? $theme["inico_c"] : '#888');
foreach ($form['fields'] as $f) {
  if($f['icon'] != 'idef' && $f['icon'] != 'inone')
    echo '#wpmchimpa .wpmc-ficon [wpmcfield="'.$f['tag'].'"] ~ .inputicon {background: '.$this->getIcon($f['icon'],25,$col).' no-repeat center}';
}
?>
#wpmchimpa .wpmchimpa-field textarea,
#wpmchimpa .wpmchimpa-field select,
#wpmchimpa input[type="text"]{
text-align: left;
width: 100%;
height: 40px;
background: #fff;
 padding: 0 10px;
-webkit-border-radius: 3px;
-moz-border-radius: 3px;
border-radius: 3px;
color: #353535;
font-size:17px;
outline:0;
display: block;
border: 1px solid #efefef;
<?php 
    if(isset($theme["tbox_f"]['f'])){
    array_push($wpmc_font, $theme["tbox_f"]['f']);
      echo 'font-family:'.$theme["tbox_f"]['f'].';';
    }
    if(isset($theme["tbox_f"]['s'])){
        echo 'font-size:'.$theme["tbox_f"]['s'].'px;';
    }
    if(isset($theme["tbox_f"]['w'])){
        echo 'font-weight:'.$theme["tbox_f"]['w'].';';
    }
    if(isset($theme["tbox_f"]['st'])){
        echo 'font-style:'.$theme["tbox_f"]['st'].';';
    }
    if(isset($theme["tbox_fc"])){
        echo 'color:'.$theme["tbox_fc"].';';
    }
    if(isset($theme["tbox_bgc"])){
        echo 'background:'.$theme["tbox_bgc"].';';
    }
    if(isset($theme["tbox_w"])){
        echo 'width:'.$theme["tbox_w"].'px;';
    }
    if(isset($theme["tbox_h"])){
        echo 'height:'.$theme["tbox_h"].'px;';
    }
    if(isset($theme["tbox_bor"]) && isset($theme["tbox_borc"])){
        echo ' border:'.$theme["tbox_bor"].'px solid '.$theme["tbox_borc"].';';
    }
?>
}

#wpmchimpa .wpmchimpa-field.wpmchimpa-multidrop select{
  height: 100px;
}

#wpmchimpa .wpmchimpa-field.wpmchimpa-drop:before{
content: '';
width: 40px;
height: 40px;
position: absolute;
right: 0;
top: 0;
pointer-events: none;
background: no-repeat center;
background-image: <?=$this->getIcon('dd',16,'#000');?>;
<?php 
if(isset($theme["tbox_h"])){
  echo 'width:'.$theme["tbox_h"].'px;';
  echo 'height:'.$theme["tbox_h"].'px;';
}
?>
}
#wpmchimpa input[type="text"] ~ .inputlabel{
position: absolute;
top: 0;
left: 0;
right: 0;
pointer-events: none;
width: 100%;
line-height: 40px;
color: rgba(0,0,0,0.6);
font-size: 17px;
font-weight:500;
padding: 0 10px;
white-space: nowrap;
<?php 
if(isset($theme["tbox_f"]['f'])){
    array_push($wpmc_font, $theme["tbox_f"]['f']);
  echo 'font-family:'.str_replace("|ng","",$theme["tbox_f"]['f']).';';
}
if(isset($theme["tbox_f"]['s'])){
    echo 'font-size:'.$theme["tbox_f"]['s'].'px;';
}
if(isset($theme["tbox_f"]['w'])){
    echo 'font-weight:'.$theme["tbox_f"]['w'].';';
}
if(isset($theme["tbox_f"]['st'])){
    echo 'font-style:'.$theme["tbox_f"]['st'].';';
}
if(isset($theme["tbox_fc"])){
    echo 'color:'.$theme["tbox_fc"].';';
}
?>
}
#wpmchimpa input[type="text"]:valid + .inputlabel{
display: none;
}
#wpmchimpa select.wpmcerror,
#wpmchimpa input[type="text"].wpmcerror{
  border-color: red;
}
#wpmchimpa .wpmchimpa-field.wpmchimpa-check,
#wpmchimpa .wpmchimpa-field.wpmchimpa-radio{
  text-align: left;
}
#wpmchimpa .wpmchimpa-check *,
#wpmchimpa .wpmchimpa-radio *{
color: #fff;
<?php
if(isset($theme["check_f"]["f"])){
  echo 'font-family:'.str_replace("|ng","",$theme["check_f"]["f"]).';';
}
if(isset($theme["check_f"]["s"])){
    echo 'font-size:'.$theme["check_fs"].'px;';
}
if(isset($theme["check_f"]["w"])){
    echo 'font-weight:'.$theme["check_fw"].';';
}
if(isset($theme["check_f"]["st"])){
    echo 'font-style:'.$theme["check_fst"].';';
}
if(isset($theme["check_fc"])){
    echo 'color:'.$theme["check_fc"].';';
}
?>
}
#wpmchimpa .wpmchimpa-item input {
  display: none;
}
#wpmchimpa .wpmchimpa-item span {
  cursor: pointer;
  display: inline-block;
  position: relative;
  padding-left: 35px;
  line-height: 26px;
  margin-right: 10px;
}

#wpmchimpa .wpmchimpa-item span:before,
#wpmchimpa .wpmchimpa-item span:after {
  content: '';
  display: inline-block;
  width: 16px;
  height: 16px;
  left: 0;
  top: 5px;
  position: absolute;
}
#wpmchimpa .wpmchimpa-item span:before {
background-color: #fff;
transition: all 0.3s ease-in-out;
<?php
  if(isset($theme["check_borc"])){
      echo 'border: 1px solid'.$theme["check_borc"].';';
  }
?>
}
#wpmchimpa .wpmchimpa-item input:checked + span:before{
  <?php if(isset($theme["check_c"]))echo 'background: '.$theme["check_c"].';';?>
}
#wpmchimpa .wpmchimpa-item input[type='checkbox'] + span:hover:after, #wpmchimpa input[type='checkbox']:checked + span:after {
  content:'';
  background: no-repeat center;
  <?php if(isset($theme['check_shade']))$chs=$theme['check_shade'];else $chs='1';
  echo 'background-image: '.$this->chshade($chs).';';?>
  left: -1px;
}
#wpmchimpa .wpmchimpa-item input[type='radio'] + span:before {
border-radius: 50%;
width: 16px;
height: 16px;
top: 4px;
}
#wpmchimpa input[type='radio']:checked + span:after {
background: <?php echo ($chs == 1)?'#7C7C7C':'#fafafa';?>;
width: 12px;
height: 12px;
top: 6px;
left: 2px;
border-radius: 50%;
}
#wpmchimpa .wpmcinfierr{
  display: block;
  height: 10px;
  text-align: left;
  line-height: 10px;
  margin-bottom: -10px;
  font-size: 10px;
  color: red;
pointer-events: none;
  <?php
    if(isset($theme["status_f"]['f'])){
    array_push($wpmc_font, $theme["status_f"]['f']);
      echo 'font-family:'.str_replace("|ng","",$theme["status_f"]['f']).';';
    }
    if(isset($theme["status_f"]['w'])){
        echo 'font-weight:'.$theme["status_f"]['w'].';';
    }
    if(isset($theme["status_f"]['st'])){
        echo 'font-style:'.$theme["status_f"]['st'].';';
    }
  ?>
}

#wpmchimpa .wpmchimpa-subs-button{
-webkit-border-radius: 3px;
-moz-border-radius: 3px;
border-radius: 3px;
width: 100%;
color: #fff;
font-size: 17px;
border: 1px solid #50B059;
background-color: #73C557;
height: 42px;
line-height: 42px;
text-align: center;
cursor: pointer;
 <?php
if(isset($theme["button_f"]['f'])){
    array_push($wpmc_font, $theme["button_f"]['f']);
  echo 'font-family:'.$theme["button_f"]['f'].';';
}
if(isset($theme["button_f"]['s'])){
    echo 'font-size:'.$theme["button_f"]['s'].'px;';
}
if(isset($theme["button_f"]['w'])){
    echo 'font-weight:'.$theme["button_f"]['w'].';';
}
if(isset($theme["button_f"]['st'])){
    echo 'font-style:'.$theme["button_f"]['st'].';';
}
if(isset($theme["button_fc"])){
    echo 'color:'.$theme["button_fc"].';';
}
if(isset($theme["button_w"])){
    echo 'width:'.$theme["button_w"].'px;';
}
if(isset($theme["button_h"])){
    echo 'height:'.$theme["button_h"].'px;';
    echo 'line-height:'.$theme["button_h"].'px;';
}
if(isset($theme["button_bc"])){
    echo 'background-color:'.$theme["button_bc"].';';
}
if(isset($theme["button_br"])){
    echo '-webkit-border-radius:'.$theme["button_br"].'px;';
    echo '-moz-border-radius:'.$theme["button_br"].'px;';
    echo 'border-radius:'.$theme["button_br"].'px;';
}
if(isset($theme["button_bor"]) && isset($theme["button_borc"])){
    echo ' border:'.$theme["button_bor"].'px solid '.$theme["button_borc"].';';
}
?>
}

#wpmchimpa .wpmchimpa-subs-button::before{
content: '<?php if(isset($theme['button'])) echo $theme['button'];else echo __( 'Subscribe', 'chimpmate-wpmca' );?>';
}
#wpmchimpa .wpmchimpa-subs-button:hover{
background-color: #50B059;
<?php if(isset($theme["button_fch"])){
    echo 'color:'.$theme["button_fch"].';';
}    
if(isset($theme["button_bch"])){
    echo 'background-color:'.$theme["button_bch"].';';
}?>
}
#wpmchimpa .wpmchimpa-subsc{position: relative;}
#wpmchimpa .wpmchimpa-subs-button.subsicon:before{
padding-left: 42px;
  <?php 
  if(isset($theme["button_w"])){
      echo 'padding-left:'.$theme["button_h"].'px;';
  }
  ?>
}
#wpmchimpa .wpmchimpa-subs-button.subsicon::after{
content:'';
position: absolute;
height: 42px;
width: 42px;
top: 0;
left: 0;
pointer-events: none;
  <?php 
  if(isset($theme["button_h"])){
      echo 'width:'.$theme["button_h"].'px;';
      echo 'height:'.$theme["button_h"].'px;';
  }
  if($theme["button_i"] != 'inone' && $theme["button_i"] != 'idef'){
    $col = ((isset($theme["button_fc"]))? $theme["button_fc"] : '#fff');
     echo 'background: '.$this->getIcon($theme["button_i"],25,$col).' no-repeat center;';
  }
  ?>
}
#wpmchimpa-main .wpmchimpa-signal {
display: none;
position: absolute;
top: 6px;
right: 5px;
-webkit-transform: scale(0.8);
-ms-transform: scale(0.8);
transform: scale(0.8);
}
.wpmchimpa-overlay-bg.signalshow #wpmchimpa-main .wpmchimpa-signal {
  display: inline-block;
}
#wpmchimpa-main .wpmchimpa-feedback{
text-align: center;
position: relative;
color: #ccc;
font-size: 10px;
height: 12px;
top: -91px;
margin-top: -12px;
<?php
if(isset($theme["status_f"]['f'])){
    array_push($wpmc_font, $theme["status_f"]['f']);
  echo 'font-family:'.$theme["status_f"]['f'].';';
}
if(isset($theme["status_f"]['s'])){
  echo 'font-size:'.$theme["status_f"]['s'].'px;';
}
if(isset($theme["status_f"]['w'])){
  echo 'font-weight:'.$theme["status_f"]['w'].';';
}
if(isset($theme["status_f"]['st'])){
  echo 'font-style:'.$theme["status_f"]['st'].';';
}
if(isset($theme["status_fc"])){
  echo 'color:'.$theme["status_fc"].';';
}
?>
}
#wpmchimpa-main .wpmchimpa-tag{
margin: 5px auto;
}
#wpmchimpa-main .wpmchimpa-tag,
#wpmchimpa-main .wpmchimpa-tag *{
color:#68728D;
font-size: 10px;
<?php
  if(isset($theme["tag_f"]['f'])){
    array_push($wpmc_font, $theme["tag_f"]['f']);
    echo 'font-family:'.$theme["tag_f"]['f'].';';
  }
  if(isset($theme["tag_f"]['s'])){
      echo 'font-size:'.$theme["tag_f"]['s'].'px;';
  }
  if(isset($theme["tag_f"]['w'])){
      echo 'font-weight:'.$theme["tag_f"]['w'].';';
  }
  if(isset($theme["tag_f"]['st'])){
      echo 'font-style:'.$theme["tag_f"]['st'].';';
  }
  if(isset($theme["tag_fc"])){
      echo 'color:'.$theme["tag_fc"].';';
  }
?>
}
#wpmchimpa-main .wpmchimpa-tag:before{
content:<?php
  $tfs=10;
  if(isset($theme["tag_f"]['s'])){$tfs=$theme["tag_f"]['s'];}
  $tfc='#68728D';
  if(isset($theme["tag_fc"])){$tfc=$theme["tag_fc"];}
  echo $this->getIcon('lock1',$tfs,$tfc);?>;
margin: 5px;
top: 1px;
position:relative;
}

#wpmchimpa-main .wpmchimpa-social{
display: inline-block;
margin: 0 auto;
height: 90px;
width: 100%;
background: rgba(75, 75, 75, 0.3);
box-shadow: 0px 1px 1px 1px rgba(116, 116, 116, 0.94);
}
#wpmchimpa-main .wpmchimpa-social::before{
content: '<?php if(isset($theme['soc_head'])) echo $theme['soc_head'];else echo __( 'Subscribe with', 'chimpmate-wpmca' );?>';
font-size: 13px;
color: #ADACB2;
width: 100%;
display: block;
margin: 15px auto 5px;
<?php
if(isset($theme["soc_f"]['f'])){
    array_push($wpmc_font, $theme["soc_f"]['f']);
  echo 'font-family:'.$theme["soc_f"]['f'].';';
}
if(isset($theme["soc_f"]['s'])){
    echo 'font-size:'.$theme["soc_f"]['s'].'px;';
}
if(isset($theme["soc_f"]['w'])){
    echo 'font-weight:'.$theme["soc_f"]['w'].';';
}
if(isset($theme["soc_f"]['st'])){
    echo 'font-style:'.$theme["soc_f"]['st'].';';
}
if(isset($theme["soc_fc"])){
    echo 'color:'.$theme["soc_fc"].';';
}
?>
}

#wpmchimpa-main .wpmchimpa-social .wpmchimpa-soc{
display: inline-block;
width:40px;
height: 40px;
border-radius: 2px;
cursor: pointer;
-webkit-transition: all 0.1s ease;
transition: all 0.1s ease;
-webkit-backface-visibility:hidden;
border:1px solid #262E43;
<?php  if(isset($theme["bg_c"])){
    echo 'border-color: '.$theme["bg_c"].';';
}?>
}
#wpmchimpa-main .wpmchimpa-social .wpmchimpa-soc::before{
content: '';
display: block;
width:40px;
height: 40px;
background: no-repeat center;
}

#wpmchimpa-main .wpmchimpa-social .wpmchimpa-soc.wpmchimpa-fb {
<?php if(!isset($settings["fb_api"])){
echo 'display:none;';
}?>
}
#wpmchimpa-main .wpmchimpa-social .wpmchimpa-soc.wpmchimpa-fb::before {
background-image:<?php echo $this->getIcon('fb1',15,'#fff');?>
}
#wpmchimpa-main .wpmchimpa-social .wpmchimpa-soc.wpmchimpa-fb:hover:before {
background-image:<?php echo $this->getIcon('fb1',15,'#2d609b');?>
}
#wpmchimpa-main .wpmchimpa-social .wpmchimpa-soc.wpmchimpa-gp {
<?php if(!isset($settings["gp_api"])){
echo 'display:none;';
}?>
}
#wpmchimpa-main .wpmchimpa-social .wpmchimpa-soc.wpmchimpa-gp:before {
background-image: <?php echo $this->getIcon('gp1',15,'#fff');?>
}
#wpmchimpa-main .wpmchimpa-social .wpmchimpa-soc.wpmchimpa-gp:hover:before {
background-image: <?php echo $this->getIcon('gp1',15,'#eb4026');?>
}
#wpmchimpa-main .wpmchimpa-social .wpmchimpa-soc.wpmchimpa-ms {
<?php if(!isset($settings["ms_api"])){
echo 'display:none;';
}?>
}
#wpmchimpa-main .wpmchimpa-social .wpmchimpa-soc.wpmchimpa-ms::before {
background-image: <?php echo $this->getIcon('ms1',15,'#fff');?>
}
#wpmchimpa-main .wpmchimpa-social .wpmchimpa-soc.wpmchimpa-ms:hover:before {
background-image: <?php echo $this->getIcon('ms1',15,'#00BCF2');?>
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
color: #999;
opacity: 0.4;
<?php if(isset($theme["lite_close_col"])){
echo 'color:'.$theme["lite_close_col"].';';
}?>
}
#wpmchimpa-main .wpmchimpa-close-button:hover:before{
opacity: 1;
}
#wpmchimpa-main .wpmchimpa-feedback.wpmchimpa-done{
font-size: 15px;  top: 0;  margin: 0;height: auto}
#wpmchimpa-main .wpmchimpa-feedback.wpmchimpa-done:before{
content:<?=$this->getIcon('ch1',15,'#fff');?>;
width: 40px;
height: 40px;
border-radius: 20px;
line-height: 46px;
display: block;
background-color: #01E169;
margin: 40px auto;
}

#wpmchimpa-main.wosoc .wpmchimpa-social {
display: none;
}
#wpmchimpa-main.wosoc .wpmchimpa {
height: 100%;
}
@media only screen and (max-device-width : 750px) and (orientation : landscape) {
  .wpmchimpa-overlay-bg #wpmchimpa-main{
-webkit-transform:translate(-50%, -50%) scale(0.8);
    -ms-transform:translate(-50%, -50%) scale(0.8);
    transform:translate(-50%, -50%) scale(0.8);
  }
}

</style>

<div class="wpmchimpa-reset wpmchimpa-overlay-bg wpmchimpselector chimpmatecss">
<div class="wpmchimpa-maina">
  <div class="wpmchimpa-mainc">
  <div id="wpmchimpa-main" class="<?php if(isset($theme['lite_dissoc'])) echo ' wosoc';  ?>">
    <div id="wpmchimpa-newsletterform" class="wpmchimpa-wrapper">
      <div class="wpmchimpa" id="wpmchimpa">
<?php if(isset($theme['heading'])) echo '<h3>'.$theme['heading'].'</h3>';?>
<?php if(isset($theme['msg'])) echo '<div class="wpmchimpa_para">'.$theme['msg'].'</div>';?>
          <form action="" method="post">
              <div class="formbox">
<input type="hidden" name="action" value="wpmchimpa_add_email_ajax"/>
<input type="hidden" name="wpmcform" value="<?php echo $form['id'];?>"/>
<?php $set = array(
  'icon' => false,
  'type' => 1
  );
$this->stfield($form['fields'],$set); 
?>
              <div class="wpmchimpa-subsc">
                <div class="wpmchimpa-subs-button<?php echo (isset($theme['button_i']) && $theme['button_i'] != 'inone' && $theme['button_i'] != 'idef')? ' subsicon' : '';?>"></div>
                <div class="wpmchimpa-signal"><?php 
            echo $this->getSpin(isset($theme["spinner_t"])?$theme["spinner_t"]:'3','wpmchimpa-main',isset($theme["spinner_c"])?$theme["spinner_c"]:'#000');?></div>
              
              </div>
              <?php if(isset($theme['tag_en'])){
              if(isset($theme['tag'])) $tagtxt= $theme['tag'];
              else $tagtxt=__( 'Secure and Spam free...', 'chimpmate-wpmca' );
              echo '<div class="wpmchimpa-tag">'.$tagtxt.'</div>';
              }?>

            </div>

            </form>
          <div class="wpmchimpa-feedback" wpmcerr="gen"></div>
      </div>
            <div class="wpmchimpa-social">
                <div class="wpmchimpa-soc wpmchimpa-fb"></div>
                <div class="wpmchimpa-soc wpmchimpa-gp"></div>
                <div class="wpmchimpa-soc wpmchimpa-ms"></div>
            </div>
            
    </div>
        <div class="wpmchimpa-close-button"></div>
  </div>
</div>
</div>
</div>