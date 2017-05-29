<?php if ($_['updated'] === true) : ?>
<div class="alert alert-success center">Item Updated Successfully</div>
<?php endif; ?>
<div class="container">
	<form action="" method="POST">
	<div class="row">
		<div class="col-xs-12 col-sm-8">
			<div class="row list-header">
				<a href="/admin/<?=ucfirst($_['model_name'])?>/view/"><?=ucfirst($_['model_name'])?></a> Update
			</div>
			<?php $i = 0; ?>
			<?php foreach ($_['structure'] as $structureType) :  ?>

				<div class="row list-item">
					<div class="col-xs-4">
						<label style="float: right;" class="form-label"><?=$structureType['name']?>:</label>
					</div>
					<div class="col-xs-8">
						<?php if ($structureType['native_type'] == "LONG") : ?>
							<input class="form-control" type="number" value="<?=$_['models'][0][$i]?>" name="<?=$structureType['name']?>">
						<?php endif;?>

						<?php if ($structureType['native_type'] == "VAR_STRING") : ?>
							<input class="form-control" type="text" value="<?=$_['models'][0][$i]?>" name="<?=$structureType['name']?>">
						<?php endif;?>

						<?php if ($structureType['native_type'] == "BLOB") : ?>
							<textarea class="form-control" name="<?=$structureType['name']?>"><?=$_['models'][0][$i]?></textarea>
							
						<?php endif;?>


					</div>
				</div>
			<?php $i++; endforeach; ?>
			
		</div>

		<div class="col-xs-12 col-sm-4">

		</div>
	</div>
	<div class="row center">
		<button class="theme-btn" type="submit">
			Update
		</button>
	</div>
	</form>
</div>