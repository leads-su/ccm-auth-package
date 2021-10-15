<?php namespace ConsulConfigManager\Auth\Exceptions;

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
class ExceptionHandler extends Handler {

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
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (Throwable $throwable, Request $request) {
            if (
                $throwable instanceof RouteNotFoundException &&
                $throwable->getMessage() === 'Route [login] not defined.'
            ) {
                return $this->renderNotAuthorizedException($throwable);
            }
        });

        $this->renderable(function (AuthenticationException $exception, Request $request) {
            return $this->renderNotAuthorizedException($exception);
        });

        $this->renderable(function (UnauthorizedException $exception, Request $request) {
            return response_error([
                'code'      =>  $exception->getStatusCode(),
                'message'   =>  $exception->getMessage()
            ], $exception->getMessage(), Response::HTTP_UNAUTHORIZED);
        });
    }

    /**
     * Render "Not Authorized" exception
     * @param Throwable $exception
     * @return JsonResponse
     */
    private function renderNotAuthorizedException(Throwable $exception): JsonResponse {
        return response_error([
            'code'      =>  $exception->getCode(),
            'message'   =>  $exception->getMessage()
        ], 'You are not authorized to access this route', Response::HTTP_UNAUTHORIZED);
    }
}
