<?php
namespace Concrete\Package\CdnCachePurgeCloudflare\Controller\SinglePage\Dashboard\System\Optimization;

use Core;
use Package;

class Cloudflare extends \Concrete\Core\Page\Controller\DashboardPageController
{
    public function view()
    {
        /** @var \Concrete\Core\Package\Package $pkg */
        $pkg = Package::getByHandle('cdn_cache_purge_cloudflare');
        $authKey = $pkg->getFileConfig()->get('cloudflare.auth_key');
        $authEmail = $pkg->getFileConfig()->get('cloudflare.auth_email');
        $zoneId = $pkg->getFileConfig()->get('cloudflare.zone_id');
        $this->set('authKey', $authKey);
        $this->set('authEmail', $authEmail);
        $this->set('zoneId', $zoneId);
    }

    public function settings_saved()
    {
        $this->set('message', t('Settings saved.'));
        $this->view();
    }

    public function update_settings()
    {
        if ($this->token->validate("update_settings")) {
            if ($this->isPost()) {
                /** @var \Concrete\Core\Utility\Service\Validation\Strings $validator */
                $validator = Core::make('helper/validation/strings');
                $authKey = $this->post('authKey');
                $authEmail = $this->post('authEmail');
                $zoneId = $this->post('zoneId');

                if (!$validator->notempty($authKey)) {
                    $this->error->add(t('Please enter Auth Key.'));
                }
                if (!$validator->notempty($authEmail)) {
                    $this->error->add(t('Please enter Cloudflare account email address.'));
                }
                if (!$validator->notempty($zoneId)) {
                    $this->error->add(t('Please enter Cloudflare Zone ID.'));
                }

                if (!$this->error->has()) {
                    /** @var \Concrete\Core\Package\Package $pkg */
                    $pkg = Package::getByHandle('cdn_cache_purge_cloudflare');
                    $pkg->getFileConfig()->save('cloudflare.auth_key', $authKey);
                    $pkg->getFileConfig()->save('cloudflare.auth_email', $authEmail);
                    $pkg->getFileConfig()->save('cloudflare.zone_id', $zoneId);
                    $this->redirect('/dashboard/system/optimization/cloudflare', 'settings_saved');
                }
            }
        } else {
            $this->error->add($this->token->getErrorMessage());
        }
        $this->view();
    }
}
