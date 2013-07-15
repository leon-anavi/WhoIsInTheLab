<li>
	<div role="user">
		<?php if ($user->hasFacebook): ?>
		<a href="<?php echo $user->facebookLink; ?>"><img role="picture" src="<?php echo $user->facebookPicture; ?>"  height="128" /></a>
		<?php else: ?>
		<a href="<?php echo $user->facebookLink; ?>"><img role="picture" src="img/no-picture.png" height="128" /></a>
		<?php endif; ?>
		<h3 role="name"><?php echo $user->name; ?></h3>
		<ul role="social">
			<?php if ($user->hasTwitter): ?><li role="twitter"><a href="<?php echo $user->twitterLink; ?>"><img src="img/32/twitter.png"/></a></li><?php endif; ?>
			<?php if ($user->hasFacebook): ?><li role="facebook"><a href="<?php echo $user->facebookLink; ?>"><img src="img/32/facebook.png"/></a></li><?php endif; ?>
			<?php if ($user->hasGooglePlus): ?><li role="googlePlus"><a href="<?php echo $user->googlePlusLink; ?>"><img src="img/32/google.png"/></a></li><?php endif; ?>
			<?php if ($user->hasTel): ?><li role="tel"><a href="tel://<?php echo $user->tel; ?>"><?php echo $user->tel; ?></a></li><?php endif; ?>
			<?php if ($user->hasEmail): ?><li role="email"><a href="mailto://<?php echo $user->email; ?>"><?php echo $user->email; ?></a></li><?php endif; ?>
			<?php if ($user->hasWebsite): ?><li role="website"><a href="<?php echo $user->website; ?>"><?php echo $user->website; ?></a></li><?php endif; ?>
		</ul>
	</div>
</li>
