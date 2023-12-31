<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\DB;
use Throwable;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
     */
    public function register(): void
    {
        $this->renderable(function (Throwable $e) {
            if ($e instanceof WebException) {
                DB::rollBack();
                return back()->withErrors($e->getMessage());
            }
            if ($e instanceof NotFoundHttpException) {
                // Menampilkan halaman 404

                return response()->view('pages.error.404', ['message' => $e->getMessage()], 404);
            }
        });

        $this->renderable(function (\Spatie\Permission\Exceptions\UnauthorizedException $e, $request) {
            return response()->view('pages.error.403', ['message' => $e->getMessage()], 404);
        });
    }
}
