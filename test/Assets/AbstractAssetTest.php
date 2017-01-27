<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ZendTest\View\Assets;

use Zend\View\Assets\Asset;
use Zend\View\Assets\Alias;
use Zend\View\Assets\AbstractAsset;
use Zend\View\Assets\AssetsManager;

/**
 * @group      Zend_View
 */
class AbstractAssetTest extends \PHPUnit_Framework_TestCase
{
    public function testNormalizeName()
    {
        $this->assertEquals(
            'foo/bar/baz.css',
            AbstractAsset::normalizeName('\foo/bar\baz.css')
        );
        $this->assertEquals(
            'http://foo/bar/baz.css',
            AbstractAsset::normalizeName('http://foo\bar\baz.css')
        );
        $this->assertEquals(
            'prefix::foo/bar/baz.css',
            AbstractAsset::normalizeName('prefix::/foo\bar\baz.css')
        );
        $this->assertEquals(
            'prefix::foo/bar/baz.css',
            AbstractAsset::normalizeName(['prefix', '\foo\bar/baz.css'])
        );
        $this->assertEquals(
            'foo/bar/baz.css',
            AbstractAsset::normalizeName([null, '\foo\bar/baz.css'])
        );
    }

    public function testFactory()
    {
        $assetsManager = new AssetsManager();
        $asset = AbstractAsset::factory('foo', 'bar', $assetsManager);
        $this->assertInstanceOf(Asset::class, $asset);
        $this->assertEquals('bar', $asset->getSource());

        $asset = AbstractAsset::factory('foo', ['source' => 'bar'], $assetsManager);
        $this->assertInstanceOf(Asset::class, $asset);
        $this->assertEquals('bar', $asset->getSource());

        $asset = AbstractAsset::factory('foo', [], $assetsManager);
        $this->assertInstanceOf(Asset::class, $asset);
        $this->assertEquals('foo', $asset->getName());

        $alias = AbstractAsset::factory('foo', ['assets' => 'bar'], $assetsManager);
        $this->assertInstanceOf(Alias::class, $alias);
        $this->assertEquals('foo', $alias->getName());
        $this->assertEquals(
            new Asset('bar'),
            $alias->get('bar')
        );
    }
}
