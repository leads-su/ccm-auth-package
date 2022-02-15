<?php

namespace ConsulConfigManager\Auth\Exceptions;

use Throwable;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

/**
 * Class ExceptionHandler
 * @package ConsulConfigManager\Auth\Exceptions
 */
class ExceptionHandler extends Handler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function () {
            //
        });

        $this->renderable(function (Throwable|AuthenticationException|UnauthorizedException $throwable) {
            if (
                (
                    $throwable instanceof RouteNotFoundException &&
                    $throwable->getMessage() === 'Route [login] not defined.'
                ) ||
                // @codeCoverageIgnoreStart
                $throwable instanceof AuthenticationException ||
                $throwable instanceof UnauthorizedException
                // @codeCoverageIgnoreEnd
            ) {
                return $this->renderNotAuthorizedException($throwable);
            }
        });
    }

    /**
     * Render "Not Authorized" exception
     * @param Throwable $throwable
     * @return JsonResponse
     */
    private function renderNotAuthorizedException(Throwable $throwable): JsonResponse
    {
        return response_error([
            'code'      =>  $throwable->getCode(),
            'message'   =>  $throwable->getMessage(),
        ], 'You are not authorized to access this route', Response::HTTP_UNAUTHORIZED);
    }
}
