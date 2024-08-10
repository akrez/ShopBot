<?php

namespace Database\Seeders;

use App\Enums\Blog\BlogStatus;
use App\Enums\Product\ProductStatus;
use App\Models\Blog;
use App\Models\Product;
use App\Models\ProductTag;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user1 = User::factory()->create([
            'name' => 'علی اکبر رضایی',
            'email' => 'akrez.like@gmail.com',
            'password' => bcrypt('12345678'),
        ]);
        $user2 = User::factory()->create([
            'name' => 'علی رضا رضایی',
            'email' => 'owkrez@gmail.com',
            'password' => bcrypt('12345678'),
        ]);

        $blog1 = Blog::factory()->create([
            'name' => 'شهاب تحریر',
            'short_description' => 'نامی مطمئن',
            'description' => 'وجود سابقه زیاد و تجربه کافی که گواه آن تولید محصولات با کیفیت و با تنوع بالا در زمینه ملزومات اداری است ما را بر این داشته تا خود را در جهت رضایت هر چه بیشتری مشتریان قرار دهیم',
            'created_by' => $user1,
            'blog_status' => BlogStatus::ACTIVE,
        ]);
        $blog2 = Blog::factory()->create([
            'name' => 'فیواستور',
            'short_description' => 'خرید لوازم تحریر فانتزی',
            'description' => 'فروشگاه اینترنتی فیواستور در سال ۱۳۹۸ ابتدا در قالب فروشگاهی در بستر اینستاگرام فعالیت خود را در زمینه عرضه لوازم تحریر فانتزی آغاز کرد',
            'created_by' => $user1,
            'blog_status' => BlogStatus::ACTIVE,
        ]);

        $product1 = Product::factory()->create([
            'code' => 'n36',
            'name' => 'پایه چسب N36',
            'blog_id' => $blog1->id,
            'product_status' => ProductStatus::ACTIVE,
        ]);
        $product2 = Product::factory()->create([
            'code' => '3020',
            'name' => 'پایه چسب 3020',
            'blog_id' => $blog1->id,
            'product_status' => ProductStatus::ACTIVE,
        ]);
        $product3 = Product::factory()->create([
            'code' => 'chasbkesh',
            'name' => 'چسب کش',
            'blog_id' => $blog1->id,
            'product_status' => ProductStatus::ACTIVE,
        ]);

        ProductTag::create([
            'blog_id' => $blog1->id,
            'product_id' => $product1->id,
            'tag_name' => 'پایه چسب',
        ]);
        ProductTag::create([
            'blog_id' => $blog1->id,
            'product_id' => $product2->id,
            'tag_name' => 'پایه چسب',
        ]);
        ProductTag::create([
            'blog_id' => $blog1->id,
            'product_id' => $product3->id,
            'tag_name' => 'پایه چسب',
        ]);
        ProductTag::create([
            'blog_id' => $blog1->id,
            'product_id' => $product3->id,
            'tag_name' => 'چسب',
        ]);
    }
}
