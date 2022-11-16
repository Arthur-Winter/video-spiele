<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GamesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $before = Carbon::now()->subMonths(6)->timestamp;
        $after = Carbon::now()->addMonths(2)->timestamp;
        $current = Carbon::now()->timestamp;
        $afterFourMonths = Carbon::now()->addMonths(4)->timestamp;

        $popularGames = Http::withHeaders([
            'Client-ID' => '0zkbzbgf8ufln3yxw3vg3zj1rbnt1r',
            'Authorization' => 'Bearer 0hmibfhohjql9neivvasgzhat8y404'
        ])
        ->withBody(
                "fields name, cover.url, first_release_date, total_rating_count, platforms.abbreviation,
                    rating, slug, summary;
                where platforms = (48,49,130,6)
                & ( first_release_date >= {$before} 
                & first_release_date < {$after}
                & total_rating_count > 5);
                sort total_rating_count desc;
                limit 12;", "text/plain"
        )->post('https://api.igdb.com/v4/games')
        ->json();

        //dd($popularGames);

        
        $recentlyReviewed = Http::withHeaders([
            'Client-ID' => '0zkbzbgf8ufln3yxw3vg3zj1rbnt1r',
            'Authorization' => 'Bearer 0hmibfhohjql9neivvasgzhat8y404'
        ])
        ->withBody(
            "fields name, cover.url, first_release_date, total_rating_count, platforms.abbreviation,
                rating, slug, summary;
                where platforms = (48,49,130,6)
                & (first_release_date >= {$before}
                & first_release_date <= {$after}
                & total_rating_count > 5);
                sort total_rating_count desc;
                limit 12;", "text/plain"
            )->post('https://api.igdb.com/v4/games')
            ->json();
        

        //dd($recentlyReviewed);

         $mostAnticipated = Http::withHeaders([
            'Client-ID' => '0zkbzbgf8ufln3yxw3vg3zj1rbnt1r',
            'Authorization' => 'Bearer 0hmibfhohjql9neivvasgzhat8y404'
         ])
        ->withBody(
            "fields name, cover.url, first_release_date, total_rating_count, platforms.abbreviation, 
                rating, slug, rating_count, summary;
                where platforms = (48,49,130,6)
                & (first_release_date >= {$before}
                & first_release_date < {$afterFourMonths}
                & total_rating_count > 5);
                sort total_rating_count desc;
                limit 4;", "text/plain"
        )->post('https://api.igdb.com/v4/games')
        ->json();

        //dd($mostAnticipated);

        $comingSoon = Http::withHeaders([
            'Client-ID' => '0zkbzbgf8ufln3yxw3vg3zj1rbnt1r',
            'Authorization' => 'Bearer 0hmibfhohjql9neivvasgzhat8y404'
        ])
            ->withBody(
                "fields name, cover.url, first_release_date, total_rating_count, platforms.abbreviation, 
                    rating, slug, rating_count, summary;
                    where platforms = (48,49,130,6)
                    & (first_release_date >= {$current}
                    & total_rating_count > 5);
                    sort first_release_date asc;
                    limit 4;", "text/plain"
            )->post('https://api.igdb.com/v4/games')
            ->json();

        // dump($comingSoon);
    

        return view('index', [
            'popularGames' => $popularGames,
            'recentlyReviewed' => $recentlyReviewed,
            'mostAnticipated' => $mostAnticipated,
            'comingSoon' => $comingSoon,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  string $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $game = Http::withHeaders([
            'Client-ID' => '0zkbzbgf8ufln3yxw3vg3zj1rbnt1r',
            'Authorization' => 'Bearer 0hmibfhohjql9neivvasgzhat8y404'
        ])
            ->withBody(
                 "fields name, cover.url, first_release_date, total_rating_count, platforms.abbreviation, rating,
                    slug, involved_companies.company.name, genres.name, aggregated_rating, summary, websites.*, videos.*, screenshots.*, similar_games.cover.url, similar_games.name, similar_games.rating,similar_games.platforms.abbreviation, similar_games.slug;
                    where slug=\"{$slug}\";" , "text/plain"
            )->post('https://api.igdb.com/v4/games')
            ->json();

        abort_if(!$game, 404);

        return view('show', [
            'game' => $game[0],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}