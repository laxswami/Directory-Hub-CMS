<?php

namespace Canvas\Http\Controllers;

use Illuminate\Routing\Controller;

class LocaleController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @return string
     */
    public function __invoke()
    {
        return collect(['app' => trans('canvas::app', [], request('locale'))])->toJson();
    }
}
