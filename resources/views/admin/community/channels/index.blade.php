<x-app-layout title="Studio Rooms | BestBusinessCoachIndia">
    <div class="content">
        <div class="container-fluid">
            {{-- Header & Breadcrumbs --}}
            <div class="py-3 d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="fs-18 fw-semibold m-0">Studio Room Management</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Community Channels</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="row mt-3">
                {{-- Left Side: Create Form --}}
                <div class="col-xl-4 col-lg-5">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-transparent border-bottom">
                            <h5 class="card-title mb-0">Create New Room</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('community-channels.store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Room Name</label>
                                    <input type="text" name="name" id="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        placeholder="e.g. Mixing Lounge" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3 position-relative">
                                    <label class="form-label fw-bold">Icon (MDI Search)</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i id="iconPreview"
                                                class="mdi mdi-dots-horizontal fs-18"></i></span>
                                        <input type="text" name="icon" id="icon_class" class="form-control"
                                            placeholder="Type to search icons..." autocomplete="off" required>
                                    </div>

                                    {{-- Icon Suggestion Dropdown --}}
                                    <div id="iconDropdown"
                                        class="position-absolute w-100 bg-white border rounded shadow-lg mt-1 z-3"
                                        style="display: none; max-height: 250px; overflow-y: auto;">
                                        <div id="iconList" class="d-flex flex-wrap p-2 gap-1">
                                            {{-- Icons appended via JS --}}
                                        </div>
                                    </div>
                                    <small class="text-muted">Search by keyword (e.g. music, chat, account)</small>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Sort Order</label>
                                        <input type="number" name="sort_order" class="form-control" value="0">
                                    </div>
                                    <div class="col-md-6 mb-3 d-flex align-items-end">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="is_private"
                                                id="isPrivate">
                                            <label class="form-check-label" for="isPrivate">Private Room</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Description</label>
                                    <textarea name="description" class="form-control" rows="3" placeholder="Purpose of this room..."></textarea>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="mdi mdi-plus-circle-outline me-1"></i> Create Channel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Right Side: Channels List --}}
                <div class="col-xl-8 col-lg-7">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-transparent border-bottom">
                            <h5 class="card-title mb-0">Existing Channels</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="ps-3" style="width: 50px;">Icon</th>
                                            <th>Channel Details</th>
                                            <th class="text-center">Order</th>
                                            <th class="text-end pe-3">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($channels as $channel)
                                            <tr>
                                                <td class="ps-3 text-center">
                                                    <div class="avatar-sm bg-primary-subtle rounded text-primary d-flex align-items-center justify-content-center"
                                                        style="width: 40px; height: 40px;">
                                                        <i class="{{ $channel->icon }} fs-20"></i>
                                                    </div>
                                                </td>
                                                <td>
                                                    <h6 class="mb-0 fs-14">{{ $channel->name }}</h6>
                                                    <span
                                                        class="text-muted fs-12">{{ Str::limit($channel->description, 50) }}</span>
                                                    @if ($channel->is_private)
                                                        <span
                                                            class="badge bg-danger-subtle text-danger ms-1">Private</span>
                                                    @endif
                                                </td>
                                                <td class="text-center font-monospace">{{ $channel->sort_order }}</td>
                                                <td class="text-end pe-3">
                                                    <div class="d-flex justify-content-end gap-2">
                                                        <form
                                                            action="{{ route('community-channels.destroy', $channel->id) }}"
                                                            method="POST">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-soft-danger"
                                                                onclick="return confirm('Delete this room permanently?')">
                                                                <i class="mdi mdi-trash-can-outline fs-16"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-5">
                                                    <div class="text-muted">
                                                        <i class="mdi mdi-folder-outline display-4 mb-3"></i>
                                                        <p>No channels found.</p>
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
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                let allIcons = [];
                const iconInput = $('#icon_class');
                const iconDropdown = $('#iconDropdown');
                const iconList = $('#iconList');
                const iconPreview = $('#iconPreview');

                // 1. Fetch Local JSON
                fetch("{{ asset('assets/icons.json') }}")
                    .then(response => response.json())
                    .then(data => {
                        allIcons = data.i;
                        console.log('MDI Data parsed successfully');
                    })
                    .catch(err => console.error('Error loading icons.json:', err));

                // 2. Search Logic
                iconInput.on('input', function() {
                    const query = $(this).val().toLowerCase().replace('mdi-', '').trim();
                    iconList.empty();

                    if (query.length < 2) {
                        iconDropdown.hide();
                        return;
                    }

                    const filtered = allIcons.filter(icon => icon.n.includes(query)).slice(0, 48);

                    if (filtered.length > 0) {
                        filtered.forEach(icon => {
                            const fullClass = `mdi mdi-${icon.n}`;
                            iconList.append(`
                                <button type="button" class="btn btn-outline-light border p-0 d-flex align-items-center justify-content-center icon-select-btn"
                                        data-class="${fullClass}" title="${icon.n}" style="width: 42px; height: 42px;">
                                    <i class="${fullClass} fs-20 text-dark"></i>
                                </button>
                            `);
                        });
                        iconDropdown.show();
                    } else {
                        iconList.append(
                            '<div class="p-3 w-100 text-center text-muted">No matching icons.</div>');
                        iconDropdown.show();
                    }
                });

                // 3. Selection Logic
                $(document).on('click', '.icon-select-btn', function() {
                    const selectedClass = $(this).data('class');
                    iconInput.val(selectedClass);
                    iconPreview.attr('class', selectedClass + ' fs-18 text-primary');
                    iconDropdown.hide();
                });

                // 4. Close dropdown
                $(document).on('click', function(e) {
                    if (!$(e.target).closest('.position-relative').length) {
                        iconDropdown.hide();
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>
