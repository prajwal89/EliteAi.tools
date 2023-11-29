<div>

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
                    <li>DataBase Size:
                        <strong>{{ \Illuminate\Support\Number::filesize($overallStats['databaseSize']) }}</strong>
                    </li>
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

        <section class="card-body">
            @foreach ($currentWebsiteAllIndexesData as $indexName => $indexData)
                <article class="border border-success p-2 rounded" style="background-color: #f0f0f0;">
                    <header class="d-flex justify-content-between">
                        <h4><strong>{{ $indexData['tableName'] }}</strong></h4>
                        <div>
                            <button wire:click="indexAllDocumentsOfTable('{{ $indexData['tableName'] }}')"
                                class="btn btn-secondary">
                                Index all documents
                            </button>
                            <button class="btn btn-success my-2"
                                wire:click="syncSettings('{{ $indexData['tableName'] }}')">
                                Sync Settings
                            </button>
                        </div>
                    </header>

                    <ul>
                        <li>Total Documents in Index: <strong>{{ $indexData['numberOfDocuments'] }}</strong></li>
                        <li>Total Documents in Table: <strong>{{ $indexData['totalRecordsInDatabaseTable'] }}</strong>
                        </li>

                        @if ($indexData['numberOfDocuments'] != DB::table($indexData['tableName'])->count())
                            <li class="text-danger">Database Records are not synced with Meilisearch</li>
                        @endif
                    </ul>

                    <div class="row">
                        <div class="col-6">
                            <label class="text-primary fw-bold" style="cursor: pointer"
                                wire:click="showServerSettings('{{ $indexData['tableName'] }}')">Server
                                Settings</label>
                            <textarea class="form-control" readonly cols="30" rows="10">{{ json_encode($indexData, JSON_PRETTY_PRINT) }}</textarea>
                        </div>
                        <div class="col-6">
                            <div>
                                <label class="text-primary fw-bold" style="cursor: pointer"
                                    wire:click="showLocalSettings('{{ $indexData['tableName'] }}')">Local
                                    Settings</label>
                                <textarea class="form-control" readonly cols="30" rows="4">{{ json_encode($indexData['localSettings'], JSON_PRETTY_PRINT) }}</textarea>
                            </div>
                            <div class="mt-2">
                                <label for="">Difference</label>
                                <textarea class="form-control" readonly cols="30" rows="4">{{ json_encode($indexData['settingsDifference'], JSON_PRETTY_PRINT) }}</textarea>
                            </div>
                        </div>
                    </div>
                </article>

                @if (!$loop->last)
                    <hr class="my-4 border border-4 border-secondary">
                @endif
            @endforeach
        </section>

    </div>

</div>
