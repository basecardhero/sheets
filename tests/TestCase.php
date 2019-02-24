<?php

namespace BaseCardHero\Sheets\Tests;

use \Mockery;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

class TestCase extends PHPUnitTestCase
{
    /**
     * Override parent::tearDown().
     *
     * @see https://github.com/GrahamCampbell/Laravel-TestBench/blob/v1.1.2/src/Traits/HelperTestCaseTrait.php#L57
     */
    public function tearDown()
    {
        parent::tearDown();

        if ($container = Mockery::getContainer()) {
            $this->addToAssertionCount($container->mockery_getExpectationCount());
        }

        Mockery::close();
    }

    /**
     * Create a Mockery spy instance.
     *
     * @param string $class The class name.
     * @param array $parameters Optional constructor parameters.
     *
     * @return \Mockery\MockInterface
     */
    protected function spy($class, $parameters = [])
    {
        return Mockery::spy($class, $parameters);
    }

    /**
     * Create a Mockery partial mock instance.
     *
     * @param string $class The class name.
     * @param array $parameters Optional constructor parameters.
     *
     * @return \Mockery\MockInterface
     */
    protected function partial($class, $parameters = [])
    {
        return $this->mock($class, $parameters)->makePartial();
    }

    /**
     * Create a Mockery mock instance.
     *
     * @param string $class The class name.
     * @param array $parameters Optional constructor parameters.
     *
     * @return \Mockery\MockInterface
     */
    protected function mock($class, $parameters = [])
    {
        return Mockery::mock($class, $parameters);
    }
}
