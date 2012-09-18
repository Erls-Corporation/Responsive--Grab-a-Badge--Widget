<div class="lggrababadge">
	<h1>Grab A Badge</h1>
	<h2>Show some love. Easy.</h2>
	<div class="badgeScroller">
	  	<div class="left-grad"></div>
	  	<div class="right-grad"></div>
	  <div class="wrapper">
	    <ul>
		<?php
		$i=0;
		$all_badges=array();
		foreach ($badges as $b) {
			if (!in_array($b->badge_id,$all_badges)){
				echo '<li><img class="badge" src="' . $b->image .'" onclick="ShowBadgeOL(\'017\',\'0\',\'' . $b->badge_id .'\');" /><div class="w1 shadow"></div></li>';
				$all_badges[$i] = $b->badge_id;
				$i++;
			}
		}
		?>
	    </ul>        
	  </div>
	</div>
</div>