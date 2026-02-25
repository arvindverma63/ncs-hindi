<x-guest-layout title="Forgot Password | Fitx">
    <div class="account-page">
        <div class="container-fluid p-0">
            <div class="row align-items-center justify-content-center g-0 px-3 py-3 vh-100">
                <div class="col-xxl-6">
                    <div class="row">
                     <div class="col-xl-4 col-md-4 mx-auto login-logo">
                                 <div class="mb-0 p-0 text-center">
                                <div class="auth-brand">
                                    <a href="{{ url('/') }}" class="logo logo-light">
                                        <span class="logo-lg">
                                            <img src="{{ asset('assets/images/fitx-logo.png') }}" alt="Fitx"
                                                height="24">
                                        </span>
                                    </a>
                                    <a href="{{ url('/') }}" class="logo logo-dark">
                                        <span class="logo-lg">
                                            <img src="{{ asset('assets/images/fitx-logo.png') }}" alt="Fitx"
                                                height="24">
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-4 mx-auto login-wrap">
                            <div>
                              

                                <div class="auth-title-section mb-3 text-center"> 
                                    <h3 class="text-dark fw-medium mb-1">Forgot Password?</h3>
                                    <p class="text-muted fs-14 mb-0">Enter your email to receive reset instructions.</p>
                                </div>
                            </div>

                            <div class="">
                                <div class="">
                                    <div class="mb-0 p-0 p-lg-3">
                                        <div class="mb-0 border-0 p-md-3 p-lg-0">
                                            <div class="pt-0">
                                                
                                                <form method="POST" action="{{ route('password.email') }}" class="mt-0 mb-4" data-form>
                                                    @csrf

                                                    <div class="form-group mb-3">
                                                        <label for="email" class="form-label">Email Address</label>
                                                        <input class="form-control @error('email') is-invalid @enderror" 
                                                               type="email" 
                                                               id="email" 
                                                               name="email" 
                                                               value="{{ old('email') }}" 
                                                               required 
                                                               autofocus 
                                                               placeholder="Enter your email">
                                                        
                                                        @error('email')
                                                            <div class="invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                    
                                                    <div class="form-group mb-0 mt-4">
                                                        <div class="d-grid">
                                                            <button class="login-btn fw-semibold" type="submit"> Email Password Reset Link </button>
                                                        </div>
                                                    </div>
                                                </form>
                                                <div class="text-center text-muted options">
                                                    <div class="form-check">
                                                                <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
                                                                <label class="form-check-label text-white" for="remember_me">Remember it ?</label>
                                                            </div>
                                                    <a class='ms-2 text-muted fs-8' href="{{ route('login') }}">Back to Login</a>
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
    </div>
</x-guest-layout>