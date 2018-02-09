<?php 
$topid = 'wpmchimpat';
?>
<style type="text/css">
.wpmchimpat{
	position:fixed;z-index: 99999;
	display: block;
	width: 100%;
	height: 50px;
	background: #000;
left: 0;
  <?php 
        if(isset($theme["bg_c"])){
            echo 'background:'.$theme["bg_c"].';';
        }
    ?>
}
.wpmchimpat.wpmctb_top{
top:0;
}
.wpmchimpat.wpmctb_bot{
bottom:0;
}
.wpmchimpat.wpmchimpat-close{
visibility: hidden;
}
.wpmchimpat .wpmchimpat-cont{
  display: block;
  display: flex;
  justify-content: center;
  align-items: center;
  margin:0 auto;
  width: 75%;
  padding: 10px;
  text-align: center;
}
.wpmchimpat h3{
  font-size: 18px;
  display: inline-block;
  line-height: 30px;
  color: #fff;
  white-space: nowrap;
  text-align: right;
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

.wpmchimpat .wpmchimpa-field{
position: relative;
width:<?php echo ((count($fields) > 1)? 21 : 30); ?>%;
margin: 0 10px;
display: inline-block;
}
.wpmchimpat .inputicon{
display: none;
}
.wpmchimpat .wpmc-ficon .inputicon {
display: block;
width: 30px;
height: 30px;
position: absolute;
top: 0;
left: 0;
pointer-events: none;
}
.wpmchimpat .wpmc-ficon input[type="text"],
.wpmchimpat .wpmc-ficon .inputlabel{
  padding-left: 30px;
}
<?php
$col = ((isset($theme["inico_c"]))? $theme["inico_c"] : '#888');
foreach ($form['fields'] as $f) {
  if($f['icon'] != 'idef' && $f['icon'] != 'inone')
    echo '.wpmchimpat .wpmc-ficon [wpmcfield="'.$f['tag'].'"] ~ .inputicon {background: '.$this->getIcon($f['icon'],20,$col).' no-repeat center}';
}
?>
.wpmchimpat .wpmchimpa-field select,
.wpmchimpat input[type="text"]{
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


.wpmchimpat .wpmchimpa-field.wpmchimpa-drop:before{
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
.wpmchimpat input[type="text"] ~ .inputlabel{
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
overflow: hidden;
white-space: nowrap;
text-overflow: ellipsis;
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
.wpmchimpat input[type="text"]:valid + .inputlabel{
display: none;
}
.wpmchimpat select.wpmcerror,
.wpmchimpat input[type="text"].wpmcerror{
  border-color: red;
}
.wpmchimpat .wpmcinfierr{
  display: block;
  height: 10px;
  line-height: 10px;
  margin-bottom: -10px;
  font-size: 10px;
  color: red;
  position: absolute;
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
.wpmchimpat .wpmchimpa-subs-button{
display:inline-block;
text-align: center;
width: <?php echo ((count($fields) > 1)? 23 : 33); ?>%;
height:30px;
line-height: 28px;
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
.wpmchimpat .wpmchimpa-subs-button::before{
  content: '<?php if(isset($theme['button'])) echo $theme['button'];else echo __( 'Subscribe', 'chimpmate-wpmca' );?>';
   display: block;
  position: relative;
  line-height: 28px;
}
.wpmchimpat .wpmchimpa-subs-button:hover{
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
.wpmchimpat .wpmchimpa-subs-button.subsicon:before{
padding-left: 30px;

}
.wpmchimpat .wpmchimpa-subs-button.subsicon::after{
content:'';
position: absolute;
height: 30px;
width: 30px;
top: 0;
left: 0;
pointer-events: none;
  <?php 

  if($theme["button_i"] != 'inone' && $theme["button_i"] != 'idef'){
    $col = ((isset($theme["button_fc"]))? $theme["button_fc"] : '#fff');
     echo 'background: '.$this->getIcon($theme["button_i"],20,$col).' no-repeat center;';
  }
  ?>
}
.wpmchimpat .wpmchimpa-feedback{
position: absolute;
bottom: 0;
color: #fff;
font-size: 10px;
text-align: center;
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
.wpmchimpat .wpmchimpa-feedback.wpmchimpa-done{
  line-height: 50px;
  font-size: 20px;
  top:0;height: auto;
}
.wpmchimpat .wpmchimpat-close-button {
display: inline-block;
width: 2em;
height: 2em;
right: 10px;
top:10px;
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


.wpmchimpat .wpmchimpat-close-button::before {
    content: "";
    display: block;
    position: absolute;
    background-color: #000;
    width: 80%;
    height: 6%;
    left: 10%;
    top: 47%;
  }

  .wpmchimpat .wpmchimpat-close-button::after {
    content: "";
    display: block;
    position: absolute;
    background-color: #000;
    width: 6%;
    height: 80%;
    left: 47%;
    top: 10%;
  }
  .wpmchimpat .wpmchimpat-close-button:hover {
    background-color: #fff; 
    -ms-transform: rotate(225deg);
    -webkit-transform: rotate(225deg);
    -moz-transform: rotate(225deg); 
    -o-transform: rotate(225deg); 
    transform: rotate(225deg); 
  } 

.wpmchimpat .wpmchimpat-close-button:hover::after {
  background-color: #7e7e7e;
}
.wpmchimpat .wpmchimpat-close-button:hover::before {
  background-color: #7e7e7e;
}

.wpmchimpat .wpmchimpa-signalc {
height: 40px;
width: 40px;
top: 8px;
right: 60px;
position: absolute;
}

.wpmchimpat .wpmchimpa-signal {
display: none;
}
.wpmchimpat.signalshow .wpmchimpa-signal {
  display: inline-block;
}
@media only screen and (max-width : 1200px) {
#wpmchimpat h3{font-size: 13px;}
}
@media only screen and (max-width : 1024px) {
#wpmchimpat{display: none;}
}

</style>

<div class="wpmchimpa-reset wpmchimpat chimpmatecss wpmchimpat-close wpmchimpselector wpmctb_top" id="<?php echo $topid;?>">
	<form action="" method="post">
  <div class="wpmchimpat-cont">
<?php if(isset($theme['heading'])) echo '<h3>'.$theme['heading'].'</h3>';?>
<input type="hidden" name="action" value="wpmchimpa_add_email_ajax"/>
<input type="hidden" name="wpmcform" value="<?php echo $form['id'];?>"/>
<?php $set = array(
  'icon' => false,
  'type' => 1
  );
$this->stfield($fields,$set); 
?>

  <div class="wpmchimpa-subs-button<?php echo (isset($theme['button_i']) && $theme['button_i'] != 'inone' && $theme['button_i'] != 'idef')? ' subsicon' : '';?>"></div>
    </div>
   <div class="wpmchimpa-signalc"><div class="wpmchimpa-signal"><?php 
            echo $this->getSpin(isset($theme["spinner_t"])?$theme["spinner_t"]:'1',$topid,isset($theme["spinner_c"])?$theme["spinner_c"]:'#fff');?></div></div>

	</form>
	<div class="wpmchimpa-feedback" wpmcerr="gen"></div>
  <div class="wpmchimpat-close-button"></div>
</div>