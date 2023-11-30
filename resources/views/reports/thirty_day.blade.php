@extends('layouts.app')

@section('title', 'Data Analysis')

@section('content')
<h1>30-day Report</h1>
<form action="#" method="GET" class="row g-3 align-items-end">
    <div class="col-auto">
        <label for="start_date" class="form-label mb-0">Start Date</label>
        <input type="date" id="start_date" name="start_date" class="form-control" 
               value="{{ request('start_date', \Carbon\Carbon::now()->subDays(29)->toDateString()) }}">
    </div>
    <div class="col-auto">
        <label for="end_date" class="form-label mb-0">End Date</label>
        <input type="date" id="end_date" name="end_date" class="form-control" 
               value="{{ request('end_date', \Carbon\Carbon::now()->toDateString()) }}">
    </div>
    <div class="col-auto">
        <button id="retrieve_data" type="submit" class="btn btn-primary">Retrieve Data</button>
    </div>
</form>

@if ($showingDefaultData)
    <div class="alert alert-info">
        No data found for the selected date range. Showing the latest 30 days of data instead.
    </div>
@endif

<table>
    <thead>
        <tr>
            <th>#</th> 
            <th>
                <a href="{{ route('reports.thirty_day', ['start_date' => request('start_date'), 
                    'end_date' => request('end_date'),'sortColumn' => 'day', 'sortOrder' => request('sortOrder', 'asc') == 'asc' ? 'desc' : 'asc']) }}">
                    Day
                    @if (request('sortColumn') == 'day')
                        {!! request('sortOrder', 'asc') == 'asc' ? '&#x25BC;' : '&#x25B2;' !!}
                    @endif
                </a>
            </th>
            <th>
                <a href="{{ route('reports.thirty_day', ['start_date' => request('start_date'), 
                    'end_date' => request('end_date'), 'sortColumn' => 'total_impressions', 'sortOrder' => request('sortOrder', 'asc') == 'asc' ? 'desc' : 'asc']) }}">
                    Impressions
                    @if (request('sortColumn') == 'total_impressions')
                        {!! request('sortOrder', 'asc') == 'asc' ? '&#x25BC;' : '&#x25B2;' !!}
                    @endif
                </a>
            </th>
            <th>
                <a href="{{ route('reports.thirty_day', ['start_date' => request('start_date'), 
                    'end_date' => request('end_date'), 'sortColumn' => 'total_conversions', 'sortOrder' => request('sortOrder', 'asc') == 'asc' ? 'desc' : 'asc']) }}">
                    Conversions
                    @if (request('sortColumn') == 'total_conversions')
                        {!! request('sortOrder', 'asc') == 'asc' ? '&#x25BC;' : '&#x25B2;' !!}
                    @endif
                </a>
            </th>
            <th>
                <a href="{{ route('reports.thirty_day', ['start_date' => request('start_date'), 
                    'end_date' => request('end_date'), 'sortColumn' => 'conversion_rate', 'sortOrder' => request('sortOrder', 'asc') == 'asc' ? 'desc' : 'asc']) }}">
                    Conversion Rate
                    @if (request('sortColumn') == 'conversion_rate')
                        {!! request('sortOrder', 'asc') == 'asc' ? '&#x25BC;' : '&#x25B2;' !!}
                    @endif
                </a>
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach($stats as $index => $stat)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $stat->day }}</td>
                <td>{{ number_format($stat->total_impressions, 0, '.', ',') }}</td>
                <td>{{ number_format($stat->total_conversions, 0, '.', ',') }}</td>
                <td>{{ number_format($stat->conversion_rate, 4) }}%</td>
            </tr>
        @endforeach
    </tbody>
</table>

@endsection


@section('styles')

@endsection


@section('scripts')
    
@endsection