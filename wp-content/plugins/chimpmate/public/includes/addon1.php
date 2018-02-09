<?php 
$theme['msg'] = htmlspecialchars_decode($theme['msg']);
$this->social=true;
?>
 <style type="text/css">
.wpmchimpab {
width: 100%;
padding:10px;
background: #fff;
display:none;
<?php  if(isset($theme["bg_c"])){
    echo 'background-color:'.$theme["bg_c"].';';
}
$bc='#EDEDED';
$bw=1;
if(isset($theme["addon_bor_c"]))$bc = $theme["addon_bor_c"];
if(isset($theme["addon_bor_w"]))$bw = $theme["addon_bor_w"];
  echo 'border: '.$bc.' solid '.$bw.'px;';
?>
-webkit-border-radius: 2px;
-moz-border-radius: 2px;
border-radius: 2px;
}
.wpmchimpab .wpmchimpa-leftpane{
display: inline-block;
float: left;
<?php 
        if(isset($theme["addon_dissoc"])){
          echo 'display:none;';
        }?>
}
.wpmchimpab form{
  display: inline-block;
  padding: 0 20px;
  width: calc(100% - 160px);
}
.wpmchimpab .wpmchimpa-cont{
  margin-top: 20px;
}
.wpmchimpab h3{
  font-size: 18px;
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
.wpmchimpab .wpmchimpa_para,.wpmchimpab .wpmchimpa_para * {
<?php if(isset($theme["msg_f"]['f'])){
    array_push($wpmc_font, $theme["msg_f"]['f']);
  echo 'font-family:'.$theme["msg_f"]['f'].';';
}if(isset($theme["msg_f"]['s'])){
    echo 'font-size:'.$theme["msg_f"]['s'].'px;';
}?>
}

.wpmchimpab  .wpmchimpa-field{
position: relative;
width:100%;
margin: 0 auto 12px auto;
<?php 
  if(isset($theme["tbox_w"])){
      echo 'width:'.$theme["tbox_w"].'px;';
  }
?>
}
.wpmchimpab .inputicon{
display: none;
}
.wpmchimpab .wpmc-ficon .inputicon {
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
.wpmchimpab .wpmc-ficon input[type="text"],
.wpmchimpab .wpmc-ficon  input[type="text"] ~ .inputlabel{
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
    echo '.wpmchimpab .wpmc-ficon [wpmcfield="'.$f['tag'].'"] ~ .inputicon {background: '.$this->getIcon($f['icon'],30,$col).' no-repeat center}';
}
?>
.wpmchimpab .wpmchimpa-field textarea,
.wpmchimpab .wpmchimpa-field select,
.wpmchimpab input[type="text"]{
width: 100%;
height: 45px;
background: #f8fafa;
padding: 0 20px;
-moz-border-radius: 5px;
-webkit-border-radius: 5px;
-ms-border-radius: 5px;
-o-border-radius: 5px;
border-radius: 5px;
border: 1px solid #e4e9e9;
color: #353535;
font-size: 16px;
outline:0;
display: block;
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

.wpmchimpab .wpmchimpa-field.wpmchimpa-multidrop select{
  height: 100px;
}
.wpmchimpab .wpmchimpa-field.wpmchimpa-drop:before{
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
.wpmchimpab input[type="text"] ~ .inputlabel{
position: absolute;
top: 0;
left: 0;
right: 0;
pointer-events: none;
width: 100%;
line-height: 45px;
padding: 0 20px;
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
.wpmchimpab input[type="text"]:valid + .inputlabel{
display: none;
}
.wpmchimpab select.wpmcerror,
.wpmchimpab input[type="text"].wpmcerror{
  border-color: red;
}
.wpmchimpab .wpmchimpa-check *,
.wpmchimpab .wpmchimpa-radio *{
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
.wpmchimpab .wpmchimpa-item input {
  display: none;
}
.wpmchimpab .wpmchimpa-item span {
  cursor: pointer;
  display: inline-block;
  position: relative;
  padding-left: 35px;
  line-height: 29px;
  margin-right: 10px;
}

.wpmchimpab .wpmchimpa-item span:before,
.wpmchimpab .wpmchimpa-item span:after {
  content: '';
  display: inline-block;
  width: 18px;
  height: 18px;
  left: 0;
  top: 5px;
  position: absolute;
}
.wpmchimpab .wpmchimpa-item span:before {
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
.wpmchimpab .wpmchimpa-item input[type='checkbox'] + span:before {
border-radius: 3px;
}
.wpmchimpab .wpmchimpa-item input:checked + span:before{
  <?php if(isset($theme["check_c"]))echo 'background: '.$theme["check_c"].';';?>
}
.wpmchimpab .wpmchimpa-item input[type='checkbox'] + span:hover:after, .wpmchimpab input[type='checkbox']:checked + span:after {
  content:'';
  background: no-repeat center;
  <?php if(isset($theme['check_shade']))$chs=$theme['check_shade'];else $chs='1';
  echo 'background-image: '.$this->chshade($chs).';';?>
  left: -1px;
  bottom: -1px;
}
.wpmchimpab .wpmchimpa-item input[type='radio'] + span:before {
border-radius: 50%;
width: 16px;
height: 16px;
top: 5px;
}
.wpmchimpab input[type='radio']:checked + span:after {
background: <?php echo ($chs == 1)?'#7C7C7C':'#fafafa';?>;
width: 12px;
height: 12px;
top: 7px;
left: 2px;
border-radius: 50%;
}
.wpmchimpab .wpmcinfierr{
  display: block;
  height: 12px;
  line-height: 12px;
  margin-bottom: -12px;
  font-size: 11px;
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
.wpmchimpab .wpmchimpa-subs-button{
border-radius: 3px;
-moz-border-radius: 3px;
-webkit-border-radius: 3px;
-ms-border-radius: 3px;
-o-border-radius: 3px;
width: 100%;
padding: 0 22px;
color: #fff;
font-size: 22px;
border: 1px solid #3079ed;
background-color: #4d90fe;
height: 45px;
line-height: 45px;
text-align: center;
cursor: pointer;
margin-bottom: 10px;
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
        echo 'background-color:'.$theme["button_bc"].';';
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
.wpmchimpab .wpmchimpa-subs-button::before{
content: '<?php if(isset($theme['button'])) echo $theme['button'];else echo __( 'Subscribe', 'chimpmate-wpmca' );?>';
<?php if(isset($theme["button_h"])){
      echo 'line-height:'.$theme["button_h"].'px;';
  } ?>
}
.wpmchimpab .wpmchimpa-subs-button:hover{
<?php if(isset($theme["button_fch"])){
        echo 'color:'.$theme["button_fch"].';';
    }    
    if(isset($theme["button_bch"])){
        echo 'background-color:'.$theme["button_bch"].';';
    } else{ ?>
  background-image: -webkit-linear-gradient(top,#4d90fe,#4787ed);
background-image: -moz-linear-gradient(top,#4d90fe,#4787ed);
background-image: -mz-linear-gradient(top,#4d90fe,#4787ed);
background-image: -o-linear-gradient(top,#4d90fe,#4787ed);
background-image: -webkit-linear-gradient(top,#4d90fe,#4787ed);
  <?php }?>
}
.wpmchimpab .wpmchimpa-subs-button.subsicon:before{
padding-left: 45px;
  <?php 
  if(isset($theme["button_w"])){
      echo 'padding-left:'.$theme["button_h"].'px;';
  }
  ?>
}
.wpmchimpab .wpmchimpa-subs-button.subsicon::after{
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
     echo 'background: '.$this->getIcon($theme["button_i"],30,$col).' no-repeat center;';
  }
  ?>
}
.wpmchimpab .wpmchimpa-social{
display: block;
}
.wpmchimpab .wpmchimpa-social::before{
content: '<?php if(isset($theme['soc_head'])) echo $theme['soc_head'];else echo __( 'Subscribe with', 'chimpmate-wpmca' );?>';
font-size: 20px;
line-height: 30px;
display: block;
text-align: center;
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

.wpmchimpab .wpmchimpa-social .wpmchimpa-soc{
margin: 5px;
cursor: pointer;
width:150px;
height: 35px;
margin-bottom: 5px;
border-radius: 5px;
-webkit-transition: all 0.1s ease;
transition: all 0.1s ease;
-webkit-backface-visibility:hidden;
}
.wpmchimpab .wpmchimpa-social .wpmchimpa-soc:hover{
-webkit-transform:scale(1.1);
-ms-transform:scale(1.1);
transform:scale(1.1);
}
.wpmchimpab .wpmchimpa-social .wpmchimpa-soc::before{
display: block;
margin: 4px 6px;
padding: 0px 5px;
display: inline-block;
border-right: 1px solid #cccccc;
height: 23px;
}

.wpmchimpab .wpmchimpa-social .wpmchimpa-soc::after{
position: absolute;
line-height: 35px;
padding-left: 10px;
color: #fff;
font-size: 14px;
}
.wpmchimpab .wpmchimpa-social .wpmchimpa-soc.wpmchimpa-fb {
    background: #2d609b;
    <?php if(!isset($settings["fb_api"])){
	echo 'display:none;';
    }?>
}
.wpmchimpab .wpmchimpa-social .wpmchimpa-soc.wpmchimpa-fb::before {
   content:<?php echo $this->getIcon('fb',25,'#fff');?>
}
.wpmchimpab .wpmchimpa-social .wpmchimpa-soc.wpmchimpa-gp {
    background: #eb4026;
    <?php if(!isset($settings["gp_api"])){
	echo 'display:none;';
    }?>
}

.wpmchimpab .wpmchimpa-social .wpmchimpa-soc.wpmchimpa-gp::before {
    content: <?php echo $this->getIcon('gp',25,'#fff');?>
}
.wpmchimpab .wpmchimpa-social .wpmchimpa-soc.wpmchimpa-ms {
    background: #00BCF2;
    <?php if(!isset($settings["ms_api"])){
  echo 'display:none;';
    }?>
}
.wpmchimpab .wpmchimpa-social .wpmchimpa-soc.wpmchimpa-ms::before {
    content: <?php echo $this->getIcon('ms',25,'#fff');?>
}

.wpmchimpab .wpmchimpa-social .wpmchimpa-soc.wpmchimpa-fb::after {
    content:"Facebook";
}
.wpmchimpab .wpmchimpa-social .wpmchimpa-soc.wpmchimpa-gp::after {
    content:"Google Plus";
}
.wpmchimpab .wpmchimpa-social .wpmchimpa-soc.wpmchimpa-ms::after {
    content:"Outlook";
}

.wpmchimpab .wpmchimpa-signalc {
height: 40px;
  margin: 10px;
  text-align: center;
}
.wpmchimpab .wpmchimpa-signal {
display: none;
}
.wpmchimpab.signalshow .wpmchimpa-signal {
  display: inline-block;
}
.wpmchimpab .wpmchimpa-feedback{
margin: -40px 0 22px;
position: relative;
font-size: 16px;
min-height: 16px;
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

.wpmchimpab .wpmchimpa-feedback.wpmchimpa-done{
  clear: both;
  margin: 0;
}
@media only screen 
and (max-width : 900px) {
.wpmchimpab form{
  clear:both;
  width: 100%;
}
.wpmchimpab .wpmchimpa-leftpane{width: 100%;}
.wpmchimpab .wpmchimpa-social .wpmchimpa-soc{margin:5px auto;}
}

.wpmchimpab .wpmchimpa-tag{
text-align: center;
position: relative;
margin-top: 5px;
}
.wpmchimpab .wpmchimpa-tag,
.wpmchimpab .wpmchimpa-tag *{
color:#000;
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
.wpmchimpab .wpmchimpa-tag:before{

   content:<?php
        $tfs=10;
        if(isset($theme["tag_f"]["s"])){$tfs=$theme["tag_fs"];}
        $tfc='#000';
        if(isset($theme["tag_fc"])){$tfc=$theme["tag_fc"];}
        echo $this->getIcon('lock1',$tfs,$tfc);?>;
   margin: 5px;
   top: 1px;
   position:relative;
}
</style>

<div class="wpmchimpa-reset wpmchimpselector wpmchimpab chimpmatecss" id="wpmchimpab">
<?php if(isset($theme['heading'])) echo '<h3>'.$theme['heading'].'</h3>';?>
<?php if(isset($theme['msg'])) echo '<div class="wpmchimpa_para">'.$theme['msg'].'</div>';?>
  <div class="wpmchimpa-cont">
	    <div class="wpmchimpa-leftpane">
            <div class="wpmchimpa-social">
                <div class="wpmchimpa-soc wpmchimpa-fb"></div>
                <div class="wpmchimpa-soc wpmchimpa-gp"></div>
                <div class="wpmchimpa-soc wpmchimpa-ms"></div>
            </div>
        </div>
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
              <?php if(isset($theme['tag_en'])){
              if(isset($theme['tag'])) $tagtxt= $theme['tag'];
              else $tagtxt=__( 'Secure and Spam free...', 'chimpmate-wpmca' );
              echo '<div class="wpmchimpa-tag">'.$tagtxt.'</div>';
              }?>
			<div class="wpmchimpa-signalc"><div class="wpmchimpa-signal"><?php 
            echo $this->getSpin(isset($theme["spinner_t"])?$theme["spinner_t"]:'7','wpmchimpab',isset($theme["spinner_c"])?$theme["spinner_c"]:'#000');?></div></div>
		</form>
    	<div class="wpmchimpa-feedback" wpmcerr="gen"></div>
    </div> <div style="clear:both"></div>
	</div>	
