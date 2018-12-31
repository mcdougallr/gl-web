<?php
include ("log-header.php"); 

?>

<link rel="stylesheet" href="_edit.css">

<h1>Edit Gould Lake Newsfeed</h1>
<form id="food-day-form" class="pure-form pure-form-stacked" method=post action="process-news.php">			
	<div class="pure-g">			
		<?php
		$newsquery = "SELECT * FROM gl_news";
		foreach ($conn->query($newsquery) as $news)
		{ ?>
			<div class="pure-u-1 pure-u-sm-1-3">
				<div class="input-padding">
					<label>Message #<?php print $news['news_id']; ?></label>
					<textarea class="pure-input-1" name="news_info<?php print $news['news_id']; ?>" onkeypress="return noenter()"><?php print $news['news_info']; ?></textarea>
					<label>Link #<?php print $news['news_id']; ?></label>
					<input class="pure-input-1" type="text" name="news_link<?php print $news['news_id']; ?>" onkeypress="return noenter()" value="<?php print $news['news_link']; ?>">				
				</div>
			</div>		
		<?php } ?>
		<div class="pure-u-1" style="text-align: center"><button id="complete" class="plaintext-button" type="submit">update</button></div>
	</div>
</form>		