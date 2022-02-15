<?php

namespace ConsulConfigManager\Auth\Test\Unit\UseCases\Logout;

use Throwable;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use ConsulConfigManager\Auth\Test\TestCase;
use ConsulConfigManager\Auth\UseCases\Logout\LogoutInputPort;
use ConsulConfigManager\Auth\UseCases\Logout\LogoutInteractor;
use ConsulConfigManager\Auth\UseCases\Logout\LogoutRequestModel;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Auth\Presenters\Logout\LogoutHttpPresenter;

/**
 * Class LogoutInteractorTest
 * @package ConsulConfigManager\Auth\Test\Unit\UseCases\Logout
 */
class LogoutInteractorTest extends TestCase
{
    /**
     * @return void
     */
    public function testShouldPassIfInitializedCorrectly(): void
    {
        $this->assertInstanceOf(LogoutInputPort::class, $this->createInteractor());
    }

    /**
     * @return void
     * @throws Throwable
     */
    public function testShouldPassIfNotAuthorizedReturned(): void
    {
        $interactor = $this->createInteractor();
        $data = $interactor->logout(new LogoutRequestModel(new Request()));
        $this->assertInstanceOf(HttpResponseViewModel::class, $data);
        $this->assertSame(Response::HTTP_UNAUTHORIZED, $data->getResponse()->getStatusCode());
    }

    /**
     * Create interactor instance
     * @return LogoutInteractor
     */
    private function createInteractor(): LogoutInteractor
    {
        $presenter = new LogoutHttpPresenter();
        return new LogoutInteractor($presenter);
    }
}
