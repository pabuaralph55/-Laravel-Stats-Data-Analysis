@extends('layouts.app')

@section('title', 'Data Analysis')

@section('content')
    <h1>Overall Publisher Report</h1>
    <table>
        <thead>
            <tr>
                <th>#</th> 
                <th>
                    <a href="{{ route('reports.index', ['sortColumn' => 'Publisher', 'sortOrder' => request('sortOrder', 'asc') == 'asc' ? 'desc' : 'asc']) }}">
                        Publisher
                        @if (request('sortColumn') == 'Publisher')
                            {!! request('sortOrder', 'asc') == 'asc' ? '&#x25BC;' : '&#x25B2;' !!}
                        @endif
                    </a>
                </th>
                <th>
                    <a href="{{ route('reports.index', ['sortColumn' => 'Impressions', 'sortOrder' => request('sortOrder', 'asc') == 'asc' ? 'desc' : 'asc']) }}">
                        Impressions
                        @if (request('sortColumn') == 'Impressions')
                            {!! request('sortOrder', 'asc') == 'asc' ? '&#x25BC;' : '&#x25B2;' !!}
                        @endif
                    </a>
                </th>
                <th>
                    <a href="{{ route('reports.index', ['sortColumn' => 'Conversions', 'sortOrder' => request('sortOrder', 'asc') == 'asc' ? 'desc' : 'asc']) }}">
                        Conversions
                        @if (request('sortColumn') == 'Conversions')
                            {!! request('sortOrder', 'asc') == 'asc' ? '&#x25BC;' : '&#x25B2;' !!}
                        @endif
                    </a>
                </th>
                <th>
                    <a href="{{ route('reports.index', ['sortColumn' => 'ConversionRate', 'sortOrder' => request('sortOrder', 'asc') == 'asc' ? 'desc' : 'asc']) }}">
                        Conversion Rate
                        @if (request('sortColumn') == 'ConversionRate')
                            {!! request('sortOrder', 'asc') == 'asc' ? '&#x25BC;' : '&#x25B2;' !!}
                        @endif
                    </a>
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach($results as $index => $result)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $result->Publisher }}</td>
                <td>{{ number_format($result->Impressions, 0, '.', ',') }}</td>
                <td>{{ number_format($result->Conversions, 0, '.', ',') }}</td>
                <td>{{ number_format($result->ConversionRate, 2) }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection


@section('index_styles')
    
@endsection


@section('index_scripts')
    
@endsection