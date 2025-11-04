<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>@yield('title','Atam Panel') - Atam Labels</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
@yield('head')

</head>
<body class="bg-gray-100 min-h-screen flex flex-col">

  <header class="bg-white shadow px-6 py-4 flex justify-between items-center">
    <div class="flex items-center space-x-3">
      <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10 w-auto" />
      <span class="text-xl font-bold text-gray-800">Atam Label</span>
    </div>

    <nav class="flex space-x-6 items-center text-sm text-gray-700">
      <a href="{{ route('order.new') }}" class="hover:text-blue-600 flex items-center gap-1"><i class="fa-solid fa-plus"></i> Create</a>
      <a href="{{ route('order.pending') }}" class="hover:text-yellow-600 flex items-center gap-1"><i class="fa-regular fa-clock"></i> Pending</a>
      <a href="{{ route('order.delivered') }}" class="hover:text-green-600 flex items-center gap-1"><i class="fa-solid fa-truck"></i> Delivered</a>
      <a href="{{ route('order.ready') }}" class="hover:text-purple-600 flex items-center gap-1"><i class="fa-solid fa-box"></i> Ready</a>
      <a href="{{ route('order.all') }}" class="hover:text-gray-800 flex items-center gap-1"><i class="fa-solid fa-list"></i> All Orders</a>
      <a href="{{ route('logout') }}" class="hover:text-red-600 flex items-center gap-1"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
    </nav>
  </header>

  <div class="flex flex-1">
    <aside class="w-64 bg-gray-800 text-white p-4">
      <nav>
        <ul>
          <li class="mb-2">
            <button onclick="toggleMenu()" class="w-full text-left flex justify-between items-center py-2 px-4 rounded hover:bg-gray-700 focus:outline-none">
              <span><i class="fas fa-cogs mr-2"></i>Masters</span>
              <i id="arrow" class="fas fa-chevron-down"></i>
            </button>
            <ul id="dropdown-menu" class="hidden mt-2 pl-4">
              <li><a href="{{ route('brand.index') }}" class="block py-2 px-4 rounded hover:bg-gray-700"><i class="fas fa-tags mr-2"></i>Brands</a></li>
              <li><a href="{{ route('buyer.index') }}" class="block py-2 px-4 rounded hover:bg-gray-700"><i class="fas fa-users mr-2"></i>Buyers</a></li>
              <li><a href="{{ route('colorset.index') }}" class="block py-2 px-4 rounded hover:bg-gray-700"><i class="fas fa-palette mr-2"></i>Colorset</a></li>
                <li><a href="{{ route('unit.index') }}" class="block py-2 px-4 rounded hover:bg-gray-700"><i class="fas fa-ruler mr-2"></i>Unit</a></li>
                <li><a href="{{ route('labeltype.index') }}" class="block py-2 px-4 rounded hover:bg-gray-700"><i class="fas fa-tag mr-2"></i>Label Type</a></li>
                <li><a href="{{ route('status.index') }}" class="block py-2 px-4 rounded hover:bg-gray-700"><i class="fas fa-clipboard-list mr-2"></i>Status</a></li>
                <li><a href="{{ route(name: 'products.index') }}" class="block py-2 px-4 rounded hover:bg-gray-700"><i class="fas fa-tag mr-2"></i>Products</a></li>
                <li><a href="{{ route('shipper.index') }}" class="block py-2 px-4 rounded hover:bg-gray-700"><i class="fas fa-tag mr-2"></i>Shippers</a></li>
                <li><a href="{{ route('status.index') }}" class="block py-2 px-4 rounded hover:bg-gray-700"><i class="fas fa-tag mr-2"></i>Status</a></li>



            </ul>
          </li>
          <li class="mb-2">
            <button onclick="toggleMenu1()" class="w-full text-left flex justify-between items-center py-2 px-4 rounded hover:bg-gray-700 focus:outline-none">
              <span><i class="fas fa-cogs mr-2"></i>Orders</span>
              <i id="arrow1" class="fas fa-chevron-down"></i>
            </button>
            <ul id="dropdown-menu1" class="hidden mt-2 pl-4">
              <li><a href="{{ route('order.all') }}" class="block py-2 px-4 rounded hover:bg-gray-700"><i class="fas fa-tags mr-2"></i>All Orders</a></li>
              <li><a href="{{ route('order.new') }}" class="block py-2 px-4 rounded hover:bg-gray-700"><i class="fas fa-users mr-2"></i>New Order</a></li>



            </ul>
          </li>
        </ul>
      </nav>
    </aside>

    <main class="flex-1 p-6">
      @yield('content')
    </main>
  </div>

  @yield('footer')
  <footer class="bg-white shadow text-center text-sm text-gray-600 py-4">
    Powered by <strong>Angira Digital Services</strong>
  </footer>

  <script>
    function toggleMenu() {
      const menu = document.getElementById('dropdown-menu');
      const arrow = document.getElementById('arrow');
      menu.classList.toggle('hidden');
      arrow.classList.toggle('fa-chevron-down');
      arrow.classList.toggle('fa-chevron-up');
    }
    function toggleMenu1() {
      const menu = document.getElementById('dropdown-menu1');
      const arrow = document.getElementById('arrow1');
      menu.classList.toggle('hidden');
      arrow.classList.toggle('fa-chevron-down');
      arrow.classList.toggle('fa-chevron-up');
    }
  </script>

</body>
</html>
