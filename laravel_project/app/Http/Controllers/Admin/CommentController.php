<?php

namespace App\Http\Controllers\Admin;

use Artesaos\SEOTools\Facades\SEOMeta;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
use Laravelista\Comments\Comment;
use App\Http\Controllers\Controller;

class CommentController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('seo.backend.admin.comment', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $all_comments = Comment::orderBy('created_at', 'DESC')->get();

        return response()->view('backend.admin.comment.index', compact('all_comments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Comment $comment
     * @return RedirectResponse
     */
    public function approve(Comment $comment)
    {
        $comment->approved = true;
        $comment->save();

        // success, flash message
        \Session::flash('flash_message', __('alert.comment-approved'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.comments.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Comment $comment
     * @return RedirectResponse
     */
    public function disapprove(Comment $comment)
    {
        $comment->approved = false;
        $comment->save();

        // success, flash message
        \Session::flash('flash_message', __('alert.comment-disapproved'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.comments.index');
    }

    /**
     * Deletes a comment.
     * @param Comment $comment
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(Comment $comment)
    {
        if (Config::get('comments.soft_deletes') == true) {
            $comment->delete();
        }
        else {
            $comment->forceDelete();
        }

        // success, flash message
        \Session::flash('flash_message', __('alert.comment-deleted'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.comments.index');
    }
}
