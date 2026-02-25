<x-app-layout title="Edit Coach | BestBusinessCoachIndia">

    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <style>
            .select2-container .select2-selection--single {
                height: 38px;
                line-height: 38px;
            }

            .select2-container--default .select2-selection--multiple .select2-selection__choice {
                background-color: #556ee6;
                border: none;
                color: white;
            }

            .select2-container--default .select2-results__option[aria-selected=true] {
                display: none !important;
            }
        </style>
    @endpush

    <div class="content">
        <div class="container-fluid">
            <div class="py-3 d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="fs-18 fw-semibold m-0">Edit Coach Profile</h4>
                    <p class="mb-0 text-muted">User: {{ $coach->user->name }}</p>
                </div>
                <div class="d-flex align-items-center gap-2">
                    @php
                        $statusClasses = [
                            'pending' => 'bg-warning text-dark',
                            'approved' => 'bg-success',
                            'rejected' => 'bg-danger',
                        ];
                        $statusLabels = [
                            'pending' => 'Pending Approval',
                            'approved' => 'Verified & Approved',
                            'rejected' => 'Rejected',
                        ];
                    @endphp
                    <span class="badge {{ $statusClasses[$coach->approval_status] }} fs-14 px-3 py-2">
                        {{ $statusLabels[$coach->approval_status] }}
                    </span>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <form id="editCoachForm" action="{{ route('admin.coaches.update', $coach->id) }}"
                                method="POST">
                                @csrf
                                @method('PUT')

                                <h5 class="mb-3 text-uppercase bg-light p-2">Personal Details</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Gender</label>
                                        <select name="gender" class="form-select">
                                            <option value="">Select Gender</option>
                                            <option value="male"
                                                {{ old('gender', $coach->gender) == 'male' ? 'selected' : '' }}>Male
                                            </option>
                                            <option value="female"
                                                {{ old('gender', $coach->gender) == 'female' ? 'selected' : '' }}>Female
                                            </option>
                                            <option value="other"
                                                {{ old('gender', $coach->gender) == 'other' ? 'selected' : '' }}>Other
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label d-block">Show Personal Details on Profile?</label>
                                        <div class="form-check form-switch mt-2">
                                            <input class="form-check-input" type="checkbox" name="show_personal_details"
                                                value="1" id="showDetails"
                                                {{ old('show_personal_details', $coach->show_personal_details) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="showDetails">Visible to Seekers</label>
                                        </div>
                                    </div>
                                </div>

                                <h5 class="mb-3 text-uppercase bg-light p-2">Professional Details</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Designation</label>
                                        <input type="text" name="designation" class="form-control"
                                            value="{{ old('designation', $coach->designation) }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Company</label>
                                        <input type="text" name="company_name" class="form-control"
                                            value="{{ old('company_name', $coach->company_name) }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">City <span class="text-danger">*</span></label>
                                        <input type="text" name="city" class="form-control"
                                            value="{{ old('city', $coach->city) }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">LinkedIn URL</label>
                                        <input type="url" name="linkedin_url" class="form-control"
                                            placeholder="https://linkedin.com/..."
                                            value="{{ old('linkedin_url', $coach->linkedin_url) }}">
                                    </div>

                                    <div class="col-12 mb-3">
                                        <label class="form-label">Categories</label>
                                        <select name="categories[]" class="form-control select2" multiple="multiple">
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ $coach->categories->contains($category->id) ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-12 mb-3">
                                        <label class="form-label">Bio</label>
                                        <textarea name="bio" class="form-control" rows="5">{{ old('bio', $coach->bio) }}</textarea>
                                    </div>

                                    <div class="col-12 mt-2">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="is_featured"
                                                value="1" id="featSwitch"
                                                {{ $coach->is_featured ? 'checked' : '' }}>
                                            <label class="form-check-label" for="featSwitch">Feature on Homepage</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4 text-end">
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card bg-light-subtle border-light">
                        <div class="card-header bg-light">
                            <h5 class="card-title text-dark mb-0">Approval Actions</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.coaches.update-status', $coach->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <div class="d-grid gap-2">
                                    @if ($coach->approval_status !== 'approved')
                                        <button name="status" value="approved" class="btn btn-success">
                                            <i class="mdi mdi-check-circle-outline me-1"></i> Approve Coach
                                        </button>
                                    @endif
                                    @if ($coach->approval_status !== 'rejected')
                                        <button name="status" value="rejected" class="btn btn-outline-danger">
                                            <i class="mdi mdi-close-circle-outline me-1"></i> Reject Application
                                        </button>
                                    @endif
                                    @if ($coach->approval_status !== 'pending')
                                        <button name="status" value="pending" class="btn btn-link text-muted">Mark
                                            as Pending</button>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Platform Stats</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Total Views</span><span
                                    class="fw-bold">{{ $coach->profile_views }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Interactions</span><span
                                    class="fw-bold">{{ $coach->total_interactions }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            $(document).ready(function() {
                $('.select2').select2({
                    width: '100%',
                    templateResult: function(data) {
                        if (!data.id || data.selected) return null;
                        return data.text;
                    }
                });

                $('input, select, textarea').on('input change', function() {
                    $(this).removeClass('is-invalid');
                    $(this).parent().find('.error-text').remove();
                });

                $('#editCoachForm').on('submit', function(e) {
                    let isValid = true;
                    $('.error-text').remove();
                    $('.form-control, .form-select').removeClass('is-invalid');

                    let city = $('input[name="city"]');
                    if (city.val().trim() === '') {
                        showError(city, 'City is required.');
                        isValid = false;
                    }

                    let linkedin = $('input[name="linkedin_url"]');
                    if (linkedin.val().trim() !== '') {
                        let urlPattern = /^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/;
                        if (!urlPattern.test(linkedin.val())) {
                            showError(linkedin, 'Please enter a valid URL.');
                            isValid = false;
                        }
                    }

                    if (!isValid) {
                        e.preventDefault();
                        $('html, body').animate({
                            scrollTop: $(".is-invalid").first().offset().top - 100
                        }, 500);
                    }
                });

                function showError(element, message) {
                    element.addClass('is-invalid');
                    element.after('<span class="text-danger error-text small mt-1 d-block">' + message + '</span>');
                }
            });
        </script>
    @endpush
</x-app-layout>
