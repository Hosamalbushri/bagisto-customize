<?php

namespace Webkul\DeliveryAgents\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Webkul\DeliveryAgents\Models\DeliveryAgent;
use Webkul\DeliveryAgents\Models\DeliveryAgentReview;
use Webkul\Customer\Models\Customer;
use Webkul\Sales\Models\Order;

class ReviewValidationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // إنشاء بيانات اختبار
        $this->customer = Customer::factory()->create();
        $this->deliveryAgent = DeliveryAgent::factory()->create();
        $this->order = Order::factory()->create([
            'status' => 'delivered',
            'delivery_agent_id' => $this->deliveryAgent->id,
        ]);
    }

    /** @test */
    public function it_prevents_duplicate_reviews_for_same_order()
    {
        // إنشاء مراجعة أولى
        $firstReview = DeliveryAgentReview::create([
            'order_id' => $this->order->id,
            'delivery_agent_id' => $this->deliveryAgent->id,
            'customer_id' => $this->customer->id,
            'rating' => 5,
            'comment' => 'تقييم ممتاز',
            'status' => 'approved',
        ]);

        // محاولة إنشاء مراجعة ثانية للطلب نفسه
        $response = $this->postJson(route('admin.review.create'), [
            'order_id' => $this->order->id,
            'delivery_agent_id' => $this->deliveryAgent->id,
            'customer_id' => $this->customer->id,
            'rating' => 4,
            'comment' => 'تقييم آخر',
        ]);

        // التحقق من فشل الطلب
        $response->assertStatus(422)
                ->assertJson([
                    'status' => 'error',
                    'message' => 'تم تقييم هذا الطلب مسبقاً',
                ]);

        // التحقق من وجود مراجعة واحدة فقط
        $this->assertEquals(1, DeliveryAgentReview::where('order_id', $this->order->id)->count());
    }

    /** @test */
    public function it_allows_review_for_delivered_order()
    {
        $response = $this->postJson(route('admin.review.create'), [
            'order_id' => $this->order->id,
            'delivery_agent_id' => $this->deliveryAgent->id,
            'customer_id' => $this->customer->id,
            'rating' => 5,
            'comment' => 'تقييم ممتاز',
        ]);

        $response->assertStatus(201)
                ->assertJson([
                    'status' => 'success',
                    'message' => 'تم إرسال التقييم بنجاح',
                ]);

        $this->assertEquals(1, DeliveryAgentReview::where('order_id', $this->order->id)->count());
    }

    /** @test */
    public function it_prevents_review_for_non_delivered_order()
    {
        // إنشاء طلب غير مكتمل التوصيل
        $pendingOrder = Order::factory()->create([
            'status' => 'processing',
            'delivery_agent_id' => $this->deliveryAgent->id,
        ]);

        $response = $this->postJson(route('admin.review.create'), [
            'order_id' => $pendingOrder->id,
            'delivery_agent_id' => $this->deliveryAgent->id,
            'customer_id' => $this->customer->id,
            'rating' => 5,
            'comment' => 'تقييم ممتاز',
        ]);

        $response->assertStatus(422)
                ->assertJson([
                    'status' => 'error',
                    'message' => 'لا يمكن تقييم طلب غير مكتمل التوصيل',
                ]);
    }

    /** @test */
    public function it_checks_existing_review_via_model_relationship()
    {
        // التحقق من عدم وجود مراجعة
        $this->assertFalse($this->order->hasReview());
        $this->assertNull($this->order->getReview());

        // إنشاء مراجعة
        DeliveryAgentReview::create([
            'order_id' => $this->order->id,
            'delivery_agent_id' => $this->deliveryAgent->id,
            'customer_id' => $this->customer->id,
            'rating' => 5,
            'comment' => 'تقييم ممتاز',
            'status' => 'approved',
        ]);

        // إعادة تحميل الطلب للحصول على العلاقة
        $this->order->refresh();

        // التحقق من وجود المراجعة
        $this->assertTrue($this->order->hasReview());
        $this->assertNotNull($this->order->getReview());
    }

    /** @test */
    public function it_checks_existing_review_via_api()
    {
        // إنشاء مراجعة
        DeliveryAgentReview::create([
            'order_id' => $this->order->id,
            'delivery_agent_id' => $this->deliveryAgent->id,
            'customer_id' => $this->customer->id,
            'rating' => 5,
            'comment' => 'تقييم ممتاز',
            'status' => 'approved',
        ]);

        // التحقق من وجود المراجعة
        $response = $this->getJson(route('admin.review.check', ['order_id' => $this->order->id]));

        $response->assertStatus(200)
                ->assertJson([
                    'has_review' => true,
                ]);
    }

    /** @test */
    public function it_returns_false_for_non_existing_review()
    {
        $response = $this->getJson(route('admin.review.check', ['order_id' => $this->order->id]));

        $response->assertStatus(200)
                ->assertJson([
                    'has_review' => false,
                ]);
    }
}
