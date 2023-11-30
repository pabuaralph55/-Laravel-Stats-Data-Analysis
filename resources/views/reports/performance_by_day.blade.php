@extends('layouts.app')

@section('title', 'Data Analysis')

@section('content')
    <h1>Performance by Day</h1>

    <div class="center-canvas">
        <canvas id="myChart" width="800" height="300"></canvas>
    </div>

    <form action="{{ route('reports.performance_by_day') }}" method="GET" class="row g-3 align-items-end">
        <div class="col-auto">
            <select id="country" name="country" class="form-control">
                <option value="">Select Country</option>
                @foreach ($countries as $country)
                    <option value="{{ $country->iso }}">{{ $country->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-auto">
            <select id="publisher" name="publisher" class="form-control">
                <option value="">Select Publisher</option>
                @foreach ($publishers as $publisher)
                    <option value="{{ $publisher->id }}">{{ $publisher->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-auto">
            <button id="retrieve_data" type="submit" class="btn btn-primary">Retrieve Data</button>
        </div>
    </form>

    <table>
        <thead>
            <tr>
                <th>#</th> 
                <th>
                    <a href="{{ route('reports.performance_by_day', ['sortColumn' => 'day', 'sortOrder' => request('sortOrder', 'asc') == 'asc' ? 'desc' : 'asc']) }}">
                        Day
                        @if (request('sortColumn') == 'day')
                            {!! request('sortOrder', 'asc') == 'asc' ? '&#x25BC;' : '&#x25B2;' !!}
                        @endif
                    </a>
                </th>
                <th>
                    <a href="{{ route('reports.performance_by_day', ['sortColumn' => 'android_conversion_rate', 'sortOrder' => request('sortOrder', 'asc') == 'asc' ? 'desc' : 'asc']) }}">
                        Android
                        @if (request('sortColumn') == 'android_conversion_rate')
                            {!! request('sortOrder', 'asc') == 'asc' ? '&#x25BC;' : '&#x25B2;' !!}
                        @endif
                    </a>
                </th>
                <th>
                    <a href="{{ route('reports.performance_by_day', ['sortColumn' => 'ipad_conversion_rate', 'sortOrder' => request('sortOrder', 'asc') == 'asc' ? 'desc' : 'asc']) }}">
                        iPad
                        @if (request('sortColumn') == 'ipad_conversion_rate')
                            {!! request('sortOrder', 'asc') == 'asc' ? '&#x25BC;' : '&#x25B2;' !!}
                        @endif
                    </a>
                </th>
                <th>
                    <a href="{{ route('reports.performance_by_day', ['sortColumn' => 'iphone_conversion_rate', 'sortOrder' => request('sortOrder', 'asc') == 'asc' ? 'desc' : 'asc']) }}">
                        iPhone
                        @if (request('sortColumn') == 'iphone_conversion_rate')
                            {!! request('sortOrder', 'asc') == 'asc' ? '&#x25BC;' : '&#x25B2;' !!}
                        @endif
                    </a>
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach($performances as $index => $performance)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $performance->day }}</td>
                    <td>{{ number_format($performance->android_conversion_rate * 100, 4) }}%</td>
                    <td>{{ number_format($performance->ipad_conversion_rate * 100, 4) }}%</td>
                    <td>{{ number_format($performance->iphone_conversion_rate * 100, 4) }}%</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection

@section('styles')
    <style>
        .center-canvas {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 70vh; 
        }
    </style>

@endsection

@section('scripts')
    <script>
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($labels),
                datasets: [{
                    label: 'Android Conversion Rate',
                    data: @json($androidConversionRates),
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }, {
                    label: 'iPad Conversion Rate',
                    data: @json($ipadConversionRates),
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }, {
                    label: 'iPhone Conversion Rate',
                    data: @json($iphoneConversionRates),
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endsection