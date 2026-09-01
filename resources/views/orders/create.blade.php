<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create Manual Order') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100" x-data="manualOrder()">
                    
                    @if (session('error'))
                        <div class="mb-4 p-4 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-200 rounded">
                            {{ session('error') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-200 rounded">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('orders.storeManual') }}" id="manualOrderForm">
                        @csrf
                        <input type="hidden" name="items" x-model="itemsJson">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Order Details -->
                            <div>
                                <h3 class="text-lg font-medium mb-4 text-gray-900 dark:text-gray-100 border-b pb-2 dark:border-gray-700">Order Details</h3>
                                
                                <div class="mb-4">
                                    <x-input-label for="customer_name" :value="__('Customer Name (Optional)')" />
                                    <x-text-input id="customer_name" class="block mt-1 w-full" type="text" name="customer_name" :value="old('customer_name')" placeholder="e.g. Guest or Student Name" />
                                </div>

                                <div class="mb-4">
                                    <x-input-label for="order_type" :value="__('Order Type')" />
                                    <select id="order_type" name="order_type" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                        <option value="dine_in">Dine-in</option>
                                        <option value="takeaway">Takeaway</option>
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <x-input-label for="payment_method" :value="__('Payment Method')" />
                                    <select id="payment_method" name="payment_method" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                        <option value="cash">Cash (Counter)</option>
                                        <option value="bkash">bKash (Counter)</option>
                                    </select>
                                    <p class="text-sm text-gray-500 mt-1">Note: Manual orders are created as Paid and Pending immediately.</p>
                                </div>

                                <div class="mt-8 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                    <h4 class="font-bold text-lg mb-2">Order Total: BDT <span x-text="calculateTotal()"></span></h4>
                                    <x-primary-button class="w-full justify-center py-3" type="button" @click="submitForm">
                                        {{ __('Place Manual Order') }}
                                    </x-primary-button>
                                </div>
                            </div>

                            <!-- Item Selection -->
                            <div>
                                <h3 class="text-lg font-medium mb-4 text-gray-900 dark:text-gray-100 border-b pb-2 dark:border-gray-700">Select Items</h3>
                                
                                <div class="mb-4">
                                    <select x-model="selectedItem" class="block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                        <option value="">-- Choose an item --</option>
                                        @foreach($foodItems as $item)
                                            <option value="{{ $item->id }}" data-name="{{ $item->name }}" data-price="{{ $item->price }}" data-stock="{{ $item->stock_quantity }}">
                                                {{ $item->name }} (BDT {{ $item->price }}) - Stock: {{ $item->stock_quantity }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="flex mt-2 space-x-2">
                                        <input type="number" x-model.number="selectedQuantity" min="1" class="w-20 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                        <button type="button" @click="addItem" class="px-4 py-2 bg-gray-800 dark:bg-gray-200 text-white dark:text-gray-800 rounded-md font-semibold text-xs hover:bg-gray-700 transition">Add</button>
                                    </div>
                                    <p class="text-sm text-red-500 mt-1" x-show="error" x-text="error"></p>
                                </div>

                                <div class="mt-6 border dark:border-gray-700 rounded-lg overflow-hidden">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead class="bg-gray-50 dark:bg-gray-700">
                                            <tr>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Item</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Qty</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Subtotal</th>
                                                <th class="px-4 py-2"></th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                            <template x-for="(item, id) in cart" :key="id">
                                                <tr>
                                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100" x-text="item.name"></td>
                                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100" x-text="item.quantity"></td>
                                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">BDT <span x-text="item.price * item.quantity"></span></td>
                                                    <td class="px-4 py-3 text-right">
                                                        <button type="button" @click="removeItem(id)" class="text-red-600 hover:text-red-900">Remove</button>
                                                    </td>
                                                </tr>
                                            </template>
                                            <tr x-show="Object.keys(cart).length === 0">
                                                <td colspan="4" class="px-4 py-4 text-center text-sm text-gray-500">No items added yet.</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script>
        function manualOrder() {
            return {
                cart: {},
                selectedItem: '',
                selectedQuantity: 1,
                error: '',
                itemsJson: '{}',
                
                addItem() {
                    this.error = '';
                    if (!this.selectedItem) return;
                    
                    const selectEl = document.querySelector(`select[x-model="selectedItem"] option:checked`);
                    const stock = parseInt(selectEl.dataset.stock);
                    const name = selectEl.dataset.name;
                    const price = parseFloat(selectEl.dataset.price);
                    
                    let newQty = this.selectedQuantity;
                    if (this.cart[this.selectedItem]) {
                        newQty += this.cart[this.selectedItem].quantity;
                    }
                    
                    if (newQty > stock) {
                        this.error = 'Cannot add more than available stock (' + stock + ')';
                        return;
                    }
                    
                    if (!this.cart[this.selectedItem]) {
                        this.cart[this.selectedItem] = {
                            name: name,
                            price: price,
                            quantity: this.selectedQuantity
                        };
                    } else {
                        this.cart[this.selectedItem].quantity += this.selectedQuantity;
                    }
                    
                    this.updateJson();
                    this.selectedItem = '';
                    this.selectedQuantity = 1;
                },
                
                removeItem(id) {
                    delete this.cart[id];
                    this.updateJson();
                },
                
                calculateTotal() {
                    let total = 0;
                    for (let id in this.cart) {
                        total += this.cart[id].price * this.cart[id].quantity;
                    }
                    return total.toFixed(2);
                },
                
                updateJson() {
                    let payload = {};
                    for(let id in this.cart) {
                        payload[id] = this.cart[id].quantity;
                    }
                    this.itemsJson = JSON.stringify(payload);
                },
                
                submitForm() {
                    if (Object.keys(this.cart).length === 0) {
                        alert('Please add at least one item.');
                        return;
                    }
                    this.updateJson();
                    document.getElementById('manualOrderForm').submit();
                }
            }
        }
    </script>
</x-app-layout>
