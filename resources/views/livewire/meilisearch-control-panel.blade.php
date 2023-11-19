<div>


    <div class="card">
        {{-- <div class="card-header">
            <h5 class="card-title">Info</h5>
        </div> --}}
        <div class="card-body">
            <div class="d-flex" wire:init="checkStatus()">
                <h4>
                    <span>Server Staus:</span>
                    @if ($isServiceOnline)
                        <strong class="text-success">Online</strong>
                    @else
                        <strong class="text-danger">Offline</strong>
                    @endif
                </h4>
            </div>
            <ul>
                <li>Total Documents: <strong>234</strong></li>
            </ul>
        </div>
    </div>


</div>
