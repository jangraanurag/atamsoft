<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Multi-Login - Angira Digital Services</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex flex-col justify-between min-h-screen">

  <!-- Main Login Container -->
  <div class="flex-grow flex items-center justify-center">
    <div class="bg-white shadow-lg rounded-2xl p-8 w-full max-w-md">

      <!-- Logo -->
      <div class="flex justify-center mb-6">
        <img src="{{ asset('images/logo.png')}}" alt="Atam Labels" class="h-16" />
      </div>

      <!-- Heading -->
      <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Login to Your Account</h2>

      <!-- Login Form -->
      <form action="{{ route('authenticate') }}" method="POST" class="space-y-4">
        @csrf
        <!-- Login Type -->
        <div>
          <label for="loginType" class="block text-sm font-medium text-gray-700">Login as</label>
          <select id="loginType" name="login_type"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
            <option value="admin">Admin</option>
            <option value="buyer">Buyer</option>
            <option value="operator">Operator</option>
          </select>
        </div>

        <!-- Email -->
        <div>
          <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
          <input id="email" name="email" type="email" required
                 class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" />
        </div>

        <!-- Password -->
        <div>
          <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
          <input id="password" name="password" type="password" required
                 class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" />
        </div>

        <!-- Submit -->
        <div>
          <button type="submit"
                  class="w-full py-2 px-4 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 transition">
            Login
          </button>
        </div>

        <!-- Forgot Password -->
        <div class="text-sm text-center mt-2">
          <a href="#" class="text-blue-600 hover:underline">Forgot your password?</a>
        </div>
      </form>
    </div>
  </div>

  <!-- Footer -->
  <footer class="text-center text-sm text-gray-600 py-4">
    Created by and Powered by <strong>Angira Digital Services</strong>
  </footer>

</body>
</html>
