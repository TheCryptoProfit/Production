<?php 
$theme['msg'] = htmlspecialchars_decode($theme['msg']);
?>
<style type="text/css">
	
.wpmchimpas {
background-color: #333;
color: #fff;
<?php
    if(isset($theme["bg_c"])){
        echo 'background-color:'.$theme["bg_c"].';';
    }?>
}
.wpmchimpas-inner {
text-align: center;
}
.wpmchimpas h3{
  font-size: 18px;
  line-height: 18px;
  margin: 40px 0 20px;
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
  margin-bottom: 10px;
}
.wpmchimpas .wpmchimpa_para,.wpmchimpas .wpmchimpa_para * {
  font-size: 14px;
  line-height: 20px;
  text-align: center;
    <?php 
        if(isset($theme["msg_f"]['f'])){
    array_push($wpmc_font, $theme["msg_f"]['f']);
          echo 'font-family:'.$theme["msg_f"]['f'].';';
        }
        if(isset($theme["msg_f"]['s'])){
            echo 'font-size:'.$theme["msg_f"]['s'].'px;';
        }
    ?>
}

.wpmchimpas  .wpmchimpa-field{
position: relative;
width:80%;
margin: 0 auto 12px auto;
<?php 
  if(isset($theme["tbox_w"])){
      echo 'width:'.$theme["tbox_w"].'px;';
  }
?>
}
.wpmchimpas .inputicon{
display: none;
}
.wpmchimpas .wpmc-ficon .inputicon {
display: block;
width: 45px;
height: 45px;
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
.wpmchimpas .wpmc-ficon input[type="text"],
.wpmchimpas .wpmc-ficon .inputlabel{
  padding-left: 45px;
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
    display: inline-block;
    width:100%;
    background:#fff;
    height:45px;
    text-align: center;
    border:2px solid #fff;
    outline:0;
    border-radius: 1px;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
    font-size: 16px;
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
width: 45px;
height: 45px;
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
line-height: 45px;
color: rgba(0,0,0,0.6);
font-size: 16px;
font-weight:500;
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
.wpmchimpas .wpmchimpa-check *,
.wpmchimpas .wpmchimpa-radio *{
font-size: 16px;
color: #fff;
text-align: left;
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
  margin-right: 10px;
  line-height: 26px;
}

.wpmchimpas .wpmchimpa-item span:before,
.wpmchimpas .wpmchimpa-item span:after {
  content: '';
  display: inline-block;
  width: 18px;
  height: 18px;
  left: 0;
  top: 4px;
  position: absolute;
}

.wpmchimpas .wpmchimpa-item span:before {
background-color: #fafafa;
transition: all 0.3s ease-in-out;
border-radius: 3px;
<?php
  if(isset($theme["check_borc"])){
      echo 'border: 1px solid'.$theme["check_borc"].';';
  }
?>
}
.wpmchimpas .wpmchimpa-item input[type='checkbox'] + span:before {
border-radius: 3px;
}
.wpmchimpas .wpmchimpa-item input[type='radio'] + span:before {
border-radius: 50%;
width: 12px;
height: 12px;
top: 7px;
}
.wpmchimpas .wpmchimpa-item input:checked + span:before{
  <?php if(isset($theme["check_c"])) $color = $theme["check_c"]; else $color = '#158EC6';
  print_r('box-shadow: inset 0 0 0 10px '.$color.';');?>
}

.wpmchimpas .wpmchimpa-item input[type='checkbox'] + span:hover:after, .wpmchimpas input[type='checkbox']:checked + span:after {
  content:'';
  background: no-repeat center;
  <?php if(isset($theme['check_shade']))$chs=$theme['check_shade'];else $chs='2';
  echo 'background-image: '.$this->chshade($chs).';';?>
  left: -1px;
  bottom: -1px;
}
.wpmchimpas .wpmchimpa-item input[type='checkbox']:not(:checked) + span:hover:after {
<?php echo 'background-image: '.$this->chshade('2').';';?>
 opacity: 0.5;
}
.wpmchimpas .wpmcinfierr{
  display: block;
  height: 12px;
  line-height: 12px;
  margin-bottom: -12px;
  font-size: 11px;
  color: red;
  text-align: left;
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
.wpmchimpas input[type="text"]:focus {
    border:2px solid #ddd;
    <?php 
        if(isset($theme["tbox_bor"]) && isset($theme["tbox_borc"])){
            echo ' border:'.$theme["tbox_bor"].'px solid '.$theme["tbox_borc"].';';
        } ?>
}
.wpmchimpas .wpmchimpa-subs-button{
  display:inline-block;
  vertical-align: initial;
  text-align: center;
  width: 70%;
    height:45px;
    background: #62bc33;
  color:#fff;
    cursor:pointer;
    position: relative;
    -webkit-box-shadow:none;
    -moz-box-shadow:none;
    -ms-box-shadow:none;
    -o-box-shadow:none;
    box-shadow:none;
    clear:both;
    text-shadow:none;
    border: 0;
    border-radius: 1px;
    -webkit-border-radius: 1px;
    -moz-border-radius: 1px;
    -ms-border-radius: 1px;
    -o-border-radius: 1px;
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
        }
        if(isset($theme["button_bc"])){
            echo 'background:'.$theme["button_bc"].';';
        }
        else{ ?>
          background: -moz-linear-gradient(left, #62bc33 0%, #8bd331 100%);
          background: -webkit-gradient(linear, left top, right top, color-stop(0%,#62bc33), color-stop(100%,#8bd331));
          background: -webkit-linear-gradient(left, #62bc33 0%,#8bd331 100%);
          background: -o-linear-gradient(left, #62bc33 0%,#8bd331 100%);
          background: -ms-linear-gradient(left, #62bc33 0%,#8bd331 100%);
          background: linear-gradient(to right, #62bc33 0%,#8bd331 100%);
        <?php }
        if(isset($theme["button_br"])){
            echo '-webkit-border-radius:'.$theme["button_br"].'px;';
            echo '-moz-border-radius:'.$theme["button_br"].'px;';
            echo '-ms-border-radius:'.$theme["button_br"].'px;';
            echo '-o-border-radius:'.$theme["button_br"].'px;';
            echo 'border-radius:'.$theme["button_br"].'px;';
        }
        if(isset($theme["button_bor"]) && isset($theme["button_borc"])){
            echo ' border:'.$theme["button_bor"].'px solid '.$theme["button_borc"].';';
        }
      ?>
}
.wpmchimpas .wpmchimpa-subs-button::before{
   content: '<?php if(isset($theme['button'])) echo $theme['button'];else echo __( 'Subscribe', 'chimpmate-wpmca' );?>';
  display: block;
  position: relative;
  line-height: 45px;
  <?php if(isset($theme["button_h"])){
      echo 'line-height:'.$theme["button_h"].'px;';
  } ?>
}
.wpmchimpas .wpmchimpa-subs-button:hover{

    background:#8BD331;
   
  color:#fff;
	<?php 
    if(isset($theme["button_bch"])){
        echo 'background:'.$theme["button_bch"].';';
    }
    else{ ?> 
      background: -moz-linear-gradient(left, #8BD331 0%, #8bd331 100%);
    background: -webkit-gradient(linear, left top, right top, color-stop(0%,#8BD331), color-stop(100%,#8bd331));
    background: -webkit-linear-gradient(left, #8BD331 0%,#8bd331 100%);
    background: -o-linear-gradient(left, #8BD331 0%,#8bd331 100%);
    background: -ms-linear-gradient(left, #8BD331 0%,#8bd331 100%);
    background: linear-gradient(to right, #8BD331 0%,#8bd331 100%);
      <?php }
    if(isset($theme["button_fch"])){
        echo 'color:'.$theme["button_fch"].';';
    }
    if(isset($theme["button_bor"]) && isset($theme["button_borc"])){
        echo ' border:'.$theme["button_bor"].'px solid '.$theme["button_borc"].';';
    }
  ?>
}
.wpmchimpas .wpmchimpa-subs-button.subsicon:before{
padding-left: 45px;
  <?php 
  if(isset($theme["button_w"])){
      echo 'padding-left:'.$theme["button_h"].'px;';
  }
  ?>
}
.wpmchimpas .wpmchimpa-subs-button.subsicon::after{
content:'';
position: absolute;
height: 45px;
width: 45px;
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
.wpmchimpas .wpmchimpa-signalc {
height: 40px;
  margin: 15px;
  text-align: center;
}
.wpmchimpas .wpmchimpa-signal {
display: none;
}
.wpmchimpas-inner.signalshow .wpmchimpa-signal {
  display: inline-block;
}

.wpmchimpas .wpmchimpa-feedback{
margin: -40px 0 22px;
  clear:both;
height: 12px;
position: relative;
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
margin: 40px;
height: auto;
}
.wpmchimpas .wpmchimpa-tag{
margin-top: 5px;
}
.wpmchimpas .wpmchimpa-tag,
.wpmchimpas .wpmchimpa-tag *{
color:#fff;
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
.wpmchimpas .wpmchimpa-tag::before{

   content:<?php
        $tfs=10;
        if(isset($theme["tag_f"]['s'])){$tfs=$theme["tag_f"]['s'];}
        $tfc='#fff';
        if(isset($theme["tag_fc"])){$tfc=$theme["tag_fc"];}
        echo $this->getIcon('lock1',$tfs,$tfc);?>;
   margin: 5px;
   top: 1px;
   position:relative;
}
.wpmchimpas-trig{	
top:40%;display: block;
<?php
    if(isset($theme["slider_trigger_top"])){
        echo 'top:'.$theme["slider_trigger_top"].'%;';
    }
  ?>
}
.wpmchimpas-trigi{ 
background: #000;
width:50px;
height:50px;
<?php
  if(isset($theme["slider_trigger_bg"])){
      echo 'background:'.$theme["slider_trigger_bg"].';';
  }
?>
}
.wpmchimpas-trigi:hover{
opacity:0.7;
}

.wpmchimpas-trigi:before{
<?php 
$ti='a01';
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
<div id="wpmchimpas" class="scrollhide chimpmatecss">
  <div class="wpmchimpas-cont">
  <div class="wpmchimpas-scroller">
    <div class="wpmchimpas-resp">
    <div class="wpmchimpas-inner wpmchimpselector">
     
<?php if(isset($theme['heading'])) echo '<h3>'.$theme['heading'].'</h3>';?>
<?php if(isset($theme['msg'])) echo '<div class="wpmchimpa_para">'.$theme['msg'].'</div>';?>
	<form action="" method="post" class="wpmchimpa-reset wpmchimpa-mainc">
<input type="hidden" name="action" value="wpmchimpa_add_email_ajax"/>
<input type="hidden" name="wpmcform" value="<?php echo $form['id'];?>"/>
<?php $set = array(
  'icon' => false,
  'type' => 1
  );
$this->stfield($form['fields'],$set); 
?>
		<div class="wpmchimpa-subs-button<?php echo (isset($theme['button_i']) && $theme['button_i'] != 'inone' && $theme['button_i'] != 'idef')? ' subsicon' : '';?>"></div>
    <?php if(isset($theme['tag_en'])){
        if(isset($theme['tag'])) $tagtxt= $theme['tag'];
        else $tagtxt=__( 'Secure and Spam free...', 'chimpmate-wpmca' );
        echo '<div class="wpmchimpa-tag">'.$tagtxt.'</div>';
        }?>
    <div class="wpmchimpa-signalc"><div class="wpmchimpa-signal"><?php 
            echo $this->getSpin(isset($theme["spinner_t"])?$theme["spinner_t"]:'1','wpmchimpas',isset($theme["spinner_c"])?$theme["spinner_c"]:'#fff');?></div></div>
	</form>
	<div class="wpmchimpa-feedback" wpmcerr="gen"></div>

    </div>
  </div>
</div>
</div>
</div><div class="wpmchimpas-bg chimpmatecss"></div>
<div class="wpmchimpas-overlay chimpmatecss"></div>
<div id="wpmchimpas-trig" class="chimpmatecss" <?php if(isset($settings['slider_trigger_scroll'])) echo 'class="scrollhide"';?>>
  <div class="wpmchimpas-trigc">
    <div class="wpmchimpas-trigi"></div>
    <div class="wpmchimpas-trigh"></div>
  </div>
</div>