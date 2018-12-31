<?php 
	$page_title = "Home";
	include($_SERVER['DOCUMENT_ROOT'].'/header.php'); 
	
	session_start();
	$db = "outed";
	include_once ($_SERVER['DOCUMENT_ROOT'].'/secure/shared/dbconnect.php');
?>
	<!--   Content -->
	<div id="gl-main">
		<div id="gl-wrapper">
			<div id="main-title">Welcome to the <div class="return"></div>Gould Lake Outdoor Centre</div>
			<div id="main-text">
				<p>The Gould Lake Outdoor Centre provides environmental and experiential education programs for elementary and secondary students.
				During the school year, classes participate in outdoor programs ranging from pond studies to canoeing and from winter cookouts to team building. 
				In the summer, Gould Lake provides curriculum-based credit programs for students delivered through canoe, hiking and kayak trips.</p>
			</div><div id="news-title">
					GL News Feed
				</div>
			<div  id="gl-news">				
				<div id="news-text" style="border: 1px #222 solid;">
					<?php 
						$count = 1;
						$news_query = "SELECT * FROM gl_news";
						foreach ($conn->query($news_query) as $news) {?>
							<div class="news-item-wrapper"
							<?php
							if ($count > 2){print " style=\"border-right: none;\"";}
							if ($news['news_link'] != ""){
								print "><div class=\"news-item\"><a href=\"".$news['news_link']."\" target=\"_blank\">".$news['news_info']."</a></div>";
							}
							else {
								print "><div class=\"news-item\">".$news['news_info']."</div>";
							}?>
							</div>
							<hr class="news-line" />
						<?php
						$count++;
						}		
					?>	
				</div>
			</div>
			<div class="slider-container">
				<!-- Caption Style -->
				<script type="text/javascript" src="/scripts/slider-master/jssor.js"></script>
				<script type="text/javascript" src="/scripts/slider-master/jssor.slider.js"></script>
				<script type="text/javascript" src="/scripts/slider-master/gl-slide-options.js"></script>
				<!-- Jssor Slider Begin -->
				<div id="slider1_container">
					<!-- Slides Container -->
					<div u="slides" class="gl-slider-slides">
						<!-- Slide -->
						<div>
							<img u="image" src="courses/img/1.jpg" />
							<div u="caption" t="FADE" t2="MCLIP|B" class="gl-slider-caption">
								<div class="gl-slider-caption-box"></div>
								<div class="gl-slider-caption-text">Support Friends of Outreach - Our Charitable Trust</div>
							</div>
						</div>
						<!-- Slide -->
						<div>
							<img u="image" src="courses/img/2.jpg" />
							<div u="caption" t="FADE" t2="MCLIP|B" class="gl-slider-caption">
								<div class="gl-slider-caption-box"></div>
								<div class="gl-slider-caption-text">The Gould Lake Barn - Thanks for the pic Mia Kilborn!</div>
							</div>
						</div>
						<!-- Slide -->
						<div>
							<img u="image" src="courses/img/3.jpg" />
							<div u="caption" t="FADE" t2="MCLIP|B" class="gl-slider-caption">
								<div class="gl-slider-caption-box"></div>
								<div class="gl-slider-caption-text">Summer Registration begins December 1st.</div>
							</div>
						</div>
						<!-- Slide -->
						<div>
							<img u="image" src="courses/img/4.jpg" />
							<div u="caption" t="FADE" t2="MCLIP|B" class="gl-slider-caption">
								<div class="gl-slider-caption-box"></div>
								<div class="gl-slider-caption-text">Join us for a summer in the wilderness.</div>
							</div>
						</div>
						<!-- Slide -->
						<div>
							<img u="image" src="courses/img/5.jpg" />
							<div u="caption" t="FADE" t2="MCLIP|B" class="gl-slider-caption">
								<div class="gl-slider-caption-box"></div>
								<div class="gl-slider-caption-text">Our Outdoor Classroom</div>
							</div>
						</div>
						<!-- Slide -->
						<div>
							<img u="image" src="courses/img/6.jpg" />
							<div u="caption" t="FADE" t2="MCLIP|B" class="gl-slider-caption">
								<div class="gl-slider-caption-box"></div>
								<div class="gl-slider-caption-text">Join us in the wilderness</div>
							</div>
						</div>							
					</div>
					<span u="arrowleft" class="jssora16l" style="width: 22px; height: 36px; top: 123px; left: 18px;"></span>
					<span u="arrowright" class="jssora16r" style="width: 22px; height: 36px; top: 123px; right: 18px"></span>
				</div>
			</div>
			<div id="siteup"><A HREF="http://www.SiteUptime.com/">
<IMG SRC="http://www.siteuptime.com/images/Siteuptime-Button-Blue.gif" BORDER="0" WIDTH="88" HEIGHT="31" ALT="SiteUptime Web Site Monitoring Service"></div>
		</div>
		<div id="footer-padding"></div>
	</div>   
</div>	
<?php include($_SERVER['DOCUMENT_ROOT'].'/footer.php'); ?>
