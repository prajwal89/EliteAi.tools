<div wire:init="init()">

    <div class="card">
        {{-- <div class="card-header">
            <h5 class="card-title">Info</h5>
        </div> --}}
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

</div>
