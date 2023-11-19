<div wire:init="init()">

    <div class="card">
        <div class="card-header">
            <h2>Overall Stats</h2>
        </div>
        <div class="card-body">
            <div class="d-flex">
                <h4>
                    <span>Server Staus:</span>
                    @if ($isServiceOnline)
                        <strong class="text-success">Online</strong>
                    @else
                        <strong class="text-danger">Offline</strong>
                    @endif
                </h4>
            </div>
            @if (!empty($overallStats['databaseSize']))
                {{-- Total size of milisearch including all indexes --}}
                <ul>
                    <li>HostName:
                        <strong>
                            <a href="{{ config('custom.meilisearch.host') }}" target="_blank">
                                {{ config('custom.meilisearch.host') }}
                            </a>
                        </strong>
                    </li>
                    <li>DataBase Size: <strong>{{ formatFileSize($overallStats['databaseSize']) }}</strong></li>
                    <li>Total Documents: <strong>{{ $totalDocuments }}</strong></li>
                    <li>Total Documents Current Website: <strong>{{ $totalDocumentsOfCurrentWebsite }}</strong></li>
                </ul>
            @endif
        </div>
    </div>


    <div class="card mt-4">
        <div class="card-header">
            <h2>Indexes</h2>
        </div>

        <div class="card-body">
            <div class="border border-success p-2 rounded">
                @foreach ($currentWebsiteAllIndexesData as $indexName => $indexData)
                    <h4>{{ $indexName }}</h4>

                    <ul>
                        <li>Total Documents: {{ $indexData['numberOfDocuments'] }}</li>
                    </ul>

                    <textarea class="form-control" id="" cols="30" rows="10">{{ json_encode($indexData, JSON_PRETTY_PRINT) }}</textarea>
                @endforeach
            </div>
        </div>
    </div>

</div>
