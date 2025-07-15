<!DOCTYPE html>
<html lang="en">
    <head>
        @include('template.head')
    </head>
  <body>
    <div class="wrapper">
      <!-- Sidebar -->
      @include('components.' . Auth::user()->getRoleNames()->first() . '.sidebar')
      <!-- End Sidebar -->

      <div class="main-panel">
        <div class="main-header">
          <div class="main-header-logo">
            <!-- Logo Header -->
            @include('template.logo-header')
            <!-- End Logo Header -->
          </div>
          <!-- Navbar Header -->
          @include('template.navbar-header')
          <!-- End Navbar -->
        </div>

        <div class="container">
          <div class="page-inner">
            {{-- Start from here. --}}
            @yield('content')
          </div>
        </div>

        <footer class="footer">
          @include('template.footer')
        </footer>
      </div>
    </div>   @include('template.script')
   
   {{-- Stack for page-specific scripts --}}
   @stack('scripts')
  </body>
</html>