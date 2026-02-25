<x-app-layout title="Social Links | Fitx Admin">
    <div class="content">
        <div class="container-fluid">
            <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                <div class="flex-grow-1"><h4 class="fs-18 fw-semibold m-0">Social Links</h4></div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Social Links</li>
                    </ol>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card bg-light-subtle border shadow-none">
                        <div class="card-header bg-transparent border-bottom">
                            <h5 class="card-title mb-0">Social Media URLs</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.settings.update-social-links') }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    @php
                                        $socials = [
                                            'facebook_url' => 'Facebook',
                                            'x_url' => 'X (Twitter)',
                                            'instagram_url' => 'Instagram',
                                            'whatsapp_url' => 'WhatsApp',
                                            'youtube_url' => 'YouTube',
                                            'linkedin_url' => 'LinkedIn'
                                        ];
                                    @endphp
                                    @foreach($socials as $key => $label)
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-medium">{{ $label }} URL</label>
                                        <input type="url" class="form-control" name="{{ $key }}" value="{{ $settings->get($key) }}" placeholder="https://...">
                                    </div>
                                    @endforeach
                                </div>
                                <button type="submit" class="btn btn-primary">Update Links</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>