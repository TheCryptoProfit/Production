<?php 
$theme['msg'] = htmlspecialchars_decode($theme['msg']);
$this->social=true;
?>
<style type="text/css">
.wpmchimpaf{
  position:fixed;z-index: 99999;
  display: inline-block;
  width: 320px;
background: #fff;
box-shadow: 0 0 20px rgba(0,0,0,.2);
overflow: hidden;
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
}
.wpmchimpaf h3{
  display: block;
  width: 95%;
    font-size:16px;
    line-height: 35px;
    font-weight:400;
    margin: 10px;
    text-align: center;
    float: left;
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
  font-size: 14px;
<?php if(isset($theme["msg_f"]['f'])){
    array_push($wpmc_font, $theme["msg_f"]['f']);
echo 'font-family:'.$theme["msg_f"]['f'].';';
}?>
}

.wpmchimpaf .wpmchimpaf-cont{
  padding:10px;
  text-align: center;
  max-height: 500px;
}

.wpmchimpaf  .wpmchimpa-field{
position: relative;
width:100%;
margin: 0 auto 10px auto;
text-align: left;
}
.wpmchimpaf .inputicon{
display: none;
}
.wpmchimpaf .wpmc-ficon .inputicon {
display: block;
width: 35px;
height: 35px;
position: absolute;
top: 0;
left: 0;
pointer-events: none;
}
.wpmchimpaf .wpmc-ficon input[type="text"],
.wpmchimpaf .wpmc-ficon input[type="text"] ~ .inputlabel{
  padding-left: 35px;
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
border-radius: 5px;
width: 100%;
background: #f8fafa;
padding: 0 5%;
border: 1px solid #e4e9e9;
height:35px;
color: #353535;
outline:0;
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
width: 35px;
height: 35px;
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
line-height: 35px;
color: rgba(0,0,0,0.6);
font-size: 16px;
padding: 0 5%;
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
.wpmchimpaf input[type="text"]:valid + .inputlabel{
display: none;
}
.wpmchimpaf select.wpmcerror,
.wpmchimpaf input[type="text"].wpmcerror{
  border-color: red;
}
.wpmchimpaf .wpmchimpa-check *,
.wpmchimpaf .wpmchimpa-radio *{
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
  line-height: 29px;
  margin-right: 10px;
}

.wpmchimpaf .wpmchimpa-item span:before,
.wpmchimpaf .wpmchimpa-item span:after {
  content: '';
  display: inline-block;
  width: 18px;
  height: 18px;
  left: 0;
  top: 5px;
  position: absolute;
}
.wpmchimpaf .wpmchimpa-item span:before {
box-shadow: 0 0 1px 1px #ccc;
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
.wpmchimpaf .wpmchimpa-item input:checked + span:before{
  <?php if(isset($theme["check_c"]))echo 'background: '.$theme["check_c"].';';?>
}
.wpmchimpaf .wpmchimpa-item input[type='checkbox'] + span:hover:after, .wpmchimpaf input[type='checkbox']:checked + span:after {
  content:'';
  background: no-repeat center;
  <?php if(isset($theme['check_shade']))$chs=$theme['check_shade'];else $chs='1';
  echo 'background-image: '.$this->chshade($chs).';';?>
  left: -1px;
  bottom: -1px;
}
.wpmchimpaf .wpmchimpa-item input[type='radio'] + span:before {
border-radius: 50%;
width: 16px;
height: 16px;
top: 5px;
}
.wpmchimpaf input[type='radio']:checked + span:after {
background: <?php echo ($chs == 1)?'#7C7C7C':'#fafafa';?>;
width: 12px;
height: 12px;
top: 7px;
left: 2px;
border-radius: 50%;
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
.wpmchimpaf input[type="text"]:focus {
    <?php 
        if(isset($theme["tbox_bor"]) && isset($theme["tbox_borc"])){
            echo ' border:'.$theme["tbox_bor"].'px solid '.$theme["tbox_borc"].';';
        } ?>
}
.wpmchimpaf .wpmchimpa-subs-button{
   -moz-border-radius: 3px;
    -webkit-border-radius: 3px;
    -ms-border-radius: 3px;
    -o-border-radius: 3px;
    border-radius: 3px;
  display:inline-block;
  text-align: center;
  width: 100%;
    height:36px;
    line-height: 36px;
    border: 1px solid #3079ed;
   background-color: #4d90fe;
  color:#fff;
    cursor:pointer;
    text-shadow:none;
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
           background-image: -webkit-linear-gradient(top,#4d90fe,#4787ed);
background-image: -moz-linear-gradient(top,#4d90fe,#4787ed);
background-image: -mz-linear-gradient(top,#4d90fe,#4787ed);
background-image: -o-linear-gradient(top,#4d90fe,#4787ed);
background-image: -webkit-linear-gradient(top,#4d90fe,#4787ed);
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
}
.wpmchimpaf .wpmchimpa-subs-button:hover{
    background:#8BD331;   
  color:#fff;
    <?php 
        if(isset($theme["button_bch"])){
            echo 'background:'.$theme["button_bch"].';';
        }
        else{ ?> 
          background-image: -webkit-linear-gradient(top,#4d90fe,#4787ed);
background-image: -moz-linear-gradient(top,#4d90fe,#4787ed);
background-image: -mz-linear-gradient(top,#4d90fe,#4787ed);
background-image: -o-linear-gradient(top,#4d90fe,#4787ed);
background-image: -webkit-linear-gradient(top,#4d90fe,#4787ed);
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
padding-left: 36px;
}
.wpmchimpaf .wpmchimpa-subs-button.subsicon::after{
content:'';
position: absolute;
height: 36px;
width: 36px;
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
position: relative;
height:14px;
width: 100%;
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
position: relative;
margin-top: 25px;
font-size: 16px;
}

.wpmchimpaf .wpmchimpaf-close-button {
  display: inline-block;
  width: 17px;
  height: 30px;
  right: 20px;
  top: 10px;
  position: absolute;
  cursor: pointer;
  -webkit-backface-visibility: hidden;
  backface-visibility: hidden;
}

.wpmchimpaf .wpmchimpaf-close-button::before {
  content: "\00D7";
  font-size: 30px;
  font-weight: 100;
  color: #959595;
}
.wpmchimpaf .wpmchimpaf-close-button:hover:before {
    color: #000;
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
            echo $this->getSpin(isset($theme["spinner_t"])?$theme["spinner_t"]:'7','wpmchimpaf',isset($theme["spinner_c"])?$theme["spinner_c"]:'#000');?></div></div>
    <input type="hidden" name="action" value="wpmchimpa_add_email_ajax"/>
  </form>
  <div class="wpmchimpa-feedback" wpmcerr="gen"></div>
  </div>
  </div>
</div>