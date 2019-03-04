<?php

namespace Statamic\Addons\AardvarkSeo\Tests;

use Mockery;
use Statamic\Addons\AardvarkSeo\Controllers\RedirectsController;
use Statamic\Testing\TestCase;

class RedirectsControllerTest extends TestCase
{
    public function setUp()
    {
        $this->controller = Mockery::mock(RedirectsController::class)->makePartial();
    }

    /**
     * @test
     */
    public function storage_key_is_correctly_set()
    {
        return $this->assertEquals(RedirectsController::STORAGE_KEY, 'seo-redirects');
    }

    /**
     * @test
     */
    public function routes_filepath_is_correctly_set()
    {
        return $this->assertEquals(RedirectsController::ROUTES_FILE, 'routes.yaml');
    }

    /**
     * Test whether an array of data provided by the CP redirects grid
     * will be collected and formatted into redirection categories.
     *
     * @test
     */
    public function data_collection_creates_routes_correctly()
    {
        $processedData = $this->controller->collectRoutesFromData(RedirectsControllerTestFixtures::CP_GRID_REDIRECTS);

        // Check that the two redirect categories have been created
        $this->assertArrayHasKey('redirect', $processedData);
        $this->assertArrayHasKey('vanity', $processedData);

        // Check that the data has been sorted correctly
        $this->assertEquals('/test-1-redirected', $processedData['redirect']['/test-1']);
        $this->assertEquals('/test-2-redirected', $processedData['redirect']['/test-2']);
        $this->assertEquals('/test-3-redirected', $processedData['vanity']['/test-3']);
        $this->assertEquals('/test-4-redirected', $processedData['redirect']['/test-4']);
    }

    /**
     * Test whether potentially infinite redirects will be removed
     * from the array of redirects.
     *
     * @test
     */
    public function potentially_infinite_redirects_get_removed()
    {
        $data = RedirectsControllerTestFixtures::ROUTES_YAML_ARRAY['redirect'];
        $to = '/test-5';
        $processedData = $this->controller->removePotentialInfiniteRedirects($to, $data);
        $this->assertArrayNotHasKey($to, $processedData);
    }

    /**
     * Test that chaining redirects get flattened.
     *
     * @test
     */
    public function chaining_redirects_get_flattened()
    {
        $data = RedirectsControllerTestFixtures::ROUTES_YAML_ARRAY['vanity'];
        $to = '/test-other-redirect';
        $from = '/test-7-redirected';

        // Add the test redirect
        $data[$from] = $to;

        $processedData = $this->controller->removeChainingRedirects($from, $to, $data);

        $this->assertArrayHasKey('/test-7', $processedData);
        $this->assertArrayHasKey('/test-7-redirected', $processedData);
        $this->assertEquals('/test-other-redirect', $processedData['/test-7']);
        $this->assertEquals('/test-other-redirect', $processedData['/test-7-redirected']);
    }
}

class RedirectsControllerTestFixtures
{
    const CP_GRID_REDIRECTS = [
        [
            'source' => '/test-1',
            'target' => '/test-1-redirected',
            'status_code' => '301',
        ],
        [
            'source' => '/test-2',
            'target' => '/test-2-redirected',
            'status_code' => '301',
        ],
        [
            'source' => '/test-3',
            'target' => '/test-3-redirected',
            'status_code' => '302',
        ],
        [
            'source' => '/test-4',
            'target' => '/test-4-redirected',
            'status_code' => '301',
        ],
    ];

    const ROUTES_YAML_ARRAY = [
        'redirect' => [
            '/test-5' => '/test-5-redirected',
            '/test-6' => '/test-6-redirected',
            '/test-8' => '/test-8-redirected',
        ],
        'vanity' => [
            '/test-7' => '/test-7-redirected',
        ],
    ];
}
