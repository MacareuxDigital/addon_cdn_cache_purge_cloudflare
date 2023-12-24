<?php defined('C5_EXECUTE') or die("Access Denied.");?>

<form action="<?php echo $this->action('update_settings')?>" method="post">
    <?php echo $this->controller->token->output('update_settings')?>
    <fieldset>
        <legend><?php echo t('Credentials'); ?></legend>
        <div class="form-group">
            <?php echo $form->label('authKey', t('Cloudflare Auth Key')); ?>
            <?php echo $form->text('authKey', $authKey); ?>
        </div>
        <div class="form-group">
            <?php echo $form->label('authEmail', t('Cloudflare Auth Email')); ?>
            <?php echo $form->text('authEmail', $authEmail); ?>
            <p class="help-block"><?php echo t('Enter the API Key and email address of Cloudflare account which you can create from <a href="https://dash.cloudflare.com/profile/api-tokens" target="_blank">Cloudflare dashboard</a>.'); ?></p>
        </div>
        <div class="form-group">
            <?php echo $form->label('zoneId', t('Cloudflare Zone ID')); ?>
            <?php echo $form->text('zoneId', $zoneId); ?>
            <p class="help-block"><?php echo t('Enter Cloudflare Zone ID which zone you want to clear caches. You can find the Zone ID from <<a href="https://dash.cloudflare.com/"  target="_blank">Cloudflare dashboard</a>'); ?></p>
        </div>
    </fieldset>
    <div class="ccm-dashboard-form-actions-wrapper">
        <div class="ccm-dashboard-form-actions">
            <button class="pull-right btn btn-success" type="submit" ><?php echo t('Save')?></button>
        </div>
    </div>
</form>