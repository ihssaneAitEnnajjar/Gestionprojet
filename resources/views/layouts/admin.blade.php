<div class="min-h-screen bg-gray-100">
    @include('layouts.navigation')

    </Sidebar>

    <!-- Page Content -->
    <main>
        {{ $slot }}
    </main>
</div>
