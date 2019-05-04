<?php

namespace Resta\Core\Tests\UrlComponent;

use Resta\Core\Tests\AbstractTest;
use Resta\Url\UrlVersionIdentifier;

class UrlComponentTest extends AbstractTest
{
    /**
     * @return void|mixed
     */
   public function testUrlComponent()
   {
       $url = static::$app->get('url');

       $url->getRequestPathInfo(['','core','test']);

       $url->handle();

       $params = $url->urlList;

       $this->assertTrue(true,is_array($params));
       $this->assertSame(5,count($params));
       $this->assertSame("Core",$params['project']);
       $this->assertSame("V1",$params['version']);
       $this->assertSame("Test",$params['endpoint']);
       $this->assertSame("index",$params['method']);
       $this->assertSame([],$params['parameters']);

       $supportedVersions = UrlVersionIdentifier::supportedVersions(['V1']);

       $this->assertTrue(true,in_array($params['version'],$supportedVersions));
       $this->assertFalse(false,in_array('V2',$supportedVersions));
   }


    /**
     * @return void|mixed
     */
    public function testUrlComponent2()
    {
        $url = static::$app->get('url');

        $url->getRequestPathInfo(['','core','V1','test']);

        $url->handle();

        $params = $url->urlList;

        $this->assertTrue(true,is_array($params));
        $this->assertSame(5,count($params));
        $this->assertSame("Core",$params['project']);
        $this->assertSame("V1",$params['version']);
        $this->assertSame("Test",$params['endpoint']);
        $this->assertSame("index",$params['method']);
        $this->assertSame([],$params['parameters']);

        $supportedVersions = UrlVersionIdentifier::supportedVersions(['V1']);

        $this->assertTrue(true,in_array($params['version'],$supportedVersions));
        $this->assertFalse(false,in_array('V2',$supportedVersions));

        $supportedVersions = UrlVersionIdentifier::supportedVersions(['V1','V2']);

        $this->assertTrue(true,in_array($params['version'],$supportedVersions));
        $this->assertTrue(true,in_array('V2',$supportedVersions));

    }

    /**
     * @return void|mixed
     */
    public function testUrlComponent3()
    {
        $url = static::$app->get('url');

        $url->getRequestPathInfo(['','core','V1','test','a1','b1','c1']);

        $url->handle();

        $params = $url->urlList;

        $this->assertTrue(true,is_array($params));
        $this->assertSame(5,count($params));
        $this->assertSame("Core",$params['project']);
        $this->assertSame("V1",$params['version']);
        $this->assertSame("Test",$params['endpoint']);
        $this->assertSame("A1",$params['method']);
        $this->assertSame(['A1','B1','C1'],$params['parameters']);
        $this->assertSame('A1',$params['parameters'][0]);
        $this->assertSame('B1',$params['parameters'][1]);
        $this->assertSame('C1',$params['parameters'][2]);

        $supportedVersions = UrlVersionIdentifier::supportedVersions(['V1']);

        $this->assertTrue(true,in_array($params['version'],$supportedVersions));
        $this->assertFalse(false,in_array('V2',$supportedVersions));

        $supportedVersions = UrlVersionIdentifier::supportedVersions(['V1','V2']);

        $this->assertTrue(true,in_array($params['version'],$supportedVersions));
        $this->assertTrue(true,in_array('V2',$supportedVersions));

    }

    /**
     * @return void|mixed
     */
    public function testUrlComponent4()
    {
        $url = static::$app->get('url');

        $url->getRequestPathInfo(['','core','test','a1','b1','c1']);

        $url->handle();

        $params = $url->urlList;

        $this->assertTrue(true,is_array($params));
        $this->assertSame(5,count($params));
        $this->assertSame("Core",$params['project']);
        $this->assertSame("V1",$params['version']);
        $this->assertSame("Test",$params['endpoint']);
        $this->assertSame("A1",$params['method']);
        $this->assertSame(['A1','B1','C1'],$params['parameters']);
        $this->assertSame('A1',$params['parameters'][0]);
        $this->assertSame('B1',$params['parameters'][1]);
        $this->assertSame('C1',$params['parameters'][2]);

        $supportedVersions = UrlVersionIdentifier::supportedVersions(['V1']);

        $this->assertTrue(true,in_array($params['version'],$supportedVersions));
        $this->assertFalse(false,in_array('V2',$supportedVersions));

        $supportedVersions = UrlVersionIdentifier::supportedVersions(['V1','V2']);

        $this->assertTrue(true,in_array($params['version'],$supportedVersions));
        $this->assertTrue(true,in_array('V2',$supportedVersions));

    }


    /**
     * @return void|mixed
     */
    public function testUrlComponent5()
    {
        $url = static::$app->get('url');

        $url->getRequestPathInfo(['','core']);

        $url->handle();

        $params = $url->urlList;

        $this->assertTrue(true,is_array($params));
        $this->assertSame(5,count($params));
        $this->assertSame("Core",$params['project']);
        $this->assertSame("V1",$params['version']);
        $this->assertSame(NULL,$params['endpoint']);

        $supportedVersions = UrlVersionIdentifier::supportedVersions(['V1']);

        $this->assertTrue(true,in_array($params['version'],$supportedVersions));
        $this->assertFalse(false,in_array('V2',$supportedVersions));

        $supportedVersions = UrlVersionIdentifier::supportedVersions(['V1','V2']);

        $this->assertTrue(true,in_array($params['version'],$supportedVersions));
        $this->assertTrue(true,in_array('V2',$supportedVersions));

    }
}