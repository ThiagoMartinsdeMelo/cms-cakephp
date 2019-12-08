<?php
namespace App\Test\TestCase\Model\Behavior;

use App\Model\Behavior\UploadRBehavior;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Behavior\UploadRBehavior Test Case
 */
class UploadRBehaviorTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Behavior\UploadRBehavior
     */
    public $UploadR;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->UploadR = new UploadRBehavior();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->UploadR);

        parent::tearDown();
    }

    /**
     * Test initial setup
     *
     * @return void
     */
    public function testInitialization()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
