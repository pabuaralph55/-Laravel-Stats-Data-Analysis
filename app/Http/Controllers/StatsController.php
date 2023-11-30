<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Publisher;
use App\Models\Stat;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getOverallPublisherReport() {
        $sortColumn = request('sortColumn', 'Impressions'); 
        $sortOrder = request('sortOrder', 'desc');

        $results = DB::table('publisher as p')
            ->join('stats as s', 'p.id', '=', 's.publisher_id')
            ->select('p.name as Publisher',
                    DB::raw('SUM(s.impressions) as Impressions'),
                    DB::raw('SUM(s.conversions) as Conversions'),
                    DB::raw('SUM(s.conversions) / SUM(s.impressions) * 100 as ConversionRate'))
            ->groupBy('p.name')
            ->orderBy($sortColumn, $sortOrder)
            ->get();


        return view('reports.index', compact('results'));
    }

    public function getThirtyDayReport(Request $request) {
      
        $showingDefaultData = false;

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $sortColumn = $request->input('sortColumn', 'day');
        $sortOrder = $request->input('sortOrder', 'asc'); 

        if ($startDate === Carbon::now()->subDays(29)->toDateString() && 
            $endDate === Carbon::now()->toDateString()) {
            $showingDefaultData = true;
        }

        $stats = DB::table('stats')
            ->select(
                'day',
                DB::raw('SUM(impressions) as total_impressions'),
                DB::raw('SUM(conversions) as total_conversions'),
                DB::raw('CASE WHEN SUM(impressions) > 0 THEN SUM(conversions) / SUM(impressions) * 100 ELSE 0 END as conversion_rate')
            )
            ->whereBetween('day', [$startDate, $endDate])
            ->groupBy('day')
            ->orderBy($sortColumn, $sortOrder)
            ->get();

        // If no data was found for the selected date range
        if ($stats->isEmpty() && $startDate && $endDate) {
            $showingDefaultData = true; 
            $lastDate = DB::table('stats')->max('day');
            $startDate = Carbon::createFromFormat('Y-m-d', $lastDate)->subDays(29)->toDateString();

            $stats = DB::table('stats')
                ->select(
                    'day',
                    DB::raw('SUM(impressions) as total_impressions'),
                    DB::raw('SUM(conversions) as total_conversions'),
                    DB::raw('CASE WHEN SUM(impressions) > 0 THEN SUM(conversions) / SUM(impressions) * 100 ELSE 0 END as conversion_rate')
                )
                ->whereBetween('day', [$startDate, $lastDate])
                ->groupBy('day')
                ->orderBy($sortColumn, $sortOrder)
                ->get();
        }

        return view('reports.thirty_day', compact('stats','showingDefaultData'));
    }

    public function getPerformanceByDayReport(Request $request) {

        // Get the country ISO and publisher ID from the request
        $countryISO = $request->input('country');
        $publisherId = $request->input('publisher');

        $performanceQuery = DB::table('stats as s')
            ->join('platform as p', 's.platform_id', '=', 'p.id')
            ->join('country as c', 's.country_iso', '=', 'c.iso')
            ->join('publisher as pub', 's.publisher_id', '=', 'pub.id')
            ->selectRaw("
                s.day,
                SUM(CASE WHEN p.name = 'Android' THEN s.impressions ELSE 0 END) AS android_impressions,
                SUM(CASE WHEN p.name = 'Android' THEN s.conversions ELSE 0 END) AS android_conversions,
                SUM(CASE WHEN p.name = 'iPad' THEN s.impressions ELSE 0 END) AS ipad_impressions,
                SUM(CASE WHEN p.name = 'iPad' THEN s.conversions ELSE 0 END) AS ipad_conversions,
                SUM(CASE WHEN p.name = 'iPhone' THEN s.impressions ELSE 0 END) AS iphone_impressions,
                SUM(CASE WHEN p.name = 'iPhone' THEN s.conversions ELSE 0 END) AS iphone_conversions,
                CASE WHEN SUM(CASE WHEN p.name = 'Android' THEN s.impressions ELSE 0 END) > 0 
                    THEN SUM(CASE WHEN p.name = 'Android' THEN s.conversions ELSE 0 END) / NULLIF(SUM(CASE WHEN p.name = 'Android' THEN s.impressions ELSE 0 END), 0) 
                    ELSE 0 END AS android_conversion_rate,
                CASE WHEN SUM(CASE WHEN p.name = 'iPad' THEN s.impressions ELSE 0 END) > 0 
                    THEN SUM(CASE WHEN p.name = 'iPad' THEN s.conversions ELSE 0 END) / NULLIF(SUM(CASE WHEN p.name = 'iPad' THEN s.impressions ELSE 0 END), 0) 
                    ELSE 0 END AS ipad_conversion_rate,
                CASE WHEN SUM(CASE WHEN p.name = 'iPhone' THEN s.impressions ELSE 0 END) > 0 
                    THEN SUM(CASE WHEN p.name = 'iPhone' THEN s.conversions ELSE 0 END) / NULLIF(SUM(CASE WHEN p.name = 'iPhone' THEN s.impressions ELSE 0 END), 0) 
                    ELSE 0 END AS iphone_conversion_rate
            ")
            ->groupBy('s.day');

        // Apply country filter if provided
        if ($countryISO) {
            $performanceQuery->where('c.iso', $countryISO);
        }

        // Apply publisher filter if provided
        if ($publisherId) {
            $performanceQuery->where('pub.id', $publisherId);
        }

        $sortColumn = $request->input('sortColumn', 'day'); 
        $sortDirection = $request->input('sortOrder', 'asc') == 'asc' ? 'asc' : 'desc'; 
        $performances = $performanceQuery->orderBy($sortColumn, $sortDirection)->get();

        // Convert the data to a format suitable for Chart.js
        $labels = $performances->pluck('day');
        $androidConversionRates = $performances->pluck('android_conversion_rate');
        $ipadConversionRates = $performances->pluck('ipad_conversion_rate');
        $iphoneConversionRates = $performances->pluck('iphone_conversion_rate');

        $countries = DB::table('country')->get();
        $publishers = DB::table('publisher')->get();

        return view('reports.performance_by_day', compact('performances', 'countries', 'publishers', 'labels', 'androidConversionRates', 'ipadConversionRates', 'iphoneConversionRates'));
    }


}
