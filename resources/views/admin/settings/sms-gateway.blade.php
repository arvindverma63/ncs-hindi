<x-app-layout title="SMS Gateway | Fitx Admin">
    <div class="content">
        <div class="container-fluid">
            <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                <div class="flex-grow-1"><h4 class="fs-18 fw-semibold m-0">SMS Gateway</h4></div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">SMS Gateway</li>
                    </ol>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card bg-light-subtle border shadow-none">
                        <div class="card-header bg-transparent border-bottom">
                            <h5 class="card-title mb-0">SMS Configuration</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.settings.update-sms-gateway') }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-medium">Gateway Provider</label>
                                        <select class="form-select" name="sms_gateway">
                                            <option value="" disabled>Select</option>
                                            <option value="twilio" {{ $settings->get('sms_gateway') == 'twilio' ? 'selected' : '' }}>Twilio</option>
                                            <option value="nexmo" {{ $settings->get('sms_gateway') == 'nexmo' ? 'selected' : '' }}>Nexmo</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-medium">SID / Key</label>
                                        <input type="text" class="form-control" name="twilio_sid" value="{{ $settings->get('twilio_sid') }}">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-medium">Token / Secret</label>
                                        <input type="text" class="form-control" name="twilio_token" value="{{ $settings->get('twilio_token') }}">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Update Settings</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>