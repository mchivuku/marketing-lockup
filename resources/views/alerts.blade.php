@if(\Session::has('flash-message'))
    <div class="alert-box fail">
        <h2>{{ Session::get('flash-message') }}</h2>
    </div>
@endif