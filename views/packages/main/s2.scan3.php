<!-- ================================================================
ARCHIVE
================================================================ -->
<div class="details-title">
	<i class="fa fa-file-archive-o"></i>&nbsp;<?php _e('Archive', 'duplicator');?>
	<div class="dup-more-details" onclick="Duplicator.Pack.showDetails()"><i class="fa fa-window-maximize"></i></div>
</div>

<div class="scan-header scan-item-first">
	<i class="fa fa-files-o"></i>
	<?php _e("Files", 'duplicator'); ?>
	<i class="fa fa-question-circle data-size-help"
		data-tooltip-title="<?php _e("File Size:", 'duplicator'); ?>"
		data-tooltip="<?php _e('The files size represents only the included files before compression is applied. It does not include the size of the database script and in most cases the package size once completed will be smaller than this number.', 'duplicator'); ?>"></i>
	<div id="data-arc-size1"></div>

	<div class="dup-scan-filter-status">
		<?php
			if ($Package->Archive->ExportOnlyDB) {
				echo '<i class="fa fa-filter"></i> '; _e('Database Only', 'duplicator');
			}elseif ($Package->Archive->FilterOn) {
				echo '<i class="fa fa-filter"></i> '; _e('Enabled', 'duplicator');
			}
		?>
	</div>
</div>

<!-- ============
TOTAL SIZE -->
<div class="scan-item">
	<div class="title" onclick="Duplicator.Pack.toggleScanItem(this);">
		<div class="text"><i class="fa fa-caret-right"></i> <?php _e('Total Size', 'duplicator');?></div>
		<div id="data-arc-status-size"></div>
	</div>
	<div class="info">
		<b><?php _e('Size', 'duplicator');?>:</b> <span id="data-arc-size2"></span>  &nbsp; | &nbsp;
		<b><?php _e('File Count', 'duplicator');?>:</b> <span id="data-arc-files"></span>  &nbsp; | &nbsp;
		<b><?php _e('Directory Count', 'duplicator');?>:</b> <span id="data-arc-dirs"></span> <br/><br/>
		<?php
			printf(__('Total size represents all files minus any filters that have been setup.  The current thresholds that triggers a warning is %1$s for the total size.  '
				. 'Some budget hosts limit the amount of time a PHP/Web request process can run.  When working with larger sites this can cause timeout issues on some hosts.  '
				. 'Consider using a file filter in Step 1 to shrink and filter the overall size of your package.', 'duplicator'),
					DUP_Util::byteSize(DUPLICATOR_SCAN_SITE),
					DUP_Util::byteSize(DUPLICATOR_SCAN_WARNFILESIZE));

			if ($zip_check != null) {
				echo '<br/><br/>';
				echo '<span style="font-weight:bold">';
				_e('Package support up to 2GB available in Duplicator Pro.', 'duplicator');
				echo '</span>';
				echo "&nbsp;<i><a href='https://snapcreek.com/duplicator/?utm_source=duplicator_free&utm_medium=wordpress_plugin&utm_content=free_size_warn&utm_campaign=duplicator_pro' target='_blank'>[" . __('details', 'duplicator') . "]</a></i>";
			}
		?>
	</div>
</div>

<!-- ============
LARGE FILES -->
<div class="scan-item">
	<div class="title" onclick="Duplicator.Pack.toggleScanItem(this);">
		<div class="text"><i class="fa fa-caret-right"></i> <?php _e('Large Files', 'duplicator');?></div>
		<div id="data-arc-status-big"></div>
	</div>
	<div class="info">
		<?php
			_e('Large files such as movies or zipped content can create large packages and cause issues with timeouts on some hosts.  If your having issues creating a package try '
			. 'excluding the directory paths below.  Then manually move the filtered files to your new location.', 'duplicator');
		?>
		<script id="hb-files-large" type="text/x-handlebars-template">
			<div class="container">
				<div class="hdrs">
					<?php
						printf(__('Apply Filters: <i>Files over %1$s are shown below.</i>', 'duplicator'), DUP_Util::byteSize(DUPLICATOR_SCAN_WARNFILESIZE));
					?>
				</div>
				<div class="data">
				{{#if ARC.FilterInfo.Files.Size}}
					{{#each ARC.FilterInfo.Files.Size as |directory|}}
						<div class="directory">
							<i class="fa fa-caret-right fa-lg dup-nav" onclick="Duplicator.Pack.toggleDirPath(this)"></i> &nbsp;
							<input type="checkbox" name="dir_paths[]" value="{{@key}}" id="dir_{{@index}}" />

							<label for="dir_{{@index}}" title="{{@key}}">{{shortDirectory @key}}</label> <br/>
							<div class="files">
								{{#each directory as |file|}}
									[{{file.bytes}}] &nbsp; {{file.sname}} <br/>
								{{/each}}
							</div>
						</div>
					{{/each}}
				{{else}}
					No large files found during this scan.
				{{/if}}
				</div>
			</div>
			<div style="text-align:right">
				<button type="button" class="button-small" onclick="Duplicator.Pack.applyFilters()">
					<i class="fa fa-filter"></i> <?php _e('Apply Filters &amp; Rescan', 'duplicator');?>
				</button>
			</div>
		</script>
		<div id="hb-files-large-result" class="hb-files-style"></div>
	</div>
</div>

<!-- ============
FILE NAME CHECKS -->
<div class="scan-item scan-item-last">
	<div class="title" onclick="Duplicator.Pack.toggleScanItem(this);">
		<div class="text"><i class="fa fa-caret-right"></i> <?php _e('Name Checks', 'duplicator');?></div>
		<div id="data-arc-status-names"></div>
	</div>
	<div class="info">

		<?php
			_e('File or directory names may cause issues when working across different environments and servers.  Names that are over 250 characters, contain '
				. 'special characters (such as * ? > < : / \ |) or are unicode might cause issues in a remote enviroment.  It is recommended to remove or filter '
				. 'these files before building the archive if you have issues at install time.', 'duplicator');
		?>

	</div>
</div>
<br/><br/>


<!-- ============
DATABASE -->
<div id="dup-scan-db">
	<div class="scan-header scan-item-first">
		<i class="fa fa-table"></i>
		<?php _e("Database", 'duplicator');	?>
		<i class="fa fa-question-circle data-size-help"
			data-tooltip-title="<?php _e("Database Size:", 'duplicator'); ?>"
			data-tooltip="<?php _e('The database size represents only the included tables. The process for gathering the size uses the query SHOW TABLE STATUS.  The overall size of the database file can impact the final size of the package.', 'duplicator'); ?>"></i>
		<div id="data-db-size1"></div>
		<div class="dup-scan-filter-status">
			<?php
				if ($Package->Database->FilterOn) {
					echo '<i class="fa fa-filter"></i> '; _e('Enabled', 'duplicator');
				}
			?>
		</div>
	</div>

	<!-- ============
	DB: TOTAL SIZE -->
	<div class="scan-item">
		<div class="title" onclick="Duplicator.Pack.toggleScanItem(this);">
			<div class="text"><i class="fa fa-caret-right"></i> <?php _e('Total Size', 'duplicator');?></div>
			<div id="data-db-status-size"></div>
		</div>
		<div class="info">
			<b><?php _e('Size', 'duplicator');?>:</b> <span id="data-db-size2"></span> &nbsp; | &nbsp;
			<b><?php _e('Tables', 'duplicator');?>:</b> <span id="data-db-tablecount"></span> &nbsp; | &nbsp;
			<b><?php _e('Records', 'duplicator');?>:</b> <span id="data-db-rows"></span>
			 <br/><br/>
			<?php
				//OVERVIEW
				echo '<b>' . __('Overview:', 'duplicator') . '</b><br/>';
				printf(__('Total size and row count for all database tables are approximate values.  The thresholds that trigger warnings are %1$s OR %2$s records total for the entire database.  The larger the databases the more time it takes to process and execute.  This can cause issues with budget hosts that have cpu/memory limits, and timeout constraints.', 'duplicator'),
						DUP_Util::byteSize(DUPLICATOR_SCAN_DB_ALL_SIZE),
						number_format(DUPLICATOR_SCAN_DB_ALL_ROWS));

				//OPTIONS
				echo '<br/><br/>';
				echo '<b>' . __('Options:', 'duplicator') . '</b><br/>';
				$lnk = '<a href="maint/repair.php" target="_blank">' . __('Repair and Optimization', 'duplicator') . '</a>';
				printf(__('1. Running a %1$s on your database will help to improve the overall size, performance and efficiency of the database.', 'duplicator'), $lnk);
				echo '<br/><br/>';
				$lnk = '<a href="?page=duplicator-settings" target="_blank">' . __('Duplicator Settings', 'duplicator') . '</a>';
				printf(__('2. If your server supports shell_exec and mysqldump it is recommended to enable this option from the %1$s menu.', 'duplicator'), $lnk);
				echo '<br/><br/>';
				_e('3. Consider removing data from tables that store logging, statistical  or other non-critical information about your site.', 'duplicator');
			?>
		</div>
	</div>

	<!-- ============
	DB: TABLE DETAILS -->
	<div class="scan-item scan-item-last">
		<div class="title" onclick="Duplicator.Pack.toggleScanItem(this);">
			<div class="text"><i class="fa fa-caret-right"></i> <?php _e('Table Details', 'duplicator');?></div>
			<div id="data-db-status-details"></div>
		</div>
		<div class="info">
			<?php
				//OVERVIEW
				echo '<b>' . __('Overview:', 'duplicator') . '</b><br/>';
				printf(__('The thresholds that trigger warnings for individual tables are %1$s OR %2$s records OR tables names with upper-case characters.  The larger '
					. 'the table the more time it takes to process and execute.  This can cause issues with budget hosts that have cpu/memory limits, and timeout constraints.', 'duplicator'),
						DUP_Util::byteSize(DUPLICATOR_SCAN_DB_TBL_SIZE),
						number_format(DUPLICATOR_SCAN_DB_TBL_ROWS));

				//OPTIONS
				echo '<br/><br/>';
				echo '<b>' . __('Options:', 'duplicator') . '</b><br/>';
				$lnk = '<a href="maint/repair.php" target="_blank">' . __('Repair and Optimization', 'duplicator') . '</a>';
				printf(__('1. Run a %1$s on the table to improve the overall size and performance.', 'duplicator'), $lnk);
				echo '<br/><br/>';
				_e('2. Remove stale date from tables such as logging, statistical or other non-critical data.', 'duplicator');
				echo '<br/><br/>';
				$lnk = '<a href="http://dev.mysql.com/doc/refman/5.7/en/server-system-variables.html#sysvar_lower_case_table_names" target="_blank">' . __('lower_case_table_names', 'duplicator') . '</a>';
				printf(__('3. For table name case sensitivity issues either rename the table with lower case characters or be prepared to work with the %1$s system variable setting.', 'duplicator'), $lnk);
				echo '<br/><br/>';

				echo '<b>' . __('Tables:', 'duplicator') . '</b><br/>';
			?>

			<div id="dup-scan-db-info">
				<div id="data-db-tablelist"></div>
			</div>
		</div>
	</div>
	<br/>

	<div id="dup-archive-details" style="display: none">
		<b>DATABASE SETTINGS</b>
		<hr size="1" />
		<table id="dup-scan-db-details">
			<tr><td><b><?php _e('Name:', 'duplicator');?></b></td><td><?php echo DB_NAME ;?> </td></tr>
			<tr><td><b><?php _e('Host:', 'duplicator');?></b></td><td><?php echo DB_HOST ;?> </td></tr>
			<tr>
				<td style="vertical-align: top"><b><?php _e('Build Mode:', 'duplicator');?></b></td>
				<td style="line-height:18px">
					<a href="?page=duplicator-settings" target="_blank"><?php echo $dbbuild_mode ;?></a>
					<?php if ($mysqlcompat_on) :?>
						<br/>
						<small style="font-style:italic; color:maroon">
							<i class="fa fa-exclamation-circle"></i> <?php _e('MySQL Compatibility Mode Enabled', 'duplicator'); ?>
							<a href="https://dev.mysql.com/doc/refman/5.7/en/mysqldump.html#option_mysqldump_compatible" target="_blank">[<?php _e('details', 'duplicator'); ?>]</a>
						</small>
					<?php endif;?>
				</td>
			</tr>
		</table>
		<br/><br/>


		<!-- ============
		VIEW FILTERS -->
		<b>FILTER SETTINGS</b>
		<hr size="1" />
		<?php if ($Package->Archive->FilterOn) : ?>
			<div class="info">
				<b>[<?php _e('Root Directory', 'duplicator');?>]</b><br/>
				<?php echo DUPLICATOR_WPROOTPATH;?>
				<br/><br/>

				<b>[<?php _e('Excluded Directories', 'duplicator');?>]</b><br/>
				<?php
					if (strlen( $Package->Archive->FilterDirs)) {
						echo str_replace(";", "<br/>", $Package->Archive->FilterDirs);
					} else {
						_e('No directory filters have been set.', 'duplicator');
					}
				?>
				<br/>

				<b>[<?php _e('Excluded File Extensions', 'duplicator');?>]</b><br/>
				<?php
					if (strlen( $Package->Archive->FilterExts)) {
						echo $Package->Archive->FilterExts;
					} else {
						_e('No file extension filters have been set.', 'duplicator');
					}
				?>
				<small>
					<?php
						_e('The root directory is where Duplicator starts archiving files.  The excluded sections will be skipped during the archive process.  ', 'duplicator');
						_e('All results are stored in a json file. ', 'duplicator');
					?>
					<a href="<?php echo DUPLICATOR_SITE_URL ?>/wp-admin/admin-ajax.php?action=duplicator_package_scan" target="dup_report"><?php _e('[view json report]', 'duplicator');?></a>
				</small><br/>
			</div>
		<?php endif;  ?>

	</div>
	
</div><!-- end .dup-scan-db -->


<!-- ==========================================
THICK-BOX DIALOGS: -->
<?php
	$alert1 = new DUP_UI_Dialog();
	$alert1->height     = 600;
	$alert1->width      = 600;
	$alert1->title		= __('Scan Details', 'duplicator');
	$alert1->message	= "<div id='dup-archive-details-window'></div>";
	$alert1->initAlert();


?>

<script>
jQuery(document).ready(function($)
{
	Handlebars.registerHelper('if_eq',		function(a, b, opts) { return (a == b) ? opts.fn(this) : opts.inverse(this);});
	Handlebars.registerHelper('if_neq',		function(a, b, opts) { return (a != b) ? opts.fn(this) : opts.inverse(this);});
	Handlebars.registerHelper('shortDirectory', function(path) {return  (path.length > 70) ? path.slice(0, 70) + '...' : path;});

	Duplicator.Pack.showDetails = function ()
	{
		$('#dup-archive-details-window').html($('#dup-archive-details').html());
		<?php $alert1->showAlert(); ?>
		return;
	}

	Duplicator.Pack.toggleDirPath = function(item)
	{
		var $dir   = $(item).parents('div.directory');
		var $files = $dir.find('div.files');
		var $arrow = $dir.find('i.dup-nav');
		if ($files.is(":hidden")) {
			$arrow.addClass('fa-caret-down').removeClass('fa-caret-right');
			$files.show();
		} else {
			$arrow.addClass('fa-caret-right').removeClass('fa-caret-down');
			$files.hide(250);
		}
	}

	Duplicator.Pack.toggleScanItem = function(item)
	{
		var $info = $(item).parents('div.scan-item').children('div.info');
		var $text = $(item).find('div.text i.fa');
		if ($info.is(":hidden")) {
			$text.addClass('fa-caret-down').removeClass('fa-caret-right');
			$info.show();
		} else {
			$text.addClass('fa-caret-right').removeClass('fa-caret-down');
			$info.hide(250);
		}
	}

	Duplicator.Pack.applyFilters = function()
	{
		var filters = [];
		$("#hb-files-large-result input[name='dir_paths[]']:checked").each(function (){
			filters.push($(this).val());
		});

		var data = {
			action: 'DUP_CTRL_Package_addDirectoryFilter',
			nonce: '<?php echo wp_create_nonce('DUP_CTRL_Package_addDirectoryFilter'); ?>',
			dir_paths : filters.join(";")
		};

		$.ajax({
			type: "POST",
			cache: false,
			url: ajaxurl,
			dataType: "json",
			timeout: 100000,
			data: data,
			complete: function() { Duplicator.Pack.rescan();},
			success:  function(data) {console.log(data);},
			error: function(data) {	console.log(data);}
		});
	}

	Duplicator.Pack.intLargeFileView = function(data)
	{
		var template = $('#hb-files-large').html();
		var templateScript = Handlebars.compile(template);
		var html = templateScript(data);
		$('#hb-files-large-result').html(html);
	}

	Duplicator.Pack.setScanStatus = function(status)
	{
		var result;
		switch (status) {
			case false :    result = '<div class="dup-scan-warn"><i class="fa fa-exclamation-triangle"></i></div>';      break;
			case 'Warn' :   result = '<div class="badge-warn">Warn</div>'; break;
			case true :     result = '<div class="dup-scan-good"><i class="fa fa-check"></i></div>';	                 break;
			case 'Good' :   result = '<div class="badge-pass">Good</div>';                break;
			default :
				result = 'unable to read';
		}
		return result;
	}

	/*	Load Scan Data   */
	Duplicator.Pack.loadScanData = function(data)
	{
	
		$('#dup-progress-bar-area').hide();

		//ERROR: Data object is corrupt or empty return error
		if (data == undefined || data.RPT == undefined)
		{
			Duplicator.Pack.intErrorView();
			console.log('JSON Report Data:');
			console.log(data);
			return;
		}

		$('#data-rpt-scantime').text(data.RPT.ScanTime || 0);
		Duplicator.Pack.intServerData(data);
		Duplicator.Pack.intLargeFileView(data);


		//****************
		//DATABASE
		var errMsg = "unable to read";
		var html = "";
		var DB_TotalSize = 'Good';
		var DB_TableDetails = 'Good';
		var DB_TableRowMax  = <?php echo DUPLICATOR_SCAN_DB_TBL_ROWS; ?>;
		var DB_TableSizeMax = <?php echo DUPLICATOR_SCAN_DB_TBL_SIZE; ?>;
		if (data.DB.Status.Success)
		{
			DB_TotalSize = data.DB.Status.DB_Rows == 'Warn' || data.DB.Status.DB_Size == 'Warn' ? 'Warn' : 'Good';
			DB_TableDetails = data.DB.Status.TBL_Rows == 'Warn' || data.DB.Status.TBL_Size == 'Warn' || data.DB.Status.TBL_Case == 'Warn' ? 'Warn' : 'Good';

			$('#data-db-status-size').html(Duplicator.Pack.setScanStatus(DB_TotalSize));
			$('#data-db-status-details').html(Duplicator.Pack.setScanStatus(DB_TableDetails));
			$('#data-db-size1').text(data.DB.Size || errMsg);
			$('#data-db-size2').text(data.DB.Size || errMsg);
			$('#data-db-rows').text(data.DB.Rows || errMsg);
			$('#data-db-tablecount').text(data.DB.TableCount || errMsg);
			//Table Details
			if (data.DB.TableList == undefined || data.DB.TableList.length == 0) {
				html = '<?php _e("Unable to report on any tables", 'duplicator') ?>';
			} else {
				$.each(data.DB.TableList, function(i) {
					html += '<b>' + i  + '</b><br/>';
					$.each(data.DB.TableList[i], function(key,val) {
						html += (key == 'Case' && val == 1) || (key == 'Rows' && val > DB_TableRowMax) || (key == 'Size' && parseInt(val) > DB_TableSizeMax)
								? '<div style="color:red"><span>' + key  + ':</span>' + val + '</div>'
								: '<div><span>' + key  + ':</span>' + val + '</div>';
					});
				});
			}
			$('#data-db-tablelist').append(html);
		} else {
			html = '<?php _e("Unable to report on database stats", 'duplicator') ?>';
			$('#dup-scan-db').html(html);
		}

		//****************
		//ARCHIVE
		$('#data-arc-status-size').html(Duplicator.Pack.setScanStatus(data.ARC.Status.Size));
		$('#data-arc-status-names').html(Duplicator.Pack.setScanStatus(data.ARC.Status.Names));
		$('#data-arc-status-big').html(Duplicator.Pack.setScanStatus(data.ARC.Status.Big));
		$('#data-arc-size1').text(data.ARC.Size || errMsg);
		$('#data-arc-size2').text(data.ARC.Size || errMsg);
		$('#data-arc-files').text(data.ARC.FileCount || errMsg);
		$('#data-arc-dirs').text(data.ARC.DirCount || errMsg);

		//Name Checks
		html = '';
		//Dirs
		if (data.ARC.FilterInfo.Dirs.Warning !== undefined && data.ARC.FilterInfo.Dirs.Warning.length > 0) {
			$.each(data.ARC.FilterInfo.Dirs.Warning, function (key, val) {
				html += '<?php _e("DIR", 'duplicator') ?> ' + key + ':<br/>[' + val + ']<br/>';
			});
		}
		//Files
		if (data.ARC.FilterInfo.Files.Warning !== undefined && data.ARC.FilterInfo.Files.Warning.length > 0) {
			$.each(data.ARC.FilterInfo.Files.Warning, function (key, val) {
				html += '<?php _e("FILE", 'duplicator') ?> ' + key + ':<br/>[' + val + ']<br/>';
			});
		}
		html = (html.length == 0) ? '<?php _e("No name warning issues found.", 'duplicator') ?>' : html;


		$('#data-arc-names-data').html(html);
		$('#dup-msg-success').show();

		//Waring Check
		var warnCount = data.RPT.Warnings || 0;
		if (warnCount > 0) {
			$('#dup-scan-warning-continue').show();
			$('#dup-build-button').prop("disabled",true).removeClass('button-primary');
		} else {
			$('#dup-scan-warning-continue').hide();
			$('#dup-build-button').prop("disabled",false).addClass('button-primary');
		}

	}


});
</script>