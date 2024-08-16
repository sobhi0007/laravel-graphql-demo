<?php
namespace App\Livewire;

use Livewire\Component;
use GuzzleHttp\Client;
use App\Enums\OrderStatus;
class OrdersTable extends Component
{
    public $orders = [];


    public function getOrderStatusColorPill($status): string
    {
        return OrderStatus::from($status)->color();
    }

    public function mount()
    {
     
            $client = new Client();
    
            $query = <<<'GRAPHQL'
            query {
                orders {
                    request_id
                    complexity
                    data(first: 20) {
                        edges {
                            node {
                                id
                                order_number
                                fulfillment_status
                                shipping_address {
                                    first_name
                                    last_name
                                    city
                                    address1
                                    phone
                                    email
                                }
                                total_price
                                ready_to_ship
                                required_ship_date
                                shop_name
                            }
                        }
                    }
                }
            }
            GRAPHQL;
    
            $response = $client->post('https://public-api.shiphero.com/graphql', [
                'headers' => [
                    'Authorization' => 'Bearer ' . env('GRAPHQL_ACCESS_TOKEN'),
                    'Content-Type'  => 'application/json',
                ],
                'json' => [
                    'query' => $query,
                ],
            ]);
   
            $responseBody = json_decode($response->getBody(), true);
            $this->orders = $responseBody['data']['orders']['data']['edges'];
            
    }

    public function render()
    {
        return view('livewire.orders-table');
    }
}
