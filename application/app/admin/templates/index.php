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

            <?php $i = 0; foreach($_['site_classes'] as $site_class) : ?>

                <?php if ($i % 2 === 0) : ?>
                    <div class="row list-header">
                        <?=ucfirst($site_class)." Models"?>
                    </div>
                <?php else: ?>
                        <?php foreach($site_class as $site) : ?>
                        <a href="<?=$site?>/view/">
                            <div class="row list-item">
                                <?=$site?>
                            </div>
                        </a>
                    <?php endforeach; ?>
                    <?php endif; ?>
                
                
                
                
            <?php $i++;endforeach; ?>
        </div>

        <div class="col-xs-12 col-sm-4">

        </div>
    </div>
</div>