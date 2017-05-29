<div class="container">
	<div class="row">
		<div class="col-xs-12 col-sm-8">
			<div class="row list-header">
				<?=ucfirst($_['model_name'])?> Entries
			</div>
			<?php foreach($_['models'] as $model) : ?>
				<a href="/admin/<?=$_['model_name']?>/edit/<?=$model[0]?>">
				<div class="row list-item">
					<div class="col-xs-2">
						<?=$model[0]?>
					</div>
					<div class="col-xs-10">
						<?=$model[1]?>
					</div>
				</div>
				</a>
			<?php endforeach; ?>
		</div>

		<div class="col-xs-12 col-sm-4">

		</div>
	</div>
</div>