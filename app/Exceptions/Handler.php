<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Traits\ApiResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponse;

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
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            return false;
        });
    }

    /**
     * @param Request $request
     * @param Throwable $e
     * @return JsonResponse|Response|\Symfony\Component\HttpFoundation\Response
     * @throws Throwable
     */
    public function render($request, Throwable $e)
    {
        if ($request->is('api/*')) {
            switch (get_class($e)) {
                case ValidationException::class:
                    $this->changeValidationCode($e);
                    return $e->response ?? $this->sendError($e->getMessage(), $e->status, $e->errors());
                case HttpException::class:
                case NotFoundHttpException::class:
                    return $this->sendError($e->getMessage(), $e->getStatusCode());
                case ModelNotFoundException::class:
                    return $this->sendError($e->getMessage(), 404);
                default:
                    return $this->sendError($e->getMessage(), $e->status ?? 401);
            }
        }

        return parent::render($request, $e);
    }

    private function changeValidationCode(ValidationException $exception)
    {
        if ($exception->response == null) {
            $keyCode = [
                'Unique' => 409,
                'Exists' => 404
            ];
            foreach ($exception->validator->failed() as $failed) {
                foreach ($keyCode as $key => $code)
                    if (array_key_exists($key, $failed)) {
                        $exception->status = $code;
                        break;
                    }
            }
        }
    }
}
