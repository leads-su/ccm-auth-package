<?php namespace ConsulConfigManager\Auth\Test\Unit\Domain\UseCases\Logout;

use ConsulConfigManager\Auth\Test\TestCase;
use ConsulConfigManager\Auth\Test\ProvidesUsersRepository;
use ConsulConfigManager\Auth\Domain\UseCases\Logout\LogoutInputPort;
use ConsulConfigManager\Auth\Domain\UseCases\Logout\LogoutInteractor;
use ConsulConfigManager\Auth\Domain\Presenters\Logout\LogoutHttpPresenter;

/**
 * Class LogoutInteractorTest
 * @package ConsulConfigManager\Auth\Test\Unit\Domain\UseCases\Logout
 */
class LogoutInteractorTest extends TestCase {
    use ProvidesUsersRepository;

    public function testShouldPassIfInitializedCorrectly(): void {
        $this->assertInstanceOf(LogoutInputPort::class, $this->createInteractor());
    }

    /**
     * Create interactor instance
     * @return LogoutInteractor
     */
    private function createInteractor(): LogoutInteractor {
        $presenter = new LogoutHttpPresenter();
        return new LogoutInteractor($presenter);
    }


}
