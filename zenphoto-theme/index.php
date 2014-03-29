<?php include('header.php'); ?>


<div id="frontcontent">


	<div>

	
		<div id="introwrapper">
			<h2>Media website management the easy way!</h2>
			<ul id="featureicons">
				<li><span class="icon-image"></span><h3>Images</h3></li>
				<li><span class="icon-music"></span><h3>Audio</h3></li>
				<li><span class="icon-film"></span><h3>Video</h3></li>
				<li><span class="icon-file"></span><h3>Blog</h3></li>
				<li><span class="icon-file2"></span><h3>Pages</h3></li>
				<li><span class="icon-lock"></span><h3>User rights</h3></li>
				<li><span class="icon-users"></span><h3>Multi user</h3></li>
				<li><span class="icon-earth"></span><h3>Multilingual</h3></li>
			</ul>
			<div id="introtext">
			<p>Zenphoto is a standalone CMS for multimedia focused websites. Easy to use but having all the features there when you need them (but out of the way if you do not.)</p>
			<p>Zenphoto features support for various media formats and integrated <a href="/news/zenpage-a-cms-plugin-for-zenphoto">blog and custom pages</a>. Zenphoto is the ideal CMS for personal websites of illustrators, artists, designers, photographers, film makers and musicians.</p>
			<p class="buttons"><a href="/news/features">Read about all features</a></p>
			</div>
		</div>


	</div> <!-- slideshow end -->

	<?php
	$downloadroot = 'https://github.com/zenphoto/zenphoto/archive/';
	$zp_dl_version = '';
	$zp_version = '';
	$zp_dl_pubdate = '';
	$zp_dev_version = '';

	$cat = new ZenpageCategory('release');
	$latestnews = $cat->getArticles(1, 'published', true, 'date', 'desc', false);
	$newsobj = new ZenpageNews($latestnews[0]['titlelink']);
	$zp_dl_version = $newsobj->getTitlelink();
	$zp_version = $newsobj->getTitle();
	$zp_dl_pubdate = zpFormattedDate(DATE_FORMAT, strtotime($newsobj->getDatetime()));
	$zp_dev = array_slice(explode('.', ($current_version = substr($zp_version, strpos($zp_dl_version, '-') + 1)) . '.0.0.0'), 0, 3);

	//WARNING: the following code presumes that we march consistently through release numbers without any breaks!!!!
	$carry = 1;
	while (!empty($zp_dev)) {
		$v = array_pop($zp_dev) + $carry;
		if ($v % 10) {
			$carry = 0;
		} else {
			$v = 0;
		}
		$zp_dev_version = $v . '.' . $zp_dev_version;
	}
	$zp_dev_version = substr($zp_dev_version, 0, -1);
	$zp_dev_archive = '/archive/' . $zp_dev_version . '.zip';
	?>
	<div class="downloadwrapper">


		<div class="buttonzip">
			<a	href="<?php echo $downloadroot; ?><?php echo $zp_dl_version; ?>.zip" title="Download ZenPhoto in zip format">
				<img src="<?php echo $_zp_themeroot; ?>/images/downloadbutton.png" alt="" /><span>Download (.zip)</span>
			</a>
		</div>

		<div class="buttontar">
			<a	href="<?php echo $downloadroot; ?><?php echo $zp_dl_version; ?>.tar.gz"	title="Download ZenPhoto in tar format">
				<img src="<?php echo $_zp_themeroot; ?>/images/downloadbutton.png" alt="" /><span>Download (.tar.gz)</span>
			</a>
		</div>

		<p class="version_info">
			<strong><?php echo $zp_version; ?></strong> (<?php echo $zp_dl_pubdate; ?>) | License: <a	href="http://www.gnu.org/licenses/gpl-2.0.html">GPL v.2 or later</a> | <a
				href="http://www.zenphoto.org/news/installation-and-upgrading" title="Installation, upgrading and requirements">Installation,	upgrading and requirements</a>
		</p>
	</div> <!--download box div wrapper end -->

	<div class="infobox-links-r">
		<?php printSearchForm(); ?>
		<ul class="downloadlinks">
			<li><a href="/news/category/changelog" title="Zenphoto changelog">Changelog</a></li>
			<li><a href="https://github.com/zenphoto/zenphoto/archive/master.zip" title="Zenphoto 1.4.6 Beta build on GitHub">1.4.6 Beta (GitHub)</a></li>
			<!--
			<li><a href="https://github.com/zenphoto/zenphoto<?php echo $zp_dev_archive; ?>" title="Zenphoto development on Github"><?php echo $zp_dev_version; ?> Development builds (GitHub)</a></li> -->
		</ul>
	</div>

	<br style="clear: both" />
	<div class="column-l">
		<div class="infobox">
			<?php
			$number = '';
			$albtitle = '';
			$thumb = '';
			$imagelink = '';
			$latestalbum = getAlbumStatistic(1, 'latest', 'theme');
			if (!empty($latestalbum)) {
				$album = $latestalbum[0];
				$tempalbum = newAlbum($album['folder']);
				if ($tempalbum->getNumImages() != 0) {
					$firstimage = $tempalbum->getImages(1); // need only the first so don't get all
					$firstimage = $firstimage[0];
					$image = newImage($tempalbum, $firstimage);
					$thumb = $image->getCustomImage(NULL, 238, 128, 238, 128, NULL, NULL, TRUE);
					$imagelink = $image->getLink();
				}
				$albtitle = $tempalbum->getTitle();
				$themes = newAlbum('theme');
				$number = $themes->getNumAlbums();
			}
			?>
			<h3>View available themes <small>(<?php echo $number; ?>)</small></h3>
			<a href="<?php echo $imagelink; ?>"
				 title="<?php echo html_encode($albtitle); ?>"> <img
					src="<?php echo html_encode($thumb); ?>"
					alt="<?php echo html_encode($albtitle); ?>" /> </a> <br />
			<p>Latest addition: <a href="<?php echo $imagelink; ?>"
														 title="<?php echo html_encode($albtitle); ?>"><?php echo $albtitle; ?></a></p>
		</div>

		<div class="infobox">
			<h3>Latest news</h3>
			<ul class="downloadlinks">
				<?php
				if (empty($category)) {
					$latest = $_zp_zenpage->getArticles(5, NULL, true, NULL, 'DESC', true, NULL);
				} else {
					$catobj = new ZenpageCategory($category);
					$latest = $catobj->getArticles(5, NULL, true, NULL, 'DESC', true);
				}

				if (empty($latestnews)) {
					echo 'No latest news';
				} else {
					foreach ($latestnews as $news) {
						$newsobj = new ZenpageNews($news['titlelink']);
						$category = '';
						if ($newsobj->inNewsCategory('extensions'))
							$category = ' (extensions)';
						if ($newsobj->inNewsCategory('news'))
							$category = ' (news)';
						if ($newsobj->inNewsCategory('user-guide'))
							$category = ' (user guide)';
						echo '<li><a href="' . $newsobj->getLink() . '" title="' . html_encode($newsobj->getTitle()) . '">' . $newsobj->getTitle() . '</a> ' . $category . '</li>';
					}
				}
				?>
			</ul>
		</div>

		<div class="infobox">
			<h3>Found a bug?!</h3>
			<p><img src="<?php echo $_zp_themeroot; ?>/images/icon-bugtracker.png" class="imgfloat-left" alt="" /> Please report any bugs you find with a detailed description via tickets at the <a href="https://github.com/zenphoto/zenphoto/issues?state=open" title="Bugtracker (GitHub)">Zenhoto bugtracker on GitHub.com</a>.
			</p>
		</div>

		<div class="infobox">
			<h3>Share!</h3>
			<?php zp_printAddthis(); ?>
		</div>

		<div class="infobox">
			<h3>Like using Zenphoto? Donate!</h3>
			<p>Your support helps pay for this server, and helps development of zenphoto. Thank you!</p>
			<p>Visit the <a href="http://www.zenphoto.org/pages/donations">donations page</a></p>
		</div>


	</div><!-- infobox-l end -->

	<div class="column-m">

		<div class="infobox">
			<h3>Screenshots and screencasts!</h3>
			<?php
			$image = '';
			$thumb = '';
			$imgtitle = '';
			$thumb = '';
			$latestimage = getImageStatistic(1, 'latest', 'screenshots', true);
			if (!empty($latestimage)) {
				$image = $latestimage[0];
				$imgtitle = $image->getTitle();
				$thumb = $image->getCustomImage(NULL, 238, 128, 238, 128, NULL, NULL, TRUE);
			}
			?>
			<a href="<?php echo WEBPATH; ?>/screenshots/"	title="<?php echo html_encode($imgtitle); ?>">
				<img src="<?php echo html_encode($thumb); ?>" alt="<?php echo html_encode($imgtitle); ?>" /> </a>
			<br />
			<p>
				<a href="<?php echo WEBPATH; ?>/screenshots/" title="Zenphoto screenshots and screencasts">Learn about using Zenphoto</a>
			</p>
		</div>

		<?php zp_printSponsorAds(true); ?>





	</div><!-- infobox-m end -->

	<div class="column-r">

		<div class="infobox">
			<?php
			$image = '';
			$imgtitle = '';
			$imglink = '';
			$thumb = '';
			$latestimage = getImageStatistic(1, 'latest', 'showcase', false);
			if (!empty($latestimage)) {
				$image = $latestimage[0];
				$showcase = newAlbum('showcase');
				$number = $showcase->getNumImages();
				$imgtitle = $image->getTitle();
				$imglink = $image->getLink();
				$thumb = $image->getCustomImage(NULL, 238, 128, 238, 128, NULL, NULL, TRUE);
			}
			?>
			<h3>Visit the showcase! <small>(<?php echo $number; ?> entries)</small></h3>
			<a href="<?php echo html_encode($imglink); ?>"	title="<?php echo html_encode($imgtitle); ?>">
				<img src="<?php echo html_encode($thumb); ?>" alt="<?php echo html_encode($imgtitle); ?>" />
			</a>
			<br />
			<p>Latest addition: <a href="<?php echo html_encode($imglink); ?>"	title="<?php echo html_encode($imgtitle); ?>"><?php echo $imgtitle; ?></a>
			</p>
		</div>

		<div class="infobox">
			<h3>Need help? Visit the forum!</h3>
			<p><img src="<?php echo $_zp_themeroot; ?>/images/icon-forum.png"	class="imgfloat-left" alt="" /> You can post help requests and discuss everything Zenphoto related in the
				<a href="http://www.zenphoto.org/support" title="Zenphoto forums">zenphoto support forums</a> (Registration required for posting). Please do not e-mail us asking for help. Thanks!
			</p>
			<br />
			<h3>Need project help?</h3>
			<p>Visit the <a href="http://www.zenphoto.org/pages/paid-support">paid support page</a>.
			</p>
		</div>

		<div class="infobox">
			<h3>Get involved!</h3>
			<p>You would like to contribute? You don't need to be a programmer! <a href="http://www.zenphoto.org/pages/get-involved" title="Get involved!">Read here what you can do for Zenphoto!</a>
			</p>
		</div>



	</div><!-- infobox-r end -->


	<br style="clear: both" />

</div> <!-- frontcontent end -->



<div id="ads" style="margin-left: 40px">
	<script type="text/javascript">
		<!--
		google_ad_client = "pub-7903690389990760";
		google_ad_width = 728;
		google_ad_height = 90;
		google_ad_format = "728x90_as";
		google_ad_type = "text";
		google_ad_channel = "";
		google_color_border = "CCCCCC";
		google_color_bg = "FFFFFF";
		google_color_link = "000000";
		google_color_url = "666666";
		google_color_text = "333333";//-->
	</script>

	<script type="text/javascript"
	src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>

	<!-- The main column ends  --> <?php include('footer.php'); ?>
