<?php 
$theme['msg'] = htmlspecialchars_decode($theme['msg']);
?>
<style type="text/css">
.wpmchimpaf{
	position:fixed;z-index: 99999;
	display: inline-block;
	width: 320px;
	background: #000;
padding-bottom: 35px
-webkit-transition: -webkit-transform 0.3s cubic-bezier(0.785, 0.135, 0.15, 0.86);
transition: transform 0.3s cubic-bezier(0.785, 0.135, 0.15, 0.86);
  <?php 
        if(isset($theme["bg_c"])){
            echo 'background:'.$theme["bg_c"].';';
        }
    ?>
}
#wpmchimpaf.wpmctb_mid .wpmchimpaf{left: calc(50% - 160px);bottom: 0}
#wpmchimpaf.wpmctb_mid .wpmchimpaf.wpmchimpaf-close{
-webkit-transform: translateY(1000px);transform: translateY(1000px);
}
#wpmchimpaf.wpmctb_left .wpmchimpaf{left: 10px;bottom: 10px}
#wpmchimpaf.wpmctb_left .wpmchimpaf.wpmchimpaf-close{
-webkit-transform: translateX(-500px);transform: translateX(-500px);
}
#wpmchimpaf.wpmctb_right .wpmchimpaf{right: 10px;bottom: 10px}
#wpmchimpaf.wpmctb_right .wpmchimpaf.wpmchimpaf-close{
-webkit-transform: translateX(500px);transform: translateX(500px);
}
.wpmchimpaf .wpmchimpaf-head{
  width: 100%;
  height: 35px;
}
.wpmchimpaf h3{
display: block;
width: 200px;
font-size:15px;
line-height: 35px;
font-weight:400;
color:#fff;
padding-left: 10px;
float: left;
width: 100%;
text-align: center;
<?php 
    if(isset($theme["heading_f"]['f'])){
    array_push($wpmc_font, $theme["heading_f"]['f']);
      echo 'font-family:'.$theme["heading_f"]['f'].';';
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
.wpmchimpaf .wpmchimpa_para{
  margin-bottom: 10px;
}
.wpmchimpaf .wpmchimpa_para,.wpmchimpaf .wpmchimpa_para * {
  color: #fff;
<?php if(isset($theme["msg_f"]['f'])){
    array_push($wpmc_font, $theme["msg_f"]['f']);
echo 'font-family:'.str_replace("|ng","",$theme["msg_f"]['f']).';';
}?>
}
.wpmchimpaf .wpmchimpaf-cont{
  padding:10px;
  text-align: center;
}

.wpmchimpaf .wpmchimpa-field{
position: relative;
width:100%;
margin: 0 auto 10px auto;
}
.wpmchimpaf .inputicon{
display: none;
}
.wpmchimpaf .wpmc-ficon .inputicon {
display: block;
width: 30px;
height: 30px;
position: absolute;
top: 0;
left: 0;
pointer-events: none;
}
.wpmchimpaf .wpmc-ficon input[type="text"],
.wpmchimpaf .wpmc-ficon .inputlabel{
  padding-left: 30px;
}
<?php
$col = ((isset($theme["inico_c"]))? $theme["inico_c"] : '#888');
foreach ($form['fields'] as $f) {
  if($f['icon'] != 'idef' && $f['icon'] != 'inone')
    echo '.wpmchimpaf .wpmc-ficon [wpmcfield="'.$f['tag'].'"] ~ .inputicon {background: '.$this->getIcon($f['icon'],25,$col).' no-repeat center}';
}
?>
.wpmchimpaf .wpmchimpa-field textarea,
.wpmchimpaf .wpmchimpa-field select,
.wpmchimpaf input[type="text"]{
    display: inline-block;
    width:100%;
    background:#fff;
    height:30px;
    text-align: center;
    border:2px solid #fff;
    outline:0;
    border-radius: 1px;
    -webkit-border-radius: 1px;
    -moz-border-radius: 1px;
    -ms-border-radius: 1px;
    -o-border-radius: 1px;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
    font-size: 16px;
    <?php 
        if(isset($theme["tbox_f"]['f'])){
    array_push($wpmc_font, $theme["tbox_f"]['f']);
          echo 'font-family:'.$theme["tbox_f"]['f'].';';
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
        if(isset($theme["tbox_bor"]) && isset($theme["tbox_borc"])){
            echo ' border:'.$theme["tbox_bor"].'px solid '.$theme["tbox_borc"].';';
        }
    ?>
}

.wpmchimpaf .wpmchimpa-field.wpmchimpa-multidrop select{
  height: 100px;
}

.wpmchimpaf .wpmchimpa-field.wpmchimpa-drop:before{
content: '';
width: 30px;
height: 30px;
position: absolute;
right: 0;
top: 0;
pointer-events: none;
background: no-repeat center;
background-image: <?=$this->getIcon('dd',16,'#000');?>;
}
.wpmchimpaf input[type="text"] ~ .inputlabel{
position: absolute;
top: 0;
left: 0;
right: 0;
pointer-events: none;
width: 100%;
line-height: 30px;
color: rgba(0,0,0,0.6);
font-size: 16px;
font-weight:500;
white-space: nowrap;
text-align: center;
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
.wpmchimpaf input[type="text"]:valid + .inputlabel{
display: none;
}
.wpmchimpaf select.wpmcerror,
.wpmchimpaf input[type="text"].wpmcerror{
  border-color: red;
}.wpmchimpaf .wpmchimpa-check *,
.wpmchimpaf .wpmchimpa-radio *{
font-size: 14px;
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
.wpmchimpaf .wpmchimpa-item input {
  display: none;
}
.wpmchimpaf .wpmchimpa-item span {
  cursor: pointer;
  display: inline-block;
  position: relative;
  padding-left: 35px;
  line-height: 26px;
  margin-right: 10px;
}

.wpmchimpaf .wpmchimpa-item span:before,
.wpmchimpaf .wpmchimpa-item span:after {
  content: '';
  display: inline-block;
  width: 15px;
  height: 15px;
  left: 0;
  top: 3px;
  position: absolute;
}

.wpmchimpaf .wpmchimpa-item span:before {
background-color: #fafafa;
transition: all 0.3s ease-in-out;
border-radius: 3px;
<?php
  if(isset($theme["check_borc"])){
      echo 'border: 1px solid'.$theme["check_borc"].';';
  }
?>
}
.wpmchimpaf .wpmchimpa-item input[type='checkbox'] + span:before {
border-radius: 3px;
}
.wpmchimpaf .wpmchimpa-item input[type='radio'] + span:before {
border-radius: 50%;
width: 12px;
height: 12px;
top: 4px;
}
.wpmchimpaf .wpmchimpa-item input:checked + span:before{
  <?php if(isset($theme["check_c"])) $color = $theme["check_c"]; else $color = '#158EC6';
  print_r('box-shadow: inset 0 0 0 10px '.$color.';');?>
}

.wpmchimpaf .wpmchimpa-item input[type='checkbox'] + span:hover:after, .wpmchimpaf input[type='checkbox']:checked + span:after {
  content:'';
  background: no-repeat center;
  <?php if(isset($theme['check_shade']))$chs=$theme['check_shade'];else $chs='2';
  echo 'background-image: '.$this->chshade($chs).';';?>
  left: -1px;
  bottom: -1px;
}
.wpmchimpaf .wpmchimpa-item input[type='checkbox']:not(:checked) + span:hover:after {
<?php echo 'background-image: '.$this->chshade('2').';';?>
 opacity: 0.5;
}

.wpmchimpaf .wpmcinfierr{
  text-align: left;
  display: block;
  height: 10px;
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
.wpmchimpaf input[type="email"]:focus,.wpmchimpaf input[type="text"]:focus {
    border:2px solid #ddd;
    <?php 
        if(isset($theme["tbox_bor"]) && isset($theme["tbox_borc"])){
            echo ' border:'.$theme["tbox_bor"].'px solid '.$theme["tbox_borc"].';';
        } ?>
}
.wpmchimpaf .wpmchimpa-subs-button{
  display:inline-block;
  text-align: center;
  width: 100%;
    height:28px;
    line-height: 28px;
    margin-bottom:10px;
    background: #62bc33;
  color:#fff;
    cursor:pointer;
    -webkit-box-shadow:none;
    -moz-box-shadow:none;
    -ms-box-shadow:none;
    -o-box-shadow:none;
    box-shadow:none;
    clear:both;
    text-shadow:none;
    border: 0;
    -webkit-border-radius: 1px;
    -moz-border-radius: 1px;
    -ms-border-radius: 1px;
    -o-border-radius: 1px;
    border-radius: 1px;
    position: relative;
  <?php
        if(isset($theme["button_f"]['f'])){
    array_push($wpmc_font, $theme["button_f"]['f']);
          echo 'font-family:'.$theme["button_f"]['f'].';';
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
.wpmchimpaf .wpmchimpa-subs-button::before{
  content: '<?php if(isset($theme['button'])) echo $theme['button'];else echo __( 'Subscribe', 'chimpmate-wpmca' );?>';
   display: block;
  position: relative;
  line-height: 28px;
}
.wpmchimpaf .wpmchimpa-subs-button:hover{
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
.wpmchimpaf .wpmchimpa-subs-button.subsicon:before{
padding-left: 28px;
}
.wpmchimpaf .wpmchimpa-subs-button.subsicon::after{
content:'';
position: absolute;
height: 28px;
width: 28px;
top: 0;
left: 0;
pointer-events: none;
  <?php 
  if($theme["button_i"] != 'inone' && $theme["button_i"] != 'idef'){
    $col = ((isset($theme["button_fc"]))? $theme["button_fc"] : '#fff');
     echo 'background: '.$this->getIcon($theme["button_i"],25,$col).' no-repeat center;';
  }
  ?>
}
.wpmchimpaf .wpmchimpa-feedback{
margin: -40px 0 22px;
  clear:both;
height:14px;
position: relative;
color: #fff;
  <?php
        if(isset($theme["status_f"]['f'])){
    array_push($wpmc_font, $theme["status_f"]['f']);
          echo 'font-family:'.$theme["status_f"]['f'].';';
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

.wpmchimpaf .wpmchimpa-feedback.wpmchimpa-done{
  margin: 0;
  height: auto;
}

.wpmchimpaf .wpmchimpaf-close-button {
display: inline-block;
width: 1.5em;
height: 1.5em;
right: 10px;
top:5px;
position: absolute;
border: 0.1em solid #7e7e7e;
-webkit-border-radius: 50%;
-moz-border-radius: 50%;
-msborder-radius: 50%;
-o-border-radius: 50%;
border-radius: 50%;
cursor:pointer;
background-color: #7e7e7e;
-moz-transform: rotate(45deg); 
-o-transform: rotate(45deg);
-ms-transform: rotate(45deg);
-webkit-transform: rotate(45deg);
transform: rotate(45deg);
transition: all 0.5s ease;
}


.wpmchimpaf .wpmchimpaf-close-button::before {
    content: "";
    display: block;
    position: absolute;
    background-color: #000;
    width: 80%;
    height: 6%;
    left: 10%;
    top: 47%;
  }

  .wpmchimpaf .wpmchimpaf-close-button::after {
    content: "";
    display: block;
    position: absolute;
    background-color: #000;
    width: 6%;
    height: 80%;
    left: 47%;
    top: 10%;
  }
  .wpmchimpaf .wpmchimpaf-close-button:hover {
    background-color: #fff; 
    -ms-transform: rotate(225deg);
    -webkit-transform: rotate(225deg);
    -moz-transform: rotate(225deg); 
    -o-transform: rotate(225deg); 
    transform: rotate(225deg); 
  } 

.wpmchimpaf .wpmchimpaf-close-button:hover::after {
      background-color: #7e7e7e;
    }
.wpmchimpaf .wpmchimpaf-close-button:hover::before {
      background-color: #7e7e7e;
    }

.wpmchimpaf .wpmchimpa-signalc {
height: 40px;
  margin-top: 10px;
  text-align: center;
}
.wpmchimpaf .wpmchimpa-signal {
display: none;
}
#wpmchimpaf.signalshow .wpmchimpa-signal {
  display: inline-block;
}
@media only screen and (max-width : 1024px) {
.wpmchimpaf{
	display: none;
}
}
</style>
<div class="wpmchimpaf-tray chimpmatecss wpmchimpselector wpmctb_right" id="wpmchimpaf">
<div class="wpmchimpa-reset wpmchimpaf wpmchimpaf-close">
  <div class="wpmchimpaf-head">
<?php if(isset($theme['heading'])) echo '<h3>'.$theme['heading'].'</h3>';?>
    <div class="wpmchimpaf-close-button"></div>
  </div>
  <div class="wpmchimpaf-cont">
<?php if(isset($theme['msg'])) echo '<div class="wpmchimpa_para">'.$theme['msg'].'</div>';?>
  <form action="" method="post">
<input type="hidden" name="action" value="wpmchimpa_add_email_ajax"/>
<input type="hidden" name="wpmcform" value="<?php echo $form['id'];?>"/>
<?php $set = array(
  'icon' => false,
  'type' => 1
  );
$this->stfield($form['fields'],$set); 
?>
                        <div class="wpmchimpa-subs-button<?php echo (isset($theme['button_i']) && $theme['button_i'] != 'inone' && $theme['button_i'] != 'idef')? ' subsicon' : '';?>"></div>
   <div class="wpmchimpa-signalc"><div class="wpmchimpa-signal"><?php
            echo $this->getSpin(isset($theme["spinner_t"])?$theme["spinner_t"]:'1','wpmchimpaf',isset($theme["spinner_c"])?$theme["spinner_c"]:'#fff');?></div></div>
		<input type="hidden" name="action" value="wpmchimpa_add_email_ajax"/>
	</form>
	<div class="wpmchimpa-feedback" wpmcerr="gen"></div>
  </div>
</div>
</div>