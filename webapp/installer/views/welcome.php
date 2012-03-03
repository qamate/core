    <h2 class="target">Welcome</h2>
    <form action="index.php" method="POST">
	<div class="form-table">
	    <?php if( $error ): ?>
	    <p>Check the next requirements:</p>
	    <div class="requirements_help">
		<p><b>Requirements help:</b></p>
		<ul>
	<?php
	$requirements = get_requirements();
	foreach( $requirements as $req ): ?>
		<?php if( !$req['check'] ): ?>
			<li><?php echo $req['solution']; ?></li>
		<?php endif; ?>
	<?php endforeach; ?>
		    <li><a href="http://forums.opensourceclassifieds.org/">Need more help?</a></li>
		</ul>
	    </div>
	    <?php else: ?>
	    <p>All right! All the requirements have met:</p>
	    <?php endif; ?>
	    <ul>
	    <?php foreach( $requirements as $k => $v): ?>
		<li><?php echo $k; ?> <img src="<?php echo osc_get_absolute_url(); ?>/library/images/<?php echo $v ? 'tick.png' : 'cross.png'; ?>" alt="" title="" /></li>
	    <?php endforeach; ?>
	    </ul>
	    <div class="more-stats">
		<input type="checkbox" name="ping_engines" id="ping_engines" checked="checked" value="1"/>
		<label for="ping_engines">
		    Allow my site to appear in search engines like Google.
		</label>
		<br/>
		<input type="checkbox" name="save_stats" id="save_stats" value="1"/>
		<input type="hidden" name="step" value="2" />
		<label for="save_stats">
		    Help make OpenSourceClassifieds better by automatically sending usage statistics and crash reports to OpenSourceClassifieds.
		</label>
	    </div>
	</div>
	<?php if( $error ): ?>
	<p class="margin20">
	    <input type="button" class="button" onclick="document.location = 'index.php?step=1'" value="Try again" />
	</p>
	<?php else: ?>
	<p class="margin20">
	    <input type="submit" class="button" value="Run the install" />
	</p>
    <?php endif; ?>
    </form>

