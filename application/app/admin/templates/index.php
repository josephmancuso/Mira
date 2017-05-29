<div class="container">
	<div class="row">
		<div class="col-xs-12 col-sm-8">
			<div class="row list-header">
				Admin Models
			</div>
			<div class="row list-item">
				Users
			</div>
			<div class="row list-item">
				Models
			</div>

			<div class="row list-header">
				Site Models
			</div>
			<?php foreach($_['site_classes'] as $site_class) : ?>
				<a href="<?=$site_class?>/view/">
					<div class="row list-item">
						<?=ucfirst($site_class)?>
					</div>
				</a>
			<?php endforeach; ?>
		</div>

		<div class="col-xs-12 col-sm-4">

		</div>
	</div>
</div>