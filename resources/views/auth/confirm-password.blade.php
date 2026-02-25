<x-guest-layout title="Secure Area | Fitx">
    <div class="account-page">
        <div class="container-fluid p-0">
            <div class="row align-items-center justify-content-center g-0 px-3 py-3 vh-100">
                <div class="col-xxl-6">
                    <div class="row">
                        <div class="col-xl-4 col-md-4 mx-auto">
                             <div class="mb-3 p-0 text-center">
                                    <div class="auth-brand">
                                        <a href="{{ url('/') }}" class="logo logo-light">
                                            <span class="logo-lg">
                                                <img src="{{ asset('assets/images/fitx-logo.png') }}" alt="Fitx" height="24">
                                            </span>
                                        </a>
                                        <a href="{{ url('/') }}" class="logo logo-dark">
                                            <span class="logo-lg">
                                                <img src="{{ asset('assets/images/fitx-logo.png') }}" alt="Fitx" height="24">
                                            </span>
                                        </a>
                                    </div>
                                </div>
                        </div>
                        <div class="col-xl-4 col-md-4 mx-auto">
                            <div>
                               

                                <div class="auth-title-section mb-3 text-center"> 
                                    <h4 class="text-dark fw-semibold mb-0">Secure Area</h4>
                                    <p class="text-muted fs-14 mb-0 mt-2">
                                        {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
                                    </p>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <div class="mb-0 p-0 p-lg-3">
                                        <div class="mb-0 border-0 p-md-3 p-lg-0">
                                            <div class="pt-0">
                                                
                                                <form method="POST" action="{{ route('password.confirm') }}" class="mt-0 mb-4" data-form>
                                                    @csrf

                                                    <div class="form-group mb-3">
                                                        <label for="password" class="form-label">Password</label>
                                                        <input class="form-control @error('password') is-invalid @enderror" 
                                                               type="password" 
                                                               id="password" 
                                                               name="password" 
                                                               required 
                                                               autocomplete="current-password"
                                                               placeholder="Enter your password">
                                                        
                                                        @error('password')
                                                            <div class="invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>

                                                    <div class="form-group mb-0 mt-4">
                                                        <div class="d-grid">
                                                            <button class="btn btn-primary fw-semibold" type="submit"> 
                                                                {{ __('Confirm') }} 
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>