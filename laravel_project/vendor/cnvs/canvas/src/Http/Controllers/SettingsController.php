<?php

namespace Canvas\Http\Controllers;

use Canvas\UserMeta;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SettingsController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @return JsonResponse
     */
    public function show(): JsonResponse
    {
        $metaData = UserMeta::forCurrentUser()->first();
        $emailHash = md5(trim(Str::lower(request()->user()->email)));

        return response()->json([
            'avatar' => optional($metaData)->avatar ?? "https://secure.gravatar.com/avatar/{$emailHash}?s=500",
            'dark_mode' => optional($metaData)->dark_mode ?? 0,
            'digest' => optional($metaData)->digest ?? false,
            'summary' => optional($metaData)->summary ?? null,
            'locale' => optional($metaData)->locale ?? null,
            'username' => optional($metaData)->username ?? null,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return JsonResponse
     */
    public function update(): JsonResponse
    {
        $metaData = UserMeta::forCurrentUser()->first() ?? new UserMeta();

        $data = [
            'avatar' => request('avatar') ?? $metaData->avatar,
            'dark_mode' => request('dark_mode') ?? $metaData->dark_mode,
            'digest' => request('digest') ?? $metaData->digest,
            'locale' => request('locale') ?? $metaData->locale,
            'user_id' => request()->user()->id,
            'username' => request('username') ?? $metaData->username,
            'summary' => request('summary') ?? $metaData->summary,
        ];

        $messages = [
            'unique' => __('canvas::app.validation_unique'),
        ];

        validator($data, [
            'user_id' => 'required',
            'username' => [
                'nullable',
                'alpha_dash',
                Rule::unique('canvas_user_meta')->ignore($data['user_id'], 'user_id'),
            ],
        ], $messages)->validate();

        $metaData->fill($data);

        $metaData->save();

        return response()->json($metaData->refresh());
    }
}
