<x-app-layout>
    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">All Pages</h4>
        </div>
        <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Pages</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Website Pages</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" style="width: 5%;">#</th>
                                    <th scope="col">Page Name</th>
                                    <th scope="col" class="text-end" style="width: 15%;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pages as $key => $name)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm flex-shrink-0 me-2">
                                                    <span
                                                        class="avatar-title bg-soft-primary text-primary rounded-circle fs-4">
                                                        <i class="bi bi-file-earmark-text"></i>
                                                    </span>
                                                </div>
                                                <h6 class="mb-0">{{ $name }}</h6>
                                            </div>
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('admin.pages.edit', $key) }}"
                                                class="btn btn-sm btn-soft-primary">
                                                <i class="bi bi-pencil-square me-1"></i> Edit Content
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
