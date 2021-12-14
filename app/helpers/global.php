<?php

use App\Core\Application;
use App\Services\Collection;
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

    return View::make('error', [
        'message' => '404 | Not Found'
    ], false, 404)->render();
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

function exceptionToResponse(Throwable $th)
{
    $request = new Request('GET');

    $code = $th->getCode() ? $th->getCode() : 500;

    $data['message'] = $th->getMessage();

    if (env('DEBUG')) {
        $data['code'] = $th->getCode();
        $data['line'] = $th->getLine();
        $data['file'] = $th->getFile();
        $data['trace'] = $th->getTraceAsString();
    }

    if ($request->wantsJson()) {
        echo response()->json($data, 500);

        die;
    }

    echo View::make('error', [
        'message' => $code . ' | ' . $th->getMessage()
    ], false, $code)->render();
}

function redirect(string $location)
{
    return header("Location: {$location}");
}

function collect(array $data = [])
{
    return new Collection($data);
}

function loadEnv()
{
    $file = fopen(__DIR__ . '/../../.env', 'r');

    while (!feof($file)) {
        $line = trim(fgets($file)); 
        if($line) {
            putenv($line);
        }
    }

    fclose($file);
}

function env(string $key, string $default = null)
{
    return getenv($key) ?? $default;
}

function nameAndAge()
{
    $name = 'Alee Dhillon';
    $age = 29;

    return [$name, $age];
}

function createClass()
{
    return new class() {};
}

function getLogPath(string $logName)
{
    return realpath(__DIR__ . "\\..\\..\\storage/logs") . '\\' . $logName . '.log';
}

function getAllServiceProviders()
{
    return require_once __DIR__ . '/../../config/providers.php';
}

function resolve(string $id)
{
    return Application::getContainer()->get($id);
}