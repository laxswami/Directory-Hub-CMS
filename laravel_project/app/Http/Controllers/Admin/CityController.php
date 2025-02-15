<?php

namespace App\Http\Controllers\Admin;

use App\City;
use App\Country;
use App\Http\Controllers\Controller;
use App\State;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;

class CityController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('seo.backend.admin.city.cities', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $state_id = $request->state;

        if($state_id)
        {
            $state = State::findOrFail($state_id);
            $all_cities = $state->cities()->orderBy('city_name')->paginate(10);
        }
        else
        {
            $all_cities = City::orderBy('city_name')->paginate(10);
        }

        $all_states = collect();
        $all_countries = Country::orderBy('country_name')->get();

        foreach($all_countries as $key => $country)
        {
            $states = $country->states()->orderBy('state_name')->get();
            $all_states = $all_states->merge($states);
        }

        return response()->view('backend.admin.city.index',
            compact('all_states', 'state_id', 'all_cities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('seo.backend.admin.city.create-city', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $all_states = collect();
        $all_countries = Country::orderBy('country_name')->get();

        foreach($all_countries as $key => $country)
        {
            $states = $country->states()->orderBy('state_name')->get();
            $all_states = $all_states->merge($states);
        }

        return response()->view('backend.admin.city.create',
            compact('all_states'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'state_id' => 'required|numeric',
            //'city_name' => 'required|regex:/^[\pL\s\-]+$/u|max:255',
            'city_name' => 'required|max:255',
            'city_lat' => 'required|numeric',
            'city_lng' => 'required|numeric',
            'city_slug' => 'required|max:255|regex:/^[\w-]*$/',
        ]);

        $state = State::find($request->state_id);

        if($state)
        {
            $city_name = $request->city_name;
            $city_state = $state->state_abbr;
            $city_slug = $request->city_slug;
            $city_lat = $request->city_lat;
            $city_lng = $request->city_lng;

            $validate_error = array();
            $city_name_exist = $state->cities()
                ->where('city_name', $city_name)
                ->count();
            if($city_name_exist > 0)
            {
                $validate_error['city_name'] = __('prefer_country.error.city-name-exist');

            }

            $city_slug_exist = $state->cities()
                ->where('city_slug', $city_slug)
                ->count();
            if($city_slug_exist > 0)
            {
                $validate_error['city_slug'] = __('setting_language.location.url-slug-exist');

            }

            if(count($validate_error) > 0)
            {
                throw ValidationException::withMessages($validate_error);
            }
            else
            {
                // now can store
                $new_city = new City(array(
                    'state_id' => $state->id,
                    'city_name' => $city_name,
                    'city_state' => $city_state,
                    'city_slug' => $city_slug,
                    'city_lat' => $city_lat,
                    'city_lng' => $city_lng,
                ));

                $created_city = $state->cities()->save($new_city);

                \Session::flash('flash_message', __('alert.city-created'));
                \Session::flash('flash_type', 'success');

                return redirect()->route('admin.cities.edit', $created_city);
            }
        }
        else
        {
            throw ValidationException::withMessages(['state_id' => 'State & Country not found']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param City $city
     * @return RedirectResponse
     */
    public function show(City $city)
    {
        return redirect()->route('admin.cities.edit', $city);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param City $city
     * @return Response
     */
    public function edit(City $city)
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('seo.backend.admin.city.edit-city', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        return response()->view('backend.admin.city.edit',
            compact( 'city'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param City $city
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function update(Request $request, City $city)
    {
        $request->validate([
            //'city_name' => 'required|regex:/^[\pL\s\-]+$/u|max:255',
            'city_name' => 'required|max:255',
            'city_lat' => 'required|numeric',
            'city_lng' => 'required|numeric',
            'city_slug' => 'required|max:255|regex:/^[\w-]*$/',
        ]);

        $state = State::find($city->state_id);

        if($state)
        {
            $city_name = $request->city_name;
            $city_lat = $request->city_lat;
            $city_lng = $request->city_lng;
            $city_slug = $request->city_slug;

            $validate_error = array();
            $city_name_exist = $state->cities()
                ->where('city_name', $city_name)
                ->where('id', '!=', $city->id)
                ->count();
            if($city_name_exist > 0)
            {
                $validate_error['city_name'] = __('prefer_country.error.city-name-exist');

            }

            $city_slug_exist = $state->cities()
                ->where('city_slug', $city_slug)
                ->where('id', '!=', $city->id)
                ->count();
            if($city_slug_exist > 0)
            {
                $validate_error['city_slug'] = __('setting_language.location.url-slug-exist');

            }

            if(count($validate_error) > 0)
            {
                throw ValidationException::withMessages($validate_error);
            }

            $city->city_name = $city_name;
            $city->city_slug = $city_slug;
            $city->city_lat = $city_lat;
            $city->city_lng = $city_lng;
            $city->save();

            \Session::flash('flash_message', __('alert.city-updated'));
            \Session::flash('flash_type', 'success');

            return redirect()->route('admin.cities.edit', $city);
        }
        else
        {
            \Session::flash('flash_message', __('alert.state-country-not-found'));
            \Session::flash('flash_type', 'danger');

            return redirect()->route('admin.cities.edit', $city);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param City $city
     * @return RedirectResponse
     */
    public function destroy(City $city)
    {
        $city->deleteCity();

        \Session::flash('flash_message', __('alert.city-deleted'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.cities.index');
    }
}
