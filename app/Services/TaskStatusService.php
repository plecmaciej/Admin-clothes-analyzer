<?php

namespace App\Services;

use App\Models\Task;
use App\Models\Catalog;
use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\DomCrawler\Crawler;
use Spatie\Browsershot\Browsershot;
use Symfony\Component\HttpClient\HttpClient;

class TaskStatusService
{
    public function handleInProgressStatus(Task $task)
    {
        set_time_limit(0); 
        Log::info("Start scrapowania dla zadania #{$task->id}, URL: {$task->target_url}");

        if (!$task->target_url) {
            return;
        }


        $catalog = Catalog::create([
            'title' => $task->title,
        ]);
        Log::info("Utworzono katalog #{$catalog->id} o nazwie '{$catalog->title}'");


        $html = Browsershot::url($task->target_url)
            ->userAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/201.0 Safari/537.36')
            ->bodyHtml();

        Log::info("Pobrano HTML (długość: " . strlen($html) . " znaków)");

        $crawler = new Crawler($html);

        $ids = [];


        $crawler->filter('a')->each(function (Crawler $node) use (&$ids) {
            $href = $node->attr('href');

            if ($href && preg_match('#/([A-Z]{2}[0-9]{4})\.html$#', $href, $matches)) {
                $ids[] = $matches[1];
            }
        });

        $ids = array_unique($ids);
        Log::info("Znaleziono " . count($ids) . " unikalnych produktów", ['ids' => $ids]);

        $client = HttpClient::create([
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/202.0 Safari/537.36',
                'Accept' => 'application/json'
            ]
        ]);

  
        foreach ($ids as $productId) {

            $jsonUrl = "https://www.adidas.pl/plp-app/api/product/{$productId}";
            $productResponse = $client->request('GET', $jsonUrl);

            $data = $productResponse->toArray();

            $name  = $data['product']['title'] ?? null;
            $imageUrl = $data['product']['image'] ?? null;
            $price = $data['product']['priceData']['price'] ?? null;


            $imageContents = Http::get($imageUrl)->body();
            $imagePath = "products/{$productId}.jpg";
            Storage::disk('public')->put($imagePath, $imageContents);
            Log::info("Zapisano zdjęcie dla {$productId} w {$imagePath}");


            $product = Product::updateOrCreate(
                ['product_id' => $productId],
                [
                    'name'  => $name,
                    'image_path' => $imagePath, 
                ]
            );


            $catalog->products()->syncWithoutDetaching([
                $product->id => ['price' => $price],
            ]);
            Log::info("Dodano produkt {$productId}: {$name} ({$price} PLN)");
        }

        \Log::info("Zakończono scrapowanie dla zadania #{$task->id}");
    }


}