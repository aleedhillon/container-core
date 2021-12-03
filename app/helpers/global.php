<?php

use App\Services\Request;
use App\Services\Response;
use App\Services\View;

function jsonResponse(array $data = [], int $status = 200)
{
    http_response_code($status);

    header('Content-Type: application/json; charset=utf-8');

    return json_encode($data);
}

function notFound(Request $request)
{
    if ($request->wantsJson()) {
        return jsonResponse([
            'message' => 'Page not Found'
        ], 404);
    }

    http_response_code(404);

    return View::make('404')->render();
}

function response(string $response = '', int $code = 200)
{
    return Response::make($response, $code);
}

function validationErrors(array $errors)
{
    echo response()->json([
        'message' => 'Please correct the following erros',
        'errors' => $errors
    ], 422);


    die;
}

function dd(...$data)
{
    die(var_dump($data));
}

function checkAuth()
{
    $authEmail = $_SESSION['email'] ?? null;

    if ($authEmail !== 'aleedhillon@gmail.com') {
        return jsonResponse([
            'message' => 'UnAuthenticated'
        ], 401);
    }
}

function view(string $view, array $data = [], bool $withLayout = false)
{
    return View::make($view, $data, $withLayout);
}

function storage_path(string $file)
{
    return '/storage/files/' . $file;
}
