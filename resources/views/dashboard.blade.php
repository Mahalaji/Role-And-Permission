@extends('layouts.app')

@section('content')
<main id="main" class="main">
    <div class="dashboard-content">
        <div class="widget">
            <h2>Total Users</h2>
            <p class="widget-value">8</p>
        </div>
        <div class="widget">
            <h2>Total Blogs</h2>
            <p class="widget-value">4</p>
        </div>
        <div class="widget">
            <h2>Total News</h2>
            <p class="widget-value">4</p>

        </div>
        <div class="widget wide">
            <h2>Recent Activity</h2>
            <ul class="activity-list">
                <li>User signed up</li>
                <li>User Jane made a purchase</li>
                <li>User Bob updated their profile</li>
                <li>User Alice submitted a support ticket</li>
            </ul>
        </div>
    </div>
</main>
@endsection