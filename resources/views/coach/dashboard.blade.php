<x-coach-layout title="Dashboard Overview">
    <div class="row mt-2">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="avatar-md bg-soft-primary rounded text-primary d-flex align-items-center justify-content-center">
                            <iconify-icon icon="tabler:news" class="fs-24"></iconify-icon>
                        </div>
                        <div class="ms-3">
                            <p class="text-muted mb-1 fw-medium">Total Blogs</p>
                            <h4 class="mb-0">{{ $stats['total_blogs'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="avatar-md bg-soft-info rounded text-info d-flex align-items-center justify-content-center">
                            <iconify-icon icon="tabler:eye" class="fs-24"></iconify-icon>
                        </div>
                        <div class="ms-3">
                            <p class="text-muted mb-1 fw-medium">Total Views</p>
                            <h4 class="mb-0">{{ number_format($stats['total_views']) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="avatar-md bg-soft-success rounded text-success d-flex align-items-center justify-content-center">
                            <iconify-icon icon="tabler:circle-check" class="fs-24"></iconify-icon>
                        </div>
                        <div class="ms-3">
                            <p class="text-muted mb-1 fw-medium">Published</p>
                            <h4 class="mb-0">{{ $stats['published_posts'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="avatar-md bg-soft-warning rounded text-warning d-flex align-items-center justify-content-center">
                            <iconify-icon icon="tabler:message-dots" class="fs-24"></iconify-icon>
                        </div>
                        <div class="ms-3">
                            <p class="text-muted mb-1 fw-medium">Comments</p>
                            <h4 class="mb-0">{{ $stats['pending_comments'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="card-title mb-0">Top Performing Articles</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap table-hover mb-0">
                            <thead class="bg-light-subtle">
                                <tr>
                                    <th class="ps-3">Article Title</th>
                                    <th class="text-end pe-3">Views</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topBlogs as $blog)
                                    <tr>
                                        <td class="ps-3">
                                            <span class="fw-medium">{{ Str::limit($blog->title, 50) }}</span>
                                        </td>
                                        <td class="text-end pe-3">
                                            <span class="badge bg-soft-info text-info">{{ number_format($blog->view_count) }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center py-4 text-muted">No data available.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="card-title mb-0">Recent Comments</h5>
                </div>
                <div class="card-body">
                    <div class="comment-feed">
                        @forelse($recentComments as $comment)
                            <div class="d-flex mb-3 pb-3 border-bottom-dashed">
                                <div class="flex-shrink-0">
                                    <div class="avatar-sm bg-soft-secondary rounded-circle text-secondary d-flex align-items-center justify-content-center">
                                        {{ strtoupper(substr($comment->name ?? $comment->user->name, 0, 1)) }}
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-2">
                                    <h6 class="mb-1 fs-14">{{ $comment->name ?? $comment->user->name }}</h6>
                                    <p class="text-muted mb-1 small">{{ Str::limit($comment->comment, 80) }}</p>
                                    <span class="text-primary fs-12">On: {{ Str::limit($comment->blog->title, 30) }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 text-muted">No recent comments.</div>
                        @endforelse
                    </div>
                    <a href="" class="btn btn-soft-primary btn-sm w-100 mt-2">View All Comments</a>
                </div>
            </div>
        </div>
    </div>
</x-coach-layout>