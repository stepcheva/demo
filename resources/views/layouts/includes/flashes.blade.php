@if(session()->has('success'))
    <div class="alert alert-success fade in alert-dismissable">
        {{ session()->get('success') }}
    </div>
@endif
@if(session()->has('error'))
    <div class="alert alert-danger fade in alert-dismissable">
        {{ session()->get('error') }}
    </div>
@endif
