<x-app-layout title="Mail Configuration | Fitx Admin">
    <div class="content">
        <div class="container-fluid">
            <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                <div class="flex-grow-1"><h4 class="fs-18 fw-semibold m-0">Mail Configuration</h4></div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Mail Config</li>
                    </ol>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card bg-light-subtle border shadow-none">
                        <div class="card-header bg-transparent border-bottom">
                            <h5 class="card-title mb-0">SMTP Settings</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.settings.update-mail-config') }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-medium">Mailer Name (From Name)</label>
                                        <input type="text" class="form-control" name="mailer_name" value="{{ $settings->get('mailer_name') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-medium">Sender Email (From Email)</label>
                                        <input type="email" class="form-control" name="mail_email_id" value="{{ $settings->get('mail_email_id') }}">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-medium">Driver</label>
                                        <select class="form-select" name="mail_driver">
                                            <option value="smtp" {{ $settings->get('mail_driver') == 'smtp' ? 'selected' : '' }}>SMTP</option>
                                            <option value="sendmail" {{ $settings->get('mail_driver') == 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-medium">Host</label>
                                        <input type="text" class="form-control" name="mail_host" value="{{ $settings->get('mail_host') }}">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-medium">Port</label>
                                        <input type="text" class="form-control" name="mail_port" value="{{ $settings->get('mail_port') }}">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-medium">Username</label>
                                        <input type="text" class="form-control" name="mail_username" value="{{ $settings->get('mail_username') }}">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-medium">Password</label>
                                        <input type="password" class="form-control" name="mail_password" value="{{ $settings->get('mail_password') }}">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-medium">Encryption</label>
                                        <select class="form-select" name="mail_encryption">
                                            <option value="tls" {{ $settings->get('mail_encryption') == 'tls' ? 'selected' : '' }}>TLS</option>
                                            <option value="ssl" {{ $settings->get('mail_encryption') == 'ssl' ? 'selected' : '' }}>SSL</option>
                                            <option value="null" {{ $settings->get('mail_encryption') == 'null' ? 'selected' : '' }}>None</option>
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Update Config</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>