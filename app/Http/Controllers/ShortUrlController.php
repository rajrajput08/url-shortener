<?php

namespace App\Http\Controllers;

use App\Models\ShortUrl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ShortUrlController extends Controller
{
    public function index(Request $request)
    {
        $shortUrls = ShortUrl::visibleTo($request->user())
            ->with(['user', 'company'])
            ->latest()
            ->paginate(10);

        return view('short-urls.index', compact('shortUrls'));
    }

    public function create()
    {
        Gate::authorize('create', ShortUrl::class);

        return view('short-urls.create');
    }

    public function store(Request $request)
    {
        Gate::authorize('create', ShortUrl::class);

        $request->validate([
            'original_url' => 'required|url:http,https|max:2048',
        ]);

        ShortUrl::create([
            'company_id' => $request->user()->company_id,
            'user_id' => $request->user()->id,
            'original_url' => $request->original_url,
            'short_code' => ShortUrl::generateCode(),
        ]);

        return redirect()->route('short-urls.index')->with('success', 'Short URL created successfully.');
    }

    /**
     * Public redirect, no login required.
     */
    public function resolve(string $code)
    {
        $shortUrl = ShortUrl::where('short_code', $code)->firstOrFail();
        $shortUrl->increment('hits');

        return redirect()->away($shortUrl->original_url);
    }
}
