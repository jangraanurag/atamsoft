<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>@yield('title','Atam Panel') - Atam Graphics</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  @yield('header')
<style>
.container {
max-width: 100% auto;
}
  </style>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">

  <!-- New Top Navigation -->
  <header class="bg-white shadow-md sticky top-0 z-50">
    <nav class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-16">
        <!-- Logo -->
        <div class="flex-shrink-0 flex items-center">
          <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10 w-auto" />
          {{-- <span class="text-xl font-bold text-gray-800 ml-2">Atam Graphics</span> --}}
        </div>
        
        <!-- Navigation Links -->
        <div class="hidden sm:ml-6 sm:flex sm:space-x-4">
            
            <a href="{{ route('order.new') }}" class="px-3 py-2 rounded-md text-sm font-medium text-gray-600 hover:bg-gray-100 flex items-center">
                <i class="fa-solid fa-plus mr-2"></i> New Order
            </a>

            <!-- Orders Dropdown -->
            <div class="relative group">
                <button class="px-3 py-2 rounded-md text-sm font-medium text-gray-600 hover:bg-gray-100 focus:outline-none flex items-center">
                    <i class="fa-solid fa-boxes-stacked mr-2"></i> Orders <i class="fa-solid fa-chevron-down ml-1 text-xs"></i>
                </button>
                <div class="absolute left-0 mt-0 w-48 bg-white rounded-md shadow-lg py-1 z-50 hidden group-hover:block border border-gray-200">
                    <a href="{{ route('order.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                        <i class="fa-solid fa-border-all mr-2"></i> All Orders
                    </a>
                    @if(isset($statuses_for_menu))
                        @foreach($statuses_for_menu as $status)
                            <a href="{{ route('order.index', ['status' => $status->status_name]) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                                <i class="fa-solid fa-tag mr-2"></i> {{ $status->status_name }}
                            </a>
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- Masters Dropdown -->
            <div class="relative group">
                <button class="px-3 py-2 rounded-md text-sm font-medium text-gray-600 hover:bg-gray-100 focus:outline-none flex items-center">
                    <i class="fa-solid fa-database mr-2"></i> Masters <i class="fa-solid fa-chevron-down ml-1 text-xs"></i>
                </button>
                <div class="absolute left-0 mt-0 w-48 bg-white rounded-md shadow-lg py-1 z-50 hidden group-hover:block border border-gray-200">
                    <a href="{{ route('brand.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center"><i class="fa-solid fa-copyright mr-2"></i> Brands</a>
                    <a href="{{ route('buyer.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center"><i class="fa-solid fa-users mr-2"></i> Buyers</a>
                    <a href="{{ route('supplier.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center"><i class="fa-solid fa-industry mr-2"></i> Suppliers</a>
                    <a href="{{ route('products.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center"><i class="fa-solid fa-box-open mr-2"></i> Products</a>
                    <a href="{{ route('shipper.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center"><i class="fa-solid fa-shipping-fast mr-2"></i> Shippers</a>
                    <a href="{{ route('labeltype.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center"><i class="fa-solid fa-tags mr-2"></i> Label Types</a>
                    <a href="{{ route('status.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center"><i class="fa-solid fa-check-square mr-2"></i> Statuses</a>
                    <a href="{{ route('unit.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center"><i class="fa-solid fa-ruler mr-2"></i> Units</a>
                    <a href="{{ route('dunit.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center"><i class="fa-solid fa-ruler-combined mr-2"></i> Dim. Units</a>
                    <a href="{{ route('colorset.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center"><i class="fa-solid fa-palette mr-2"></i> Color Sets</a>
                </div>
            </div>
            
            <a href="{{ route('monitoring.index') }}" class="px-3 py-2 rounded-md text-sm font-medium text-gray-600 hover:bg-gray-100 flex items-center">
                <i class="fa-solid fa-chart-line mr-2"></i> Monitoring
            </a>
        </div>
        
        <!-- Right Side -->
        <div class="flex items-center">
          <a href="{{ route('logout') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">
            <i class="fa-solid fa-right-from-bracket mr-1"></i>
            Logout
          </a>
        </div>
      </div>
    </nav>
  </header>

  <div class="container" style="max-width: 100% !important;" id="main-container">
    <!-- *** SIDEBAR IS REMOVED *** -->
    
    <!-- Main Content Area (now full width) -->
    <main id="main-content" class="flex-1 p-4 sm:p-6 lg:p-8">
      @yield('content')
    </main>
  </div>

  @yield('footer')
  
  <footer class="bg-white shadow text-center text-sm text-gray-600 py-4 mt-auto">
    Powered by <strong>Angira Digital Services</strong>
  </footer>

</body>
</html>