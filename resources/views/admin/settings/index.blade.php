<x-app-layout title="General Settings | Fitx Admin">
    <div class="content">
        <div class="container-fluid">

            <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                <div class="flex-grow-1">
                    <h4 class="fs-18 fw-semibold m-0">General Settings</h4>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Settings</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card bg-light-subtle border shadow-none">
                        <div class="card-header bg-transparent border-bottom">
                            <h5 class="card-title mb-0">App Configuration</h5>
                        </div>
                        <div class="card-body">
                            
                            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-medium">App Name</label>
                                        <input type="text" class="form-control" name="app_name" value="{{ $settings->get('app_name') }}" placeholder="Fitx App" required>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-medium">App Phone</label>
                                        <input type="text" class="form-control" name="app_phone" value="{{ $settings->get('app_phone') }}" placeholder="+1 234 567 890">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-medium">Support Email</label>
                                        <input type="email" class="form-control" name="app_email" value="{{ $settings->get('app_email') }}" placeholder="support@fitx.com">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-medium">Address</label>
                                        <input type="text" class="form-control" name="app_address" value="{{ $settings->get('app_address') }}" placeholder="123 Fitx St, Gym City">
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label fw-medium mb-2">Main Logo</label>
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="{{ $settings->getImageUrl('logo') }}" alt="Logo" class="img-thumbnail bg-light" style="height: 60px; max-width: 150px; object-fit: contain;">
                                            <input type="file" class="form-control" name="logo" accept="image/*">
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-4">
                                        <label class="form-label fw-medium mb-2">Favicon</label>
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="{{ $settings->getImageUrl('favicon') }}" alt="Favicon" class="img-thumbnail bg-light" style="height: 40px; width: 40px; object-fit: contain;">
                                            <input type="file" class="form-control" name="favicon" accept="image/*">
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-4">
                                        <label class="form-label fw-medium mb-2">Login Page Logo</label>
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="{{ $settings->getImageUrl('login_page_logo') }}" alt="Login Logo" class="img-thumbnail bg-light" style="height: 60px; max-width: 150px; object-fit: contain;">
                                            <input type="file" class="form-control" name="login_page_logo" accept="image/*">
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4 text-end">
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="mdi mdi-content-save-outline me-1"></i> Update Settings
                                    </button>
                                </div>

                            </form>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>