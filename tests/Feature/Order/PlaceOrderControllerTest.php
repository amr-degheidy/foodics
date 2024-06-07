<?php

namespace Tests\Feature\Order;

use App\Http\Services\Ingredient\IngredientServiceInterface;
use App\Mail\IngredientsLimitReachedMail;
use App\Models\Ingredient;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Product;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class PlaceOrderControllerTest extends TestCase
{

    use RefreshDatabase;
    protected function setUp(): void
    {
        parent::setUp();

        $this->refreshDatabase();

        $this->seed(DatabaseSeeder::class);
    }

    /**
     * A basic feature test example.
     */
    public function test_validation_if_no_body_sent_gives_errors(): void
    {
        $response = $this->postJson('/api/place-order');
        $response->assertStatus(422)
        ->assertJson(
            [
                "message"=>"The products field is required.",
                "errors"=> [
                    "products"=>["The products field is required."]
                ]
            ]);
    }

    /**
     * A basic feature test example.
     */
    public function test_validation_if_wrong_product_id_gives_errors(): void
    {
        $response = $this->postJson('/api/place-order',[
                "products" => [
                    [
                        "quantity" => 3
                    ]
                ]
        ]);

        $response->assertStatus(422)
            ->assertJson(
                [
                    "message"=>"The products.0.product_id field is required.",
                    "errors"=> [
                        "products.0.product_id"=>["The products.0.product_id field is required."]
                    ]
                ]);
    }

    public function test_large_amount_return_no_ingredient_available(): void
    {
        $response = $this->postJson('/api/place-order',[
            "products" => [
                [
                    'product_id' => Product::first()->id,
                    "quantity" => 333333
                ]
            ]
        ]);
        $response->assertStatus(422)
            ->assertJson(
                [
                    "message"=>"Some of Our Ingredients is out of stock",
                    "errors"=> [
                        "products"=>["Some of Our Ingredients is out of stock"]
                    ]
                ]);

    }

    public function test_order_place_added_right_details_to_db(): void
    {
        $firstProduct = Product::create([
            'name' => 'first',
            'description' => 'description first product',
            'price' => 100
        ]);

         $secondProduct = Product::create([
            'name' => 'second',
            'description' => 'description second product',
            'price' => 200
        ]);
        $ingredient = Ingredient::create([
            'name' => 'ingredient',
            'quantity' => 1000
        ]);
       $firstProduct->ingredients()->attach($ingredient,['quantity'=> 100]);
       $secondProduct->ingredients()->attach($ingredient, ['quantity'=> 200]);
       $this->postJson('/api/place-order',[
            "products" => [
                [
                    'product_id' => $firstProduct->id,
                    "quantity" => 2
                ],
                [
                    'product_id' => $secondProduct->id,
                    "quantity" => 3
                ],
            ]
        ]);

       $order = Order::first();
       $firstOrderDetails = OrderDetails::where('order_id', $order->id)
            ->where('product_id',$firstProduct->id)->first();

       $secondOrderDetails = OrderDetails::where('order_id', $order->id)
            ->where('product_id',$secondProduct->id)->first();
       $this->assertEquals(
            $firstOrderDetails->quantity,
            2
       );
       $this->assertEquals(
            $firstOrderDetails->price,
            200.00
       );
       $this->assertEquals(
            $secondOrderDetails->quantity,
            3
       );
        $this->assertEquals(
            $secondOrderDetails->price,
            600.00
        );
    }

    public function test_ingredient_decreased_correctly_on_order_placed(): void
    {
        $product = Product::create([
            'name' => 'first',
            'description' => 'description first product',
            'price' => 100
        ]);

        $ingredient = Ingredient::create([
            'name' => 'ingredient',
            'quantity' => 1000
        ]);

        $product->ingredients()->attach($ingredient,['quantity'=> 100]);

        $this->postJson('/api/place-order',[
            "products" => [
                [
                    'product_id' => $product->id,
                    "quantity" => 3
                ],
            ]
        ]);

        $updatedIngredient = Ingredient::find($ingredient->id);
        $this->assertEquals(
            $updatedIngredient->used_quantity,
            300
        );

        $this->assertEquals(
            $updatedIngredient->quantity,
            700
        );

    }

    public function test_ingredient_mail_queued_for_reach_limit(): void
    {
        Mail::fake();

        $product = Product::create([
            'name' => 'first',
            'description' => 'description first product',
            'price' => 100
        ]);

        $ingredient = Ingredient::create([
            'name' => 'ingredient',
            'quantity' => 1000
        ]);

        $product->ingredients()->attach($ingredient,['quantity'=> 100]);

        $this->postJson('/api/place-order',[
            "products" => [
                [
                    'product_id' => $product->id,
                    "quantity" => 6
                ],
            ]
        ]);


        Mail::assertQueued(IngredientsLimitReachedMail::class, function (IngredientsLimitReachedMail $mail) {
             $this->assertStringContainsString('The Current Quantity 400', $mail->render());
             return true;
        });

    }
}
