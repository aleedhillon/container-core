<?php

function jsonResponse(array $data = [], int $status = 200)
{
    http_response_code($status);

    header('Content-Type: application/json; charset=utf-8');

    echo json_encode($data);

    return true;
}

function notFound()
{
    return jsonResponse([
        'message' => 'Page not Found'
    ], 404);
}

function validationErrors(array $errors)
{
    return jsonResponse([
        'message' => 'Please correct the following erros',
        'errors' => $errors
    ], 422);
}

function dd(...$data)
{
    die(var_dump($data));
}
