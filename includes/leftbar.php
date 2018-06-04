<div class="left-bar">
	<li><a href="<?php echo $siteUrl; ?>/dashboard">
		<label class="menu-icon"><i class="ion-ios-speedometer"></i> </label><span class="text">Dashboard</span>
	</a></li>
	<li><a href="<?php echo $siteUrl; ?>/notifications" class="notification-holder">
		<label class="menu-icon"><i class="ion-ios-bell"></i> </label><span class="text">Notifications</span>
		<?php if($notificationsCount > 0){ ?>
		<span class="label label-danger btn-round bubble"><?php echo $notificationsCount; ?></span>
		<?php } ?>
	</a></li>
	<li><a href="<?php echo $siteUrl; ?>/documents">
		<label class="menu-icon"><i class="ion-document-text"></i> </label><span class="text">Documents</span>
	</a></li>
	<li><a href="<?php echo $siteUrl; ?>/requests">
		<label class="menu-icon"><i class="ion-fireball"></i> </label><span class="text">Signing Requests</span>
	</a></li>
	<?php if($role == 'admin' or $role == 'superadmin'){ ?>
	<li><a href="<?php echo $siteUrl; ?>/team">
		<label class="menu-icon"><i class="ion-ios-people"></i> </label><span class="text">Team</span>
	</a></li>
	<?php } ?>
	<?php if($role == 'superadmin'){ ?>
	<li><a href="<?php echo $siteUrl; ?>/companies">
		<label class="menu-icon"><i class="ion-ios-flower"></i> </label><span class="text">Companies</span>
	</a></li>
	<?php } ?>
	<li><a href="<?php echo $siteUrl; ?>/signature">
		<label class="menu-icon"><i class="ion-edit"></i> </label><span class="text">Signature</span>
	</a></li>
	<li><a href="<?php echo $siteUrl; ?>/settings">
		<label class="menu-icon"><i class="ion-gear-a"></i> </label><span class="text">Settings</span>
	</a></li>
</div>