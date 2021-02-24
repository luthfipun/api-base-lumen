<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function render($request, Exception $exception)
    {
        if($exception instanceof ValidationException){
            return $this->respondWithValidationError($exception);
        }

        if ($exception instanceof UnauthorizedHttpException) {
            return $this->respondWithUnAuthorize();
        }

        if ($exception instanceof NotFoundHttpException){
            return $this->respondNotFound();
        }

        return parent::render($request, $exception);
    }

    protected function respondNotFound() {
        return response()->json([
            'code' => 404,
            'message' => 'Not found',
            'data' => []
        ]);
    }

    private function respondWithValidationError(Exception $exception)
    {
        return response()->json([
            'code' => 400,
            'message' => $this->errorValidation($exception)["0"],
            'data' => []
        ]);
    }

    private function respondWithUnAuthorize()
    {
        return response()->json([
            'code' => 401,
            'message' => 'Unauthorized',
            'data' => []
        ]);
    }

    private function errorValidation(Exception $exception)
    {
        $errors = collect($exception->errors());
        return $errors->unique()->first();
    }
}
