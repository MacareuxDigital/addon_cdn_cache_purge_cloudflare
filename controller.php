<?php
namespace Concrete\Package\CdnCachePurgeCloudflare;

use Concrete\Core\Backup\ContentImporter;
use Macareux\CloudflareCache\Cache\CloudflareCache;
use Core;
use Events;
use Package;

/**
 * Class Controller
 * @package Concrete\Package\CdnCachePurgeCloudfront
 */
class Controller extends Package
{
    /**
     * @var string Package handle.
     */
    protected $pkgHandle = 'cdn_cache_purge_cloudflare';

    /**
     * @var string Required concrete5 version.
     */
    protected $appVersionRequired = '8.0.0';

    /**
     * @var string Package version.
     */
    protected $pkgVersion = '0.9.2';

    /**
     * @see https://documentation.concretecms.org/developers/packages/adding-custom-code-to-packages
     *
     * @var string[]
     */
    protected $pkgAutoloaderRegistries = [
        'src' => '\Macareux\CloudflareCache',
    ];

    /**
     * @var bool Remove \Src from package namespace.
     */
    protected $pkgAutoloaderMapCoreExtensions = true;

    /**
     * Returns the translated package description.
     *
     * @return string
     */
    public function getPackageDescription()
    {
        return t("Flushes Cloudflare cache when you click Clear Cache button.");
    }

    /**
     * Returns the translated package name.
     *
     * @return string
     */
    public function getPackageName()
    {
        return t("CDN Cache Purge for Cloudflare");
    }

    /**
     * Startup process of the package.
     */
    public function on_start()
    {
        $cloudflare = $cloudflare = new CloudflareCache();

        Events::addListener('on_cache_flush', function () use ($cloudflare) {
            $base_path = Core::getApplicationRelativePath() . '/';
            $cloudflare->createPurgeRequest([
                $base_path . '*',
            ]);
        });
    }

    /**
     * Install process of the package.
     */
    public function install()
    {
        if (version_compare(PHP_VERSION, $this->phpVersionRequired, '<')) {
            throw new Exception(t('This package requires PHP %s or greater.', $this->phpVersionRequired));
        }
        $pkg = parent::install();
        $ci = new ContentImporter();
        $ci->importContentFile($pkg->getPackagePath() . '/config/dashboard.xml');

        return $pkg;
    }
}
