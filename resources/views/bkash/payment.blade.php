<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>bKash Payment Simulation</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .bkash-pink { background-color: #E2136E; }
        .bkash-text { color: #E2136E; }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white rounded-lg shadow-xl overflow-hidden max-w-sm w-full">
        <div class="bkash-pink p-4 text-center">
            <img src="https://logos-download.com/wp-content/uploads/2022/01/BKash_Logo_icon-700x662.png" alt="bKash" class="h-12 mx-auto filter brightness-0 invert">
        </div>
        
        <div class="p-6">
            <div class="text-center mb-6">
                <p class="text-gray-600 text-sm">Merchant</p>
                <p class="font-bold text-lg">CafeTrack Campus</p>
                <p class="text-gray-600 text-sm mt-2">Amount</p>
                <p class="font-bold text-2xl bkash-text">৳ {{ number_format($order->total, 2) }}</p>
            </div>

            <form action="{{ route('orders.mark-paid', $order) }}" method="POST" id="bkashForm">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="phone">
                        Your bKash Account Number
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-[#E2136E]" id="phone" type="text" placeholder="e.g 01XXXXXXXXX" required>
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="pin">
                        bKash PIN
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline focus:border-[#E2136E]" id="pin" type="password" placeholder="Enter PIN" required>
                </div>
                <div class="flex items-center justify-between">
                    <button class="bkash-pink hover:bg-pink-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full transition" type="button" onclick="simulatePayment()">
                        Confirm Payment
                    </button>
                </div>
                <div class="text-center mt-4">
                    <a href="{{ route('orders.show', $order) }}" class="text-sm text-gray-500 hover:text-gray-800">Cancel Payment</a>
                </div>
            </form>

            <div id="loading" class="hidden text-center py-8">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-[#E2136E] mx-auto"></div>
                <p class="mt-4 text-gray-600 font-medium">Processing payment...</p>
            </div>
        </div>
    </div>

    <script>
        function simulatePayment() {
            const phone = document.getElementById('phone').value;
            const pin = document.getElementById('pin').value;
            
            if(!phone || !pin) {
                alert("Please enter phone number and PIN");
                return;
            }

            document.getElementById('bkashForm').classList.add('hidden');
            document.getElementById('loading').classList.remove('hidden');

            setTimeout(() => {
                document.getElementById('bkashForm').submit();
            }, 1500);
        }
    </script>
</body>
</html>
