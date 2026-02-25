<x-app-layout title="Payment Gateway | Fitx Admin">
    <div class="content">
        <div class="container-fluid">
            <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                <div class="flex-grow-1"><h4 class="fs-18 fw-semibold m-0">Payment Gateway</h4></div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Payment Gateway</li>
                    </ol>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card bg-light-subtle border shadow-none">
                        <div class="card-header bg-transparent border-bottom">
                            <h5 class="card-title mb-0">Configuration</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.settings.update-payment-gateway') }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label class="form-label fw-medium">Client ID</label>
                                    <input type="text" class="form-control" name="client_id" value="{{ $settings->get('client_id') }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-medium">Client Secret Key</label>
                                    <input type="text" class="form-control" name="client_secret" value="{{ $settings->get('client_secret') }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-medium">Token</label>
                                    <input type="text" class="form-control" name="token" value="{{ $settings->get('token') }}">
                                </div>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>