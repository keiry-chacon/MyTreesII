<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Successful</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body class="bg-gray-100">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full text-center">
            <!-- Success Message -->
            <h1 class="text-3xl font-semibold text-green-600 mb-4">Purchase Successful!</h1>
            <p class="text-lg text-gray-700 mb-6">Thank you for your purchase. You will receive an email confirmation shortly.</p>
            
            <!-- Icon for Confirmation -->
            <div class="mb-6">
                <i class="fas fa-check-circle text-6xl text-green-500"></i>
            </div>
            
            <!-- Button to redirect to the homepage -->
            <a href="<?= site_url('/friend/dashboard'); ?>" class="inline-block bg-blue-500 text-white py-2 px-6 rounded-lg text-lg hover:bg-blue-600 transition duration-200">
                Go to Home
            </a>
        </div>
    </div>
</body>

</html>


