<?php

namespace Database\Seeders;

use App\Enums\Blog\BlogStatus;
use App\Enums\Product\ProductStatus;
use App\Models\Blog;
use App\Models\Product;
use App\Models\Tag;
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
        $user = User::factory()->create([
            'name' => 'علی اکبر رضایی',
            'email' => 'akrez.like@gmail.com',
            'password' => bcrypt('12345678'),
        ]);

        $blog = Blog::factory()->create([
            'name' => 'شهاب تحریر',
            'short_description' => 'نامی مطمئن',
            'description' => 'وجود سابقه زیاد و تجربه کافی که گواه آن تولید محصولات با کیفیت و با تنوع بالا در زمینه ملزومات اداری است ما را بر این داشته تا خود را در جهت رضایت هر چه بیشتری مشتریان قرار دهیم',
            'created_by' => $user,
            'blog_status' => BlogStatus::ACTIVE,
        ]);

        $product1 = Product::factory()->create([
            'code' => 'n36',
            'name' => 'پایه چسب N36',
            'blog_id' => $blog->id,
            'product_status' => ProductStatus::ACTIVE,
        ]);
        $product2 = Product::factory()->create([
            'code' => '3020',
            'name' => 'پایه چسب 3020',
            'blog_id' => $blog->id,
            'product_status' => ProductStatus::ACTIVE,
        ]);
        $product3 = Product::factory()->create([
            'code' => 'chasbkesh',
            'name' => 'چسب کش',
            'blog_id' => $blog->id,
            'product_status' => ProductStatus::ACTIVE,
        ]);

        $tagPayeChasb = Tag::create([
            'name' => 'پایه چسب',
            'blog_id' => $blog->id,
        ]);
        $tagChasb = Tag::create([
            'name' => 'چسب',
            'blog_id' => $blog->id,
        ]);

        $product1->tags()->sync([$tagPayeChasb->name], false);
        $product2->tags()->sync([$tagPayeChasb->name], false);
        $product3->tags()->sync([$tagPayeChasb->name, $tagChasb->name], false);
    }
}
