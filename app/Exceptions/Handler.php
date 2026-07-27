<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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
        $this->reportable(function (Throwable $e) {
            //
        });

        // Global handling for Spatie Permission UnauthorizedException
        $this->renderable(function (\Spatie\Permission\Exceptions\UnauthorizedException $e, $request) {
            $requiredPermissions = implode(', ', $e->getRequiredPermissions());
            $requiredRoles = implode(', ', $e->getRequiredRoles());
            
            $permInfo = [];
            if (!empty($requiredPermissions)) {
                $permInfo[] = "Permission required: (" . $requiredPermissions . ")";
            }
            if (!empty($requiredRoles)) {
                $permInfo[] = "Role required: (" . $requiredRoles . ")";
            }
            
            $message = !empty($permInfo) 
                ? implode(' | ', $permInfo) . ". Please ask an administrator to grant access." 
                : 'Access Denied: Missing required permissions or roles.';

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['message' => $message, 'error' => 'Forbidden'], 403);
            }

            return response()->view('errors.403', ['exception' => $e, 'customMessage' => $message], 403);
        });

        // Global handling for AccessDeniedHttpException
        $this->renderable(function (\Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException $e, $request) {
            $message = $e->getMessage();
            if (empty($message) || $message === 'This action is unauthorized.') {
                $routeName = $request->route() ? $request->route()->getName() : null;
                $message = $routeName 
                    ? "Permission required: (" . $routeName . "). Please ask an administrator to grant access."
                    : "Access Denied: Missing required permission. Please ask an administrator to grant access.";
            }

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['message' => $message, 'error' => 'Forbidden'], 403);
            }

            return response()->view('errors.403', ['exception' => $e, 'customMessage' => $message], 403);
        });

        // Global handling for AuthorizationException
        $this->renderable(function (\Illuminate\Auth\Access\AuthorizationException $e, $request) {
            $message = $e->getMessage();
            if (empty($message) || $message === 'This action is unauthorized.') {
                $routeName = $request->route() ? $request->route()->getName() : null;
                $message = $routeName 
                    ? "Permission required: (" . $routeName . "). Please ask an administrator to grant access."
                    : "Access Denied: Missing required permission. Please ask an administrator to grant access.";
            }

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['message' => $message, 'error' => 'Forbidden'], 403);
            }

            return response()->view('errors.403', ['exception' => $e, 'customMessage' => $message], 403);
        });
    }
}
