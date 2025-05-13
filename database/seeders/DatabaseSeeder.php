<?php

namespace Database\Seeders;

use App\Models\Business\Order\Order;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use PhpParser\Node\Stmt\Foreach_;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);


        $user = User::query()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password')
        ]);


        for($i=1; $i<=10; $i++) {

            $order = Order::query()
                    ->create([
                        'customer_id' => $user->getkey(),
                        'sub_total' => 340,
                        'tax' => 0,
                        'shipping_cost' => 0,
                        'total' => 350,
                        'currency_code' => 'usd',
                        'status' => 0,
                    ]);


            $items = [
                    [
                        'item_name'  => 'Test Prdouct 1',
                        'quantity'  => 2,
                        'price'  => 40,
                        'tax'  => 0,
                        'total'  => 80,
                    ],
                    [
                        'item_name'  => 'Test Prdouct 2',
                        'quantity'  => 1,
                        'price'  => 60,
                        'tax'  => 0,
                        'total'  => 60,
                    ],
                    [
                        'item_name'  => 'Test Prdouct 3',
                        'quantity'  => 4,
                        'price'  => 50,
                        'tax'  => 0,
                        'total'  => 200,
                    ],

            ];


            $totals = [
                [
                    'code'  => 'sub_total',
                    'title'  => 'Sub Total',
                    'value'  =>  340,
                    'sort_order'  => 1,
                ],
                [
                    'code'  => 'total',
                    'title'  => 'Total',
                    'value'  =>  340,
                    'sort_order'  => 1,
                ],
            ];

            foreach($items  as $item)
            {

                $order->items()->create($item);

            }

            foreach($totals  as $total)
            {

                $order->totals()->create($total);

            }


        }
    }
}
