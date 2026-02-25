<x-app-layout title="Dashboard | BestBusinessCoachIndia Admin">
    <div class="container-fluid">
        <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
            <div class="flex-grow-1">
                <h4 class="fs-18 fw-semibold m-0">Platform Overview</h4>
                <p class="text-muted fs-14 mb-0">Manage coaches, rankings, and seeker inquiries.</p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 col-xxl-3">
                <a href="{{ route('admin.coaches.index', ['status' => 'approved']) }}">
                    <div class="card">
                        <div class="card-body">
                            <div class="widget-first">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rounded-circle bg-success-subtle p-2 me-2">
                                        <iconify-icon icon="tabler:certificate"
                                            class="align-middle text-success fs-26 mb-0"></iconify-icon>
                                    </div>
                                    <p class="mb-0 text-dark fs-16">Verified Coaches</p>
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <h3 class="fs-24 fw-medium text-dark mb-0 me-3">{{ $stats['verified_coaches'] }}
                                    </h3>
                                    <div class="d-flex align-items-center">
                                        <span class="me-2 rounded-2 badge fs-12 badge-soft-success fw-medium">
                                            <i class="mdi mdi-trending-up fs-14"></i> Active
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-6 col-xxl-3">
                <a href="{{ route('admin.coaches.index', ['status' => 'pending']) }}">
                    <div class="card">
                        <div class="card-body">
                            <div class="widget-first">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rounded-circle bg-warning-subtle p-2 me-2">
                                        <iconify-icon icon="tabler:clipboard-check"
                                            class="align-middle text-warning fs-26 mb-0"></iconify-icon>
                                    </div>
                                    <p class="mb-0 text-dark fs-16">Pending Approvals</p>
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <h3 class="fs-24 fw-medium text-dark mb-0 me-3">{{ $stats['pending_coaches'] }}</h3>
                                    <div class="d-flex align-items-center">
                                        @if ($stats['pending_coaches'] > 0)
                                            <span class="me-2 rounded-2 badge fs-12 badge-soft-warning fw-medium">Action
                                                Needed</span>
                                        @else
                                            <span
                                                class="me-2 rounded-2 badge fs-12 badge-soft-light text-muted fw-medium">All
                                                Clear</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-6 col-xxl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="widget-first">
                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-circle bg-primary-subtle p-2 me-2">
                                    <iconify-icon icon="tabler:messages"
                                        class="align-middle text-primary fs-26 mb-0"></iconify-icon>
                                </div>
                                <p class="mb-0 text-dark fs-16">Active Inquiries</p>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <h3 class="fs-24 fw-medium text-dark mb-0 me-3">{{ $stats['active_inquiries'] }}</h3>
                                <div class="d-flex align-items-center">
                                    <span class="me-2 rounded-2 badge fs-12 badge-soft-light text-muted fw-medium">
                                        <i class="mdi mdi-trending-neutral fs-14"></i> 0%
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xxl-3">
                <a href="{{ route('admin.seekers.index') }}">
                    <div class="card">
                        <div class="card-body">
                            <div class="widget-first">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rounded-circle bg-info-subtle p-2 me-2">
                                        <iconify-icon icon="tabler:users"
                                            class="align-middle text-info fs-26 mb-0"></iconify-icon>
                                    </div>
                                    <p class="mb-0 text-dark fs-16">Registered Seekers</p>
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <h3 class="fs-24 fw-medium text-dark mb-0 me-3">{{ $stats['total_seekers'] }}</h3>
                                    <div class="d-flex align-items-center">
                                        <p class="text-muted fs-14 mb-0 text-center">Lifetime users</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 col-xl-9">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title text-dark mb-0">Engagement Overview</h5>
                    </div>
                    <div class="card-body">
                        <div class="p-2">
                            <div id="overview" class="apex-charts"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12 col-xl-3">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title text-dark mb-0">Top Categories</h5>
                    </div>
                    <div class="card-body">
                        <div id="categoryChart" class="apex-charts"></div>

                        @if (empty($chartData['labels']))
                            <div class="text-center mt-3">
                                <p class="text-muted">No data available.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div class="card overflow-hidden">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title text-dark mb-0">Recent Coach Onboarding Requests</h5>
                        <a href="{{ route('admin.coaches.index', ['status' => 'pending']) }}"
                            class="btn btn-sm btn-light">View All Pending</a>
                    </div>
                    <div class="card-body mt-0 p-0">
                        <div class="table-responsive mt-0">
                            <table class="table table-custom table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="text-muted">Coach Name</th>
                                        <th class="text-muted">Category</th>
                                        <th class="text-muted">Location</th>
                                        <th class="text-muted">Experience</th>
                                        <th class="text-muted">Status</th>
                                        <th class="text-muted">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentRequests as $request)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $request->user->profile_image ?? 'https://ui-avatars.com/api/?name=' . $request->user->name }}"
                                                        class="avatar-sm rounded-circle me-2" alt="">
                                                    <div>
                                                        <h6 class="mb-0">{{ $request->user->name }}</h6>
                                                        <small class="text-muted">{{ $request->designation }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @foreach ($request->categories as $cat)
                                                    <span class="badge bg-light text-dark">{{ $cat->name }}</span>
                                                @endforeach
                                            </td>
                                            <td>{{ $request->city }}</td>
                                            <td>{{ $request->experience_years }} Years</td>
                                            <td><span class="badge bg-warning text-dark">Pending</span></td>
                                            <td>
                                                <a href="{{ route('admin.coaches.edit', $request->id) }}"
                                                    class="btn btn-sm btn-soft-primary">Review</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4">
                                                <div class="text-muted">
                                                    <iconify-icon icon="tabler:database-off"
                                                        class="fs-24 mb-2"></iconify-icon>
                                                    <p class="mb-0">No pending onboarding requests found.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
        <script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>

        <script src="{{ asset('assets/js/pages/crm-dashboard.init.js') }}"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Prepare Data from PHP
                var catLabels = @json($chartData['labels']);
                var catSeries = @json($chartData['series']);

                if (catSeries.length > 0) {
                    var options = {
                        series: catSeries,
                        chart: {
                            type: 'donut',
                            height: 270,
                        },
                        labels: catLabels,
                        colors: ['#556ee6', '#34c38f', '#f1b44c', '#f46a6a', '#50a5f1'],
                        legend: {
                            position: 'bottom',
                            horizontalAlign: 'center',
                        },
                        dataLabels: {
                            enabled: false
                        },
                        responsive: [{
                            breakpoint: 480,
                            options: {
                                chart: {
                                    width: 200
                                },
                                legend: {
                                    position: 'bottom'
                                }
                            }
                        }]
                    };

                    var chart = new ApexCharts(document.querySelector("#categoryChart"), options);
                    chart.render();
                }
            });
        </script>
    @endpush

</x-app-layout>
