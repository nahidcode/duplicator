<?php
DUP_Util::CheckPermissions('read');

require_once(DUPLICATOR_PLUGIN_PATH . '/assets/js/javascript.php');
require_once(DUPLICATOR_PLUGIN_PATH . '/views/inc.header.php');
?>
<style>
    div.dup-perks-all {font-size:13px; line-height:20px}
    div.dup-perks-hlp-area {width:320px; height:160px; float:left; border:1px solid #dfdfdf; border-radius:8px; margin:20px 30px 10px 40px;box-shadow: 0 8px 6px -6px #ccc;background: #fff}
    div.dup-perks-hlp-hdrs {
        font-weight:bold; font-size:17px; height: 25px; padding:10px 0 5px 0; text-align: center;
		background: #eeeeee;
		background: -moz-linear-gradient(top,  #eeeeee 0%, #cccccc 100%);
		background: -webkit-linear-gradient(top,  #eeeeee 0%,#cccccc 100%);
		background: linear-gradient(to bottom,  #eeeeee 0%,#cccccc 100%);
		filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#eeeeee', endColorstr='#cccccc',GradientType=0 );
    }
    div.dup-perks-txt{padding:10px 4px 4px 4px; text-align:center; font-size:16px}
</style>


<div class="wrap dup-wrap dup-perks-all">
	
    <?php duplicator_header(__("Perks", 'duplicator')) ?>
    <hr size="1" />

    <div style="width:800px; margin:auto; margin-top:10px;">

		<div style="text-align: center; font-size:18px; font-weight: bold">
			<?php	_e("Help support Duplicator and get some amazing products!", 'duplicator');	?>
		</div>

		<!-- ==========================================================
		ROW 1  -->
		<!-- BLUEHOST -->
        <div class="dup-perks-hlp-area">
            <div class="dup-perks-hlp-hdrs">
                <i class="fa fa-th fa-1x"></i> <?php _e('Bluehost', 'duplicator') ?>
            </div>
            <div class="dup-perks-txt">
				<a href="https://snapcreek.com/visit/bluehost" target="_blank">
					<img src="<?php echo DUPLICATOR_PLUGIN_URL ?>assets/img/perks_bluehost.png" style="padding:5px 0 10px 0" /><br/>
					<?php _e('Special Duplicator 50% Off Offer!', 'duplicator') ?>
				</a>
            </div>
        </div>
		
        <!-- INMOTION -->
        <div class="dup-perks-hlp-area">
            <div class="dup-perks-hlp-hdrs">
				<i class="fa fa-cube fa-1x"></i> <?php _e('InMotion', 'duplicator') ?>
			</div>
            <div class="dup-perks-txt">
				<a href="https://snapcreek.com/visit/inmotion" target="_blank">
					<img src="<?php echo DUPLICATOR_PLUGIN_URL ?>assets/img/perks_inmotion.png" style="padding:10px 0 5px 0" /><br/>
					<?php _e('Upto 25% Off - with FREE SSDs', 'duplicator') ?>
				</a>
            </div>
        </div>

		<!-- ==========================================================
		ROW 2  -->
        <!-- ELEGANT THEMES -->
        <div class="dup-perks-hlp-area">
            <div class="dup-perks-hlp-hdrs">
                <i class="fa fa-asterisk fa-1x"></i> <?php _e('Elegant Themes', 'duplicator') ?>
            </div>
            <div class="dup-perks-txt">
				<a href="https://snapcreek.com/visit/elegantthemes" target="_blank">
					<img src="<?php echo DUPLICATOR_PLUGIN_URL ?>assets/img/perks_ethemes.png" style="padding:0 0 5px 0" /><br/>
					<?php _e('10% off Lifetime Access!', 'duplicator') ?>
				</a>
            </div>
        </div>
		
		<!-- NINJA FORMS -->
        <div class="dup-perks-hlp-area">
            <div class="dup-perks-hlp-hdrs">
                <i class="fa fa-check-square-o fa-1x"></i> <?php _e('Ninja Forms', 'duplicator') ?>
            </div>
            <div class="dup-perks-txt">
				<a href="https://snapcreek.com/visit/managewp" target="_blank">
					<img src="<?php echo DUPLICATOR_PLUGIN_URL ?>assets/img/perks_ninjaforms.png" style="padding:5px 0 10px 0; " /><br/>
					<?php _e('Power Manage It All!', 'duplicator') ?>
				</a>
            </div>
        </div>
		
		<!-- ==========================================================
		ROW 3  -->
		<!-- OPTIN MONSTER -->
        <div class="dup-perks-hlp-area">
            <div class="dup-perks-hlp-hdrs">
                <i class="fa fa-envelope fa-1x"></i> <?php _e('OptinMonster', 'duplicator') ?>
            </div>
            <div class="dup-perks-txt">
				<a href="https://snapcreek.com/visit/managewp" target="_blank">
					<img src="<?php echo DUPLICATOR_PLUGIN_URL ?>assets/img/perks_optinmonster.png" style="padding:5px 0 10px 0" /><br/>
					<?php _e('Power Manage It All!', 'duplicator') ?>
				</a>
            </div>
        </div>
		
		<!-- MANAGE WP -->
        <div class="dup-perks-hlp-area">
            <div class="dup-perks-hlp-hdrs">
                <i class="fa fa-sitemap fa-1x"></i> <?php _e('ManageWP', 'duplicator') ?>
            </div>
            <div class="dup-perks-txt">
				<a href="https://snapcreek.com/visit/managewp" target="_blank">
					<img src="<?php echo DUPLICATOR_PLUGIN_URL ?>assets/img/perks_managewp.png" style="padding:5px 0 10px 0" /><br/>
					<?php _e('Power Manage It All!', 'duplicator') ?>
				</a>
            </div>
        </div>
		
        
		<!-- ==========================================================
		ROW 4  -->		
		
		        <!-- MAX CDN -->
        <div class="dup-perks-hlp-area">
            <div class="dup-perks-hlp-hdrs">
                <i class="fa fa-maxcdn fa-1x"></i> <?php _e('MaxCDN', 'duplicator') ?>
            </div>
            <div class="dup-perks-txt">
				<a href="https://snapcreek.com/visit/maxcdn" target="_blank">
					<img src="<?php echo DUPLICATOR_PLUGIN_URL ?>assets/img/perks_maxcdn.png" style="padding:5px 0 10px 0" /><br/>
					<?php _e('Supercharge Your Site!', 'duplicator') ?>
				</a>
				
            </div>
        </div>
	
		<!-- DUPLICATOR PRO -->
        <div class="dup-perks-hlp-area">
            <div class="dup-perks-hlp-hdrs">
                <i class="fa fa-share-alt fa-1x"></i> <?php _e('Duplicator Pro', 'duplicator') ?>
            </div>
            <div class="dup-perks-txt">
				<a href="https://snapcreek.com/visit/managewp" target="_blank">
					<img src="<?php echo DUPLICATOR_PLUGIN_URL ?>assets/img/logo-dpro-300x50-nosnap.png" style="padding:5px 0 10px 0; width:250px" /><br/>
					<?php _e('Go Professional!', 'duplicator') ?>
				</a>
            </div>
        </div>
		<br style="clear:both"/>
	
		<div style="margin:60px 20px; text-align: center"><small>Some promotions may change</small></div>
	
    </div>
</div>
<br/><br/><br/><br/>

<script type="text/javascript">
    jQuery(document).ready(function($) {

        Duplicator.OpenSupportWindow = function() {
            var url = 'http://lifeinthegrid.com/duplicator/resources/';
            window.open(url, 'litg');
        }

        //ATTACHED EVENTS
        jQuery('#dup-perks-kb-lnks').change(function() {
            if (jQuery(this).val() != "null")
                window.open(jQuery(this).val())
        });

    });
</script>