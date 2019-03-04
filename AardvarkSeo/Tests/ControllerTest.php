<?php

namespace Statamic\Addons\AardvarkSeo\Tests;

use Mockery;
use Statamic\Addons\AardvarkSeo\Controllers\Controller;
use Statamic\Testing\TestCase;

class ControllerTest extends TestCase
{
    public function setUp()
    {
        $this->controller = Mockery::mock(Controller::class)->makePartial();
        $this->testFieldset = ControllerTextFixtures::FIELDSET_CONTENTS;
    }

    /**
     * Test that asset field containers are transformed
     * correctly in order to add the container from config.
     *
     * @test
     */
    public function asset_fields_get_transformed()
    {
        $fields = $this->testFieldset;
        $testContainer = 'test_container';

        $processedFields = $this->controller->transformAssetsFields($fields, $testContainer);

        $this->assertEquals($testContainer, $processedFields['test_assets']['container']);
    }
}

class ControllerTextFixtures
{
    const FIELDSET_CONTENTS = [
        'test_assets' => [
            'type' => 'assets',
            'display' => 'Test Assets',
            'width' => 50,
        ],
    ];
}
