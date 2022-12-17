<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
// use MissingShopDomainException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
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
        // $this->renderable(function (MissingShopDomainException $e) {
        //     return redirect()->route('apps.shopify.no-auth');
        // });
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof \Osiset\ShopifyApp\Exceptions\MissingShopDomainException) {
            return response()->view("login", [], 500);
        }

        return parent::render($request, $exception);
    }
}
