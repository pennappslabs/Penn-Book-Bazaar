		</div><!-- close content -->
		<?php
		if(strpos($_SERVER["REQUEST_URI"], "login.php")<=0){//do not display for login?>
		<div id="sidebar">
			<ul id="nav_sub">
				<li><a href="<?php echo SITE_URL;?>/admin/index.php"><?php echo _("Dashboard");?></a></li>
				<li><a href="listing.php"><?php echo _("Listings");?></a></li>
				<li><a href="categories.php"><?php echo _("Categories");?></a></li>
				<li><a href="locations.php"><?php echo _("Locations");?></a></li>
				<li><a href="accounts.php"><?php echo _("Accounts");?></a></li>
				<li><a href="settings.php"><?php echo _("Settings");?></a></li>
				<li><a href="stats.php"><?php echo _("Site Statistics");?></a></li>
			</ul>
			<ul id="tools">
				<li><a href="optimize.php?action=cache" onclick="return confirm('<?php echo _("Are you sure");?>?');"><?php echo _("Delete Cache");?> <?php echo round((time()-filemtime(CACHE_DATA_FILE))/60,1);?> <?php echo _("minutes")?></a></li>
				<li><a href="optimize.php?action=notconfirmed" onclick="return confirm('<?php echo _("Are you sure");?>?');"><?php echo _("Delete Ads not confirmed in 3 days");?></a></li>
				<li><a href="optimize.php?action=senddeactivate" onclick="return confirm('<?php echo _("Are you sure");?>?');"><?php echo _("Send Deactivation Emails to Posts Older than 1mo");?></a></li>
				<li><a href="optimize.php?action=db" onclick="return confirm('<?php echo _("Are you sure");?>?');"><?php echo _("Optimize Database Tables");?></a></li>
				<li><a href="admin_sitemap.php"><?php echo _("Sitemap");?></a></li>
				<li><a href="phpinfo.php"><?php echo _("PHP Info");?></a></li>
			</ul>
			<blockquote>
				<ul>
					<li><a href="http://j.mp/bLgA8C"><?php echo _("Advertise Here");?></a></li>
				</ul>
				<p><?php echo _("Please think about helping us with a donation. We need your support to maintain this software");?></p>
				<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&amp;business=paypal%40open-classifieds%2ecom&amp;lc=EUR&amp;item_name=Donate%20Open%20Classifieds&amp;amount=5.00&amp;currency_code=EUR&amp;no_note=1&amp;no_shipping=2&amp;rm=1&amp;weight_unit=lbs&amp;bn=PP%2dBuyNowBF%3abtn_buynowCC_LG%2egif%3aNonHosted" target="_blank">
					<img src="https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" border="0" alt="" />
				</a>
			</blockquote>
		</div>
	<?php } ?>
		<div class="clear"></div>
	</div>
	<div id="footer">
		<?php 
		////////////////////////////////////////////////////////////
		//Common footer for admin
		////////////////////////////////////////////////////////////
		$ocdb->closeDB();
		$ocdb->returnDebug();
		echo "<p>".$ocdb->getQueryCounter()._("queries generated in").round((microtime(true)-$app_time),3)."s - ".$ocdb->getQueryCounter("cache")." "._("queries cached")."</p>";
		?>
		<ul>
			<li class="credits">&copy; 2010 <a title="Open Classifieds | Free Advertisements Classifieds web | PHP + MYSQL" href="http://open-classifieds.com/">Open Classifieds</a> <strong>version <?php echo VERSION;?></strong></li>
			<li class="copyright"><a href="http://www.gnu.org/licenses/gpl.html" target="_blank">GNU General Public License</a></li>
		</ul>
	</div>
</div>
</body>
</html>