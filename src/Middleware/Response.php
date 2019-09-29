<?php

namespace Maravel\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;

class Response
{
    public function handle($request, Closure $next)
    {

        $response = $next($request);
        if ($response instanceof JsonResponse || ($request->segment(1) == 'api' && $response->exception)) {
            if ($response->exception) {
                return $response;
            }
            else
            {
                $result = json_decode($response->content(), true);
                $result = array_merge_recursive([
                    'is_ok' => true
                ],$result);
                if(isset($result['links']))
                {
                    $links = $result['links'];
                    unset($result['links']);
                    $result['links'] = $links;
                }
                if (isset($result['meta'])) {
                    $meta = $result['meta'];
                    unset($result['meta']);
                    $result['meta'] = $meta;
                }
                if($request->route()->getAction('controller'))
                {
                    $controller = $request->route()->getController();
                    if (isset($controller->statusMessage))
                    {
                        result_message($result, $controller->statusMessage);
                    }
                    else
                    {
                        result_message($result, ':)');
                    }
                }
                $response = response()->json(
                    $result,
                    $response->status(),
                    $response->headers->all()
                );
            }
        }
        return $response;
    }
}
