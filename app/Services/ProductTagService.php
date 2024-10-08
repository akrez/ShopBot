<?php

namespace App\Services;

use App\Contracts\PortContract;
use App\DTO\ProductTagDTO;
use App\Facades\ArrayHelper;
use App\Models\Blog;
use App\Models\Product;
use App\Support\ResponseBuilder;

class ProductTagService implements PortContract
{
    const TAG_NAME_MAX_LENGTH = 32;

    const NAME_SEPARATORS = [
        PHP_EOL => 'Enter',
        ':' => ':',
        ',' => ',',
        '،' => '،',
        "\t" => 'Tab',
    ];

    const NAME_GLUE = PHP_EOL;

    public function getLatestProductsWithTags(Blog $blog)
    {
        $blog->load([
            'products' => function ($query) {
                $query->with('productTags', function ($query) {
                    $query->latest('created_at');
                })->latest('created_at');
            },
        ]);

        return $blog->products;
    }

    public function getLatestProductTags(Product $product): \Illuminate\Database\Eloquent\Collection
    {
        $product->load([
            'productTags' => function ($query) {
                $query->latest('created_at');
            },
        ]);

        return $product->productTags;
    }

    public function exportToTextArea(Product $product)
    {
        return implode(ProductTagService::NAME_GLUE, $this->exportToArray($product));
    }

    public function exportToExcel(Blog $blog)
    {
        $source = [];

        $source[] = [
            __('validation.attributes.code'),
            __('validation.attributes.name'),
            __('validation.attributes.tag_name'),
        ];

        $products = $this->getLatestProductsWithTags($blog);
        foreach ($products as $product) {
            $source[] = array_merge([
                $product->code,
                $product->name,
            ], $this->exportToArray($product));
        }

        return $source;
    }

    protected function exportToArray(Product $product)
    {
        return $this->getLatestProductTags($product)->pluck('tag_name')->toArray();
    }

    public function importFromExcel(Blog $blog, array $rows)
    {
        $result = [];
        //
        $rows = $rows + [0 => []];
        unset($rows[0]);
        //
        $productCodeToTags = [];
        foreach ($rows as $row) {
            $row += array_fill(0, 3, '');
            //
            $productCode = trim($row[0]);
            $productCodeToTags[$productCode][] = array_slice($row, 2);
        }
        //
        foreach ($productCodeToTags as $productCode => $tags) {
            $product = resolve(ProductService::class)->firstProductByCode($blog, $productCode);
            //
            if ($product) {
                $result[] = $this->import($blog, $product, $tags);
            }
        }

        return $result;
    }

    public function importFromTextArea(Blog $blog, Product $product, ?string $content)
    {
        $tags = ArrayHelper::iexplode(array_keys(ProductTagService::NAME_SEPARATORS), $content);

        return $this->import($blog, $product, $tags);
    }

    protected function import(Blog $blog, Product $product, array $tags)
    {
        $this->delete($product);
        $safeTags = $this->filter($tags);

        $responseBuilder = resolve(ResponseBuilder::class)->data((object) [
            'product' => $product,
            'product_tags' => $this->insert($blog, $product, $safeTags),
        ]);

        if (count($safeTags) != count($responseBuilder->getData()->product_tags)) {
            return $responseBuilder->status(206)->message(__('http-statuses.422'));
        }

        if (count($safeTags) == 0) {
            return $responseBuilder->status(200)->message(__('All :names removed', [
                'names' => __('Tags'),
            ]));
        }

        return $responseBuilder->status(201)->message(__(':count :names are created successfully', [
            'count' => count($safeTags),
            'names' => __('Tag'),
        ]));
    }

    public function delete(Product $product)
    {
        $product->productTags()->delete();
    }

    public function filter(array $tags)
    {
        $stringLine = implode(ProductTagService::NAME_GLUE, collect($tags)->flatten()->toArray());

        $tags = ArrayHelper::iexplode(array_keys(ProductTagService::NAME_SEPARATORS), $stringLine);

        return collect($tags)
            ->map(fn ($item) => trim($item))
            ->filter()
            ->unique()
            ->filter(fn ($tag) => (new ProductTagDTO($tag))
                ->validate()
                ->errors()
                ->isEmpty())
            ->toArray();
    }

    /**
     * @param  $tags  <int, string>
     * @return <int, ProductTag>
     */
    public function insert(Blog $blog, Product $product, array $tags): array
    {
        $data = [];
        foreach ($tags as $tag) {
            $data[] = $blog->productTags()->create([
                'tag_name' => $tag,
                'product_id' => $product->id,
            ]);
        }

        return $data;
    }
}
