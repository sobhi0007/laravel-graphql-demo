<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use GuzzleHttp\Client;
use App\Enums\OrderStatus;
use Illuminate\Support\Collection;
use Illuminate\Pagination\Paginator;

class OrdersLivewireTable extends Component
{
    use WithPagination;

    public $search = '';
    public $admin = '';
    public $perPage = 5;

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function getOrderStatusColorPill($status): string
    {
        return OrderStatus::from($status)->color();
    }

    public function render()
    {
        $client = new Client();

        $query = <<<'GRAPHQL'
        query {
            orders {
                request_id
                complexity
                data {
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
        $orders = collect($responseBody['data']['orders']['data']['edges']);

        if ($this->search) {
            $orders = $orders->filter(function ($order) {
                return stripos($order['node']['order_number'], $this->search) !== false ||
                    stripos($order['node']['shipping_address']['first_name'], $this->search) !== false ||
                    stripos($order['node']['shipping_address']['last_name'], $this->search) !== false ||
                    stripos($order['node']['shipping_address']['city'], $this->search) !== false ||
                    stripos($order['node']['shipping_address']['email'], $this->search) !== false;
            });
        }

        $paginatedOrders = $this->paginate($orders, $this->perPage);

        return view('livewire.orders-livewire-table', [
            'orders' => $paginatedOrders,
        ]);
    }

    public function paginate($items, $perPage)
    {
        $page = Paginator::resolveCurrentPage() ?: 1;
        $items = $items instanceof Collection ? $items : Collection::make($items);
        $total = $items->count();
        $results = $items->forPage($page, $perPage);

        return new \Illuminate\Pagination\LengthAwarePaginator($results, $total, $perPage, $page, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => 'page',
        ]);
    }
}
