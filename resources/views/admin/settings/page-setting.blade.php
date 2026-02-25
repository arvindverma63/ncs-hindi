<x-app-layout title="Page Settings | Fitx Admin">
    <div class="content">
        <div class="container-fluid">
            <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                <div class="flex-grow-1"><h4 class="fs-18 fw-semibold m-0">Page Settings</h4></div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Page Settings</li>
                    </ol>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card bg-light-subtle border shadow-none">
                        <div class="card-header bg-transparent border-bottom">
                            <h5 class="card-title mb-0">Page Titles</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.settings.update-page-setting') }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label class="form-label fw-medium">Home Page Title</label>
                                    <input type="text" class="form-control" name="home_page_title" value="{{ $settings->get('home_page_title') }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-medium">About Page Title</label>
                                    <input type="text" class="form-control" name="about_page_title" value="{{ $settings->get('about_page_title') }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-medium">Contact Page Title</label>
                                    <input type="text" class="form-control" name="contact_page_title" value="{{ $settings->get('contact_page_title') }}">
                                </div>
                                <button type="submit" class="btn btn-primary">Update Page Settings</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>