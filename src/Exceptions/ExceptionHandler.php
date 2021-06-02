<?php

namespace Maravel\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\QueryException;

class ExceptionHandler extends \App\Exceptions\Handler
{
    use QueryExceptionCode;

    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    public function render($request, Exception $exception)
    {
        $render = parent::render($request, $exception);
        $data = (array) $render->getData();
        $data = array_replace_recursive([
            'is_ok' => false,
            'message' => null,
            'message_text' => null,
            'referer' => $request->headers->get('referer')
        ], $data);
        result_message($data, $data['message'] ?: Response::$statusTexts[$render->getStatusCode()]);
        if(!config('app.debug'))
        {
            if ($exception instanceof ModelNotFoundException) {
                result_message($data, str_replace('App\\', '', $exception->getModel()) . ' not found');
            } elseif ($exception instanceof QueryException) {
                result_message($data, $this->QueryException(...$exception->errorInfo));
            } elseif ($render->getStatusCode() == 500 || $render->getStatusCode() == 501) {
                result_message($data, Response::$statusTexts[$render->getStatusCode()]);
            }
            unset($data['exception']);
            unset($data['file']);
            unset($data['line']);
            unset($data['trace']);
        }
        if ($exception instanceof ModelNotFoundException) {
            $error = 'No query results for :model :ids';
            $model = lcfirst(str_replace('App\\', '', $exception->getModel()));
            result_message($data,  'NO_QUERY_RESULTS', __($error, ['model' => __('models.'.$model), 'ids' => join(',', $exception->getIds())]));
        }
        $render->setData($data);
        return $render;
    }
}
