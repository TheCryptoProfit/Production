<?php
$theme['msg'] = htmlspecialchars_decode($theme['msg']);
echo '<div class="widget-text wp_widget_plugin_box">';
if(isset($theme['heading']))
	echo $before_title . $theme['heading'] . $after_title;
 ?>
 <style type="text/css">

#<?php echo $wpmcw_id; ?> {
  width: 100%;
  display: block;
  padding-bottom: 10px;
  <?php 
  if(isset($theme["bg_c"])){
    echo 'background-color:'.$theme['bg_c'].';';
  }?>
}
#<?php echo $wpmcw_id; ?> .wpmchimpa_para {
margin-bottom: 15px;
line-height: 20px;
}
#<?php echo $wpmcw_id; ?> .wpmchimpa_para ,#<?php echo $wpmcw_id; ?> .wpmchimpa_para *{
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

#<?php echo $wpmcw_id; ?> form{overflow: hidden}

#<?php echo $wpmcw_id; ?>  .wpmchimpa-field{
position: relative;
width:100%;
margin: 0 auto 10px auto;
<?php 
  if(isset($theme["tbox_w"])){
      echo 'width:'.$theme["tbox_w"].'px;';
  }
?>
}
#<?php echo $wpmcw_id; ?> .inputicon{
display: none;
}
#<?php echo $wpmcw_id; ?> .wpmc-ficon .inputicon {
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
#<?php echo $wpmcw_id; ?> .wpmc-ficon input[type="text"],
#<?php echo $wpmcw_id; ?> form .wpmc-ficon span.inputlabel{
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
    echo '#'.$wpmcw_id.' .wpmc-ficon [wpmcfield="'.$f['tag'].'"] ~ .inputicon {background: '.$plugin->getIcon($f['icon'],25,$col).' no-repeat center}';
}
?>
#<?php echo $wpmcw_id; ?> .wpmchimpa-field textarea,
#<?php echo $wpmcw_id; ?> .wpmchimpa-field select,
#<?php echo $wpmcw_id; ?> input[type="text"]{
  outline:0;
  border-radius: 1px;
    -webkit-border-radius: 1px;
    -moz-border-radius: 1px;
    -ms-border-radius: 1px;
    -o-border-radius: 1px;
  width: 100%;
   padding: 5px;
  -webkit-box-sizing: border-box;
  -moz-box-sizing: border-box;
  box-sizing: border-box;
  border:0;
  height: 40px;
  width: 100%;
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

#<?php echo $wpmcw_id; ?> .wpmchimpa-field.wpmchimpa-multidrop select{
  height: 100px;
}

#<?php echo $wpmcw_id; ?> .wpmchimpa-field.wpmchimpa-drop:before{
content: '';
width: 40px;
height: 40px;
position: absolute;
right: 0;
top: 0;
pointer-events: none;
background: no-repeat center;
background-image: <?=$plugin->getIcon('dd',16,'#000');?>;
<?php 
if(isset($theme["tbox_h"])){
  echo 'width:'.$theme["tbox_h"].'px;';
  echo 'height:'.$theme["tbox_h"].'px;';
}
?>
}
#<?php echo $wpmcw_id; ?> input[type="text"] ~ .inputlabel{
position: absolute;
top: 0;
left: 0;
right: 0;
pointer-events: none;
width: 100%;
line-height: 40px;
color: rgba(0,0,0,0.6);
font-weight:500;
font-size: 16px;
white-space: nowrap;
padding: 0 5px;
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
#<?php echo $wpmcw_id; ?> input[type="text"]:valid + .inputlabel{
display: none;
}
#<?php echo $wpmcw_id; ?> select.wpmcerror,
#<?php echo $wpmcw_id; ?> input[type="text"].wpmcerror{
  border-color: red;
}
#<?php echo $wpmcw_id; ?> .wpmchimpa-check *,
#<?php echo $wpmcw_id; ?> .wpmchimpa-radio *{
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
#<?php echo $wpmcw_id; ?> .wpmchimpa-item input {
  display: none;
}
#<?php echo $wpmcw_id; ?> .wpmchimpa-item span {
  cursor: pointer;
  display: inline-block;
  position: relative;
  padding-left: 35px;
  margin-right: 10px;
  line-height: 26px;
}

#<?php echo $wpmcw_id; ?> .wpmchimpa-item span:before,
#<?php echo $wpmcw_id; ?> .wpmchimpa-item span:after {
  content: '';
  display: inline-block;
  width: 18px;
  height: 18px;
  left: 0;
  top: 4px;
  position: absolute;
}

#<?php echo $wpmcw_id; ?> .wpmchimpa-item span:before {
background-color: #fafafa;
transition: all 0.3s ease-in-out;
border-radius: 3px;
<?php
  if(isset($theme["check_borc"])){
      echo 'border: 1px solid'.$theme["check_borc"].';';
  }
?>
}
#<?php echo $wpmcw_id; ?> .wpmchimpa-item input[type='checkbox'] + span:before {
border-radius: 3px;
}
#<?php echo $wpmcw_id; ?> .wpmchimpa-item input[type='radio'] + span:before {
border-radius: 50%;
width: 12px;
height: 12px;
top: 7px;
}
#<?php echo $wpmcw_id; ?> .wpmchimpa-item input:checked + span:before{
  <?php if(isset($theme["check_c"])) $color = $theme["check_c"]; else $color = '#158EC6';
  print_r('box-shadow: inset 0 0 0 10px '.$color.';');?>
}

#<?php echo $wpmcw_id; ?> .wpmchimpa-item input[type='checkbox'] + span:hover:after, #<?php echo $wpmcw_id; ?> input[type='checkbox']:checked + span:after {
  content:'';
  background: no-repeat center;
  <?php if(isset($theme['check_shade']))$chs=$theme['check_shade'];else $chs='2';
  echo 'background-image: '.$plugin->chshade($chs).';';?>
  left: -1px;
  bottom: -1px;
}
#<?php echo $wpmcw_id; ?> .wpmchimpa-item input[type='checkbox']:not(:checked) + span:hover:after {
<?php echo 'background-image: '.$plugin->chshade('2').';';?>
 opacity: 0.5;
}


#<?php echo $wpmcw_id;?> .wpmcinfierr{
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
#<?php echo $wpmcw_id; ?> .wpmchimpa-subs-button{
  margin: 12px 0;
  width: 100%;
  height: 40px;
text-align: center;
    background: #62bc33;
    cursor:pointer;
  color:#fff;
    -webkit-box-shadow:none;
    -moz-box-shadow:none;
    -ms-box-shadow:none;
    -o-box-shadow:none;
    box-shadow:none;
    clear:both;
    text-decoration:none;
    text-shadow:none;
    border: 0;
    border-radius: 1px;
    -webkit-border-radius: 1px;
    -moz-border-radius: 1px;
    -ms-border-radius: 1px;
    -o-border-radius: 1px;
    position: relative;
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
        else { ?>
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
#<?php echo $wpmcw_id; ?> .wpmchimpa-subs-button::before{
   content: '<?php if(isset($theme['button'])) echo $theme['button'];else echo __( 'Subscribe', 'chimpmate-wpmca' );?>';
  display: block;
  position: relative;
  line-height: 40px;
  <?php if(isset($theme["button_h"])){
      echo 'line-height:'.$theme["button_h"].'px;';
  } ?>
}
#<?php echo $wpmcw_id; ?> .wpmchimpa-subs-button:hover{
  
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
#<?php echo $wpmcw_id; ?> .wpmchimpa-subs-button.subsicon:before{
padding-left: 40px;
  <?php 
  if(isset($theme["button_w"])){
      echo 'padding-left:'.$theme["button_h"].'px;';
  }
  ?>
}
#<?php echo $wpmcw_id; ?> .wpmchimpa-subs-button.subsicon::after{
content:'';
position: absolute;
height: 40px;
width: 40px;
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
     echo 'background: '.$plugin->getIcon($theme["button_i"],25,$col).' no-repeat center;';
  }
  ?>
}
#<?php echo $wpmcw_id; ?> .wpmchimpa-feedback {
margin: -40px 0 22px;
    color: #000;
height: 18px;
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
#<?php echo $wpmcw_id; ?> .wpmchimpa-feedback.wpmchimpa-done {
  margin: 0;height: auto;
}
#<?php echo $wpmcw_id; ?> .wpmchimpa-signalc {
height: 40px;
  margin: 10px;
  text-align: center;
}
#<?php echo $wpmcw_id; ?> .wpmchimpa-signal {
display: none;
}
#<?php echo $wpmcw_id; ?>.signalshow .wpmchimpa-signal {
  display: inline-block;
}
#<?php echo $wpmcw_id; ?> .wpmchimpa-tag,
#<?php echo $wpmcw_id; ?> .wpmchimpa-tag *{
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
#<?php echo $wpmcw_id; ?> .wpmchimpa-tag:before{

   content:<?php
        $tfs=10;
        if(isset($theme["tag_f"]['s'])){$tfs=$theme["tag_f"]['s'];}
        $tfc='#fff';
        if(isset($theme["tag_fc"])){$tfc=$theme["tag_fc"];}
        echo $plugin->getIcon('lock1',$tfs,$tfc);?>;
   margin: 5px;
   top: 1px;
   position:relative;
}
 </style>
<div class="wpmchimpa-reset chimpmatecss wpmchimpselector" id="<?php echo $wpmcw_id; ?>">
<?php if(isset($theme['msg'])) echo '<div class="wpmchimpa_para">'.$theme['msg'].'</div>';?>
	<form action="" method="post">
<input type="hidden" name="action" value="wpmchimpa_add_email_ajax"/>
<input type="hidden" name="wpmcform" value="<?php echo $form['id'];?>"/>
<?php $set = array(
  'icon' => false,
  'type' => 1
  );
$plugin->stfield($form['fields'],$set); 
?>
                        <div class="wpmchimpa-subs-button<?php echo (isset($theme['button_i']) && $theme['button_i'] != 'inone' && $theme['button_i'] != 'idef')? ' subsicon' : '';?>"></div>
                <?php if(isset($theme['tag_en'])){
                  if(isset($theme['tag'])) $tagtxt= $theme['tag'];
                  else $tagtxt=__( 'Secure and Spam free...', 'chimpmate-wpmca' );
                  echo '<div class="wpmchimpa-tag">'.$tagtxt.'</div>';
                  }?>
	<div class="wpmchimpa-signalc"><div class="wpmchimpa-signal"><?php 
            echo $plugin->getSpin(isset($theme["spinner_t"])?$theme["spinner_t"]:'1',$wpmcw_id,isset($theme["spinner_c"])?$theme["spinner_c"]:'#000');?></div></div>
	</form>
	<div class="wpmchimpa-feedback" wpmcerr="gen"></div>
</div> </div>