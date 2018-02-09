<?php 
$theme['msg'] = htmlspecialchars_decode($theme['msg']);
$this->social=true;
?>
<style type="text/css">

.wpmchimpas {
background-color: #333;
<?php
    if(isset($theme["slider_canvas_c"])){
        echo 'background-color:'.$theme["slider_canvas_c"].';';
    }?>
}
.wpmchimpas .wpmchimpas-inner {
text-align: center;
border-radius:3px;
background: #262E43;
padding: 0;
margin:0 50px;
<?php
if(isset($theme["bg_c"])){
    echo 'background-color:'.$theme["bg_c"].';';
}?>
}

.wpmchimpas h3{
line-height: 20px;
padding-top:20px;
color: #fff;
font-size: 20px;
<?php 
if(isset($theme["heading_f"]['f'])){
    array_push($wpmc_font, $theme["heading_f"]['f']);
echo 'font-family:'.$theme["heading_f"]['f'].';';
}
if(isset($theme["heading_f"]['s'])){
echo 'font-size:'.$theme["heading_f"]['s'].'px;';
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
.wpmchimpas .wpmchimpa_para{
  margin: 15px 30px 0;
}
.wpmchimpas .wpmchimpa_para,.wpmchimpas .wpmchimpa_para * {
font-size: 12px;
color: #ADBBE0;
<?php if(isset($theme["msg_f"]['f'])){
    array_push($wpmc_font, $theme["msg_f"]['f']);
echo 'font-family:'.$theme["msg_f"]['f'].';';
}if(isset($theme["msg_f"]['s'])){
echo 'font-size:'.$theme["msg_f"]['s'].'px;';
}?>
}
.wpmchimpas form{
margin-top: 20px;
overflow: hidden;
}
.wpmchimpas .formbox{
margin: 0 auto;
width: calc(100% - 50px);
}

.wpmchimpas .wpmchimpa-field{
position: relative;
width:100%;
margin: 0 auto 10px auto;
text-align: left;
}
.wpmchimpas .inputicon{
display: none;
}
.wpmchimpas .wpmc-ficon .inputicon {
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
.wpmchimpas .wpmchimpa-field.wpmc-ficon input[type="text"],
.wpmchimpas .wpmchimpa-field.wpmc-ficon .inputlabel{
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
    echo '.wpmchimpas .wpmc-ficon [wpmcfield="'.$f['tag'].'"] ~ .inputicon {background: '.$this->getIcon($f['icon'],25,$col).' no-repeat center}';
}
?>
.wpmchimpas .wpmchimpa-field textarea,
.wpmchimpas .wpmchimpa-field select,
.wpmchimpas input[type="text"]{
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
.wpmchimpas .wpmchimpa-field.wpmchimpa-multidrop select{
  height: 100px;
}
.wpmchimpas .wpmchimpa-field.wpmchimpa-drop:before{
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
.wpmchimpas input[type="text"] ~ .inputlabel{
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
.wpmchimpas input[type="text"]:valid + .inputlabel{
display: none;
}
.wpmchimpas select.wpmcerror,
.wpmchimpas input[type="text"].wpmcerror{
  border-color: red;
}
.wpmchimpas .wpmchimpa-field.wpmchimpa-check,
.wpmchimpas .wpmchimpa-field.wpmchimpa-radio{
  text-align: left;
}
.wpmchimpas .wpmchimpa-check *,
.wpmchimpas .wpmchimpa-radio *{
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
.wpmchimpas .wpmchimpa-item input {
  display: none;
}
.wpmchimpas .wpmchimpa-item span {
  cursor: pointer;
  display: inline-block;
  position: relative;
  padding-left: 35px;
  line-height: 26px;
  margin-right: 10px;
}

.wpmchimpas .wpmchimpa-item span:before,
.wpmchimpas .wpmchimpa-item span:after {
  content: '';
  display: inline-block;
  width: 16px;
  height: 16px;
  left: 0;
  top: 5px;
  position: absolute;
}
.wpmchimpas .wpmchimpa-item span:before {
background-color: #fff;
transition: all 0.3s ease-in-out;
<?php
  if(isset($theme["check_borc"])){
      echo 'border: 1px solid'.$theme["check_borc"].';';
  }
?>
}
.wpmchimpas .wpmchimpa-item input:checked + span:before{
  <?php if(isset($theme["check_c"]))echo 'background: '.$theme["check_c"].';';?>
}
.wpmchimpas .wpmchimpa-item input[type='checkbox'] + span:hover:after, .wpmchimpas input[type='checkbox']:checked + span:after {
  content:'';
  background: no-repeat center;
  <?php if(isset($theme['check_shade']))$chs=$theme['check_shade'];else $chs='1';
  echo 'background-image: '.$this->chshade($chs).';';?>
  left: -1px;
}
.wpmchimpas .wpmchimpa-item input[type='radio'] + span:before {
border-radius: 50%;
width: 16px;
height: 16px;
top: 4px;
}
.wpmchimpas input[type='radio']:checked + span:after {
background: <?php echo ($chs == 1)?'#7C7C7C':'#fafafa';?>;
width: 12px;
height: 12px;
top: 6px;
left: 2px;
border-radius: 50%;
}
.wpmchimpas .wpmcinfierr{
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


.wpmchimpas .wpmchimpa-subs-button{
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
transition: all 0.5s ease;
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
.wpmchimpas .wpmchimpa-subs-button::before{
content: '<?php if(isset($theme['button'])) echo $theme['button'];else echo __( 'Subscribe', 'chimpmate-wpmca' );?>';
}
.wpmchimpas .wpmchimpa-subs-button:hover{
background-color: #50B059;
<?php if(isset($theme["button_fch"])){
echo 'color:'.$theme["button_fch"].';';
}    
if(isset($theme["button_bch"])){
echo 'background-color:'.$theme["button_bch"].';';
}?>
}
.wpmchimpas .wpmchimpa-subsc{position: relative;}
.wpmchimpas .wpmchimpa-subs-button.subsicon:before{
padding-left: 42px;
  <?php 
  if(isset($theme["button_w"])){
      echo 'padding-left:'.$theme["button_h"].'px;';
  }
  ?>
}
.wpmchimpas .wpmchimpa-subs-button.subsicon::after{
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
.wpmchimpas .wpmchimpa-signal {
display: none;
position: absolute;
top: 7px;
right: 5px;
-webkit-transform: scale(0.8);
-ms-transform: scale(0.8);
transform: scale(0.8);
}
.wpmchimpas-inner.signalshow .wpmchimpa-signal {
  display: inline-block;
}

.wpmchimpas.wpmchimpa-tag{
margin: 5px auto;
}
.wpmchimpas .wpmchimpa-tag,
.wpmchimpas .wpmchimpa-tag *{
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
.wpmchimpas .wpmchimpa-tag:before{

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
.wpmchimpas .wpmchimpa-feedback{
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
.wpmchimpas .wpmchimpa-feedback.wpmchimpa-done{
font-size: 15px;
 top: 0;  margin: 0;height: auto;
}
.wpmchimpas .wpmchimpa-feedback.wpmchimpa-done:before{
content:<?=$this->getIcon('ch1',15,'#fff');?>;
width: 40px;
height: 40px;
border-radius: 20px;
line-height: 46px;
display: block;
background-color: #01E169;
margin: 40px auto;
}
.wpmchimpas-trig{	
top:40%;
margin: 0 3px;
display: block;
<?php
if(isset($theme["slider_trigger_top"])){
echo 'top:'.$theme["slider_trigger_top"].'%;';
}
?>
}
.wpmchimpas-trigi{
background: #262E43;
width:50px;
height:50px;
<?php
if(isset($theme["slider_trigger_bg"])){
echo 'background:'.$theme["slider_trigger_bg"].';';
}?>
}

.wpmchimpas-trig .wpmchimpas-trigi:hover{
-webkit-box-shadow: inset 3px 2px 21px 5px rgba(0,0,0,0.2);
-moz-box-shadow: inset 3px 2px 21px 5px rgba(0,0,0,0.2);
box-shadow: inset 3px 2px 21px 5px rgba(0,0,0,0.2);
}
.wpmchimpas-trigi:before{	
<?php 
$ti='b05';
if(isset($theme["slider_trigger_i"])){
  if($theme["slider_trigger_i"] == 'inone')$ti='';
  else if($theme["slider_trigger_i"] != 'idef')$ti=$theme["slider_trigger_i"];
}
 ?> 
content:<?php echo $this->getIcon($ti,32,(isset($theme["slider_trigger_c"]))?$theme["slider_trigger_c"]:'#fff');?>;
height: 32px;
width: 32px;
display: block;
margin: 8px;
position: absolute;
}

.wpmchimpas .wpmchimpa-social{
display: inline-block;
margin: 12px auto 0;
height: 90px;
width: 100%;
background: rgba(75, 75, 75, 0.3);
box-shadow: 0px 1px 1px 1px rgba(116, 116, 116, 0.94);
}
.wpmchimpas .wpmchimpa-social::before{
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

.wpmchimpas .wpmchimpa-social .wpmchimpa-soc{
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
.wpmchimpas .wpmchimpa-social .wpmchimpa-soc::before{
content: '';
display: block;
width:40px;
height: 40px;
background: no-repeat center;
}

.wpmchimpas .wpmchimpa-social .wpmchimpa-soc.wpmchimpa-fb {
<?php if(!isset($settings["fb_api"])){
echo 'display:none;';
}?>
}
.wpmchimpas .wpmchimpa-social .wpmchimpa-soc.wpmchimpa-fb::before {
background-image:<?php echo $this->getIcon('fb1',15,'#fff');?>
}
.wpmchimpas .wpmchimpa-social .wpmchimpa-soc.wpmchimpa-fb:hover:before {
background-image:<?php echo $this->getIcon('fb1',15,'#2d609b');?>
}
.wpmchimpas .wpmchimpa-social .wpmchimpa-soc.wpmchimpa-gp {
<?php if(!isset($settings["gp_api"])){
echo 'display:none;';
}?>
}
.wpmchimpas .wpmchimpa-social .wpmchimpa-soc.wpmchimpa-gp:before {
background-image: <?php echo $this->getIcon('gp1',15,'#fff');?>
}
.wpmchimpas .wpmchimpa-social .wpmchimpa-soc.wpmchimpa-gp:hover:before {
background-image: <?php echo $this->getIcon('gp1',15,'#eb4026');?>
}
.wpmchimpas .wpmchimpa-social .wpmchimpa-soc.wpmchimpa-ms {
<?php if(!isset($settings["ms_api"])){
echo 'display:none;';
}?>
}
.wpmchimpas .wpmchimpa-social .wpmchimpa-soc.wpmchimpa-ms::before {
background-image: <?php echo $this->getIcon('ms1',15,'#fff');?>
}
.wpmchimpas .wpmchimpa-social .wpmchimpa-soc.wpmchimpa-ms:hover:before {
background-image: <?php echo $this->getIcon('ms1',15,'#00BCF2');?>
}


.wpmchimpas.wosoc .wpmchimpa-social{
display: none;
}
#wpmchimpas-trig .wpmchimpas-trigh{
<?php
  if(isset($theme["slider_trigger_hider"]))
    echo 'display:block;';
?>
}
#wpmchimpas-trig .wpmchimpas-trigh:before{
<?php
  if(isset($theme["slider_trigger_hc"])){
    echo 'border-right-color: '.$theme["slider_trigger_hc"].';';
    echo 'border-left-color: '.$theme["slider_trigger_hc"].';';
  }
?>
}
</style>


<div id="wpmchimpas" class="scrollhide chimpmatecss<?php if(isset($theme['slider_dissoc']))echo ' wosoc';?>">
  <div class="wpmchimpas-cont">
  <div class="wpmchimpas-scroller">
    <div class="wpmchimpas-resp">
    <div class="wpmchimpas-inner wpmchimpselector wpmchimpa-mainc">
<?php if(isset($theme['heading'])) echo '<h3>'.$theme['heading'].'</h3>';?>
<?php if(isset($theme['msg'])) echo '<div class="wpmchimpa_para">'.$theme['msg'].'</div>';?>
  <div class="wpmchimpa-cont">
	    <form action="" method="post" class="wpmchimpa-reset">
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
            echo $this->getSpin(isset($theme["spinner_t"])?$theme["spinner_t"]:'3','wpmchimpas',isset($theme["spinner_c"])?$theme["spinner_c"]:'#000');?></div>
              </div>


              <?php if(isset($theme['tag_en'])){
              if(isset($theme['tag'])) $tagtxt= $theme['tag'];
              else $tagtxt=__( 'Secure and Spam free...', 'chimpmate-wpmca' );
              echo '<div class="wpmchimpa-tag">'.$tagtxt.'</div>';
              }?>


            </div>

            <div class="wpmchimpa-social">
                <div class="wpmchimpa-soc wpmchimpa-fb"></div>
                <div class="wpmchimpa-soc wpmchimpa-gp"></div>
                <div class="wpmchimpa-soc wpmchimpa-ms"></div>
            </div>
		</form>
    	<div class="wpmchimpa-feedback" wpmcerr="gen"></div>
    </div>
	</div>	</div></div></div></div>
<div class="wpmchimpas-bg chimpmatecss"></div>
<div class="wpmchimpas-overlay chimpmatecss"></div>
<div id="wpmchimpas-trig" class="chimpmatecss" <?php if(isset($settings['slider_trigger_scroll'])) echo 'class="scrollhide"';?>>
  <div class="wpmchimpas-trigc">
    <div class="wpmchimpas-trigi"></div>
    <div class="wpmchimpas-trigh"></div>
  </div>
</div>