<div>
    <div class="container">
        <h1>Orders</h1>
        <div class="card shadow-lg">

            <table class="table table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Order Number</th>
                        <th>Status</th>
                        <th>Total Price</th>
                        <th>Customer Name</th>
                        <th>Shipping Address</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td class="fw-bolder">#{{$loop->iteration}}</td>
                        <td>{{ $order['node']['order_number'] }}</td>
                        <td>
                            <span
                                class="badge rounded-pill {{ $this->getOrderStatusColorPill($order['node']['fulfillment_status']) }}">
                                {{ $order['node']['fulfillment_status'] }}</span>
                        </td>
                        <td class="fw-bolder">${{ $order['node']['total_price'] }}</td>
                        <td>{{ $order['node']['shipping_address']['first_name'] }} {{
                            $order['node']['shipping_address']['last_name'] }}</td>
                        <td>{{ $order['node']['shipping_address']['address1'] }}, {{
                            $order['node']['shipping_address']['city'] }}</td>
                        <td>
                            <!-- Button to trigger modal -->
                            <a href="#" data-bs-toggle="modal" data-bs-target="#orderModal"
                                data-order="{{ json_encode($order['node']) }}">
                                <i class="fa fa-eye text-primary" aria-hidden="true"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderModalLabel">Order Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Order Number:</strong> <span id="modalOrderNumber"></span></p>
                    <p><strong>Status:</strong> <span id="modalOrderStatus"></span></p>
                    <p><strong>Customer Name:</strong> <span id="modalCustomerName"></span></p>
                    <p><strong>City:</strong> <span id="modalCity"></span></p>
                    <p><strong>Total Price:</strong> $<span id="modalTotalPrice"></span></p>
                    <p><strong>Ready to Ship:</strong> <span id="modalReadyToShip"></span></p>
                    <p><strong>Required Ship Date:</strong> <span id="modalRequiredShipDate"></span></p>
                    <p><strong>Shop Name:</strong> <span id="modalShopName"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
        var orderModal = document.getElementById('orderModal');
        orderModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget; 
            var order = JSON.parse(button.getAttribute('data-order'));
            
            document.getElementById('modalOrderNumber').textContent = order.order_number;
            document.getElementById('modalOrderStatus').textContent = order.fulfillment_status;
            document.getElementById('modalCustomerName').textContent = order.shipping_address.first_name + ' ' + order.shipping_address.last_name;
            document.getElementById('modalCity').textContent = order.shipping_address.city;
            document.getElementById('modalTotalPrice').textContent = order.total_price;
            document.getElementById('modalReadyToShip').textContent = order.ready_to_ship ? 'Yes' : 'No';
            document.getElementById('modalRequiredShipDate').textContent = order.required_ship_date;
            document.getElementById('modalShopName').textContent = order.shop_name;
        });
    });
    </script>

</div>