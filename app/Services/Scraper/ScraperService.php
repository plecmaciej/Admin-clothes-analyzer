<?php

namespace App\Services\Scraper;

use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;
use Spatie\Browsershot\Browsershot;

class ScraperService
{
    public function getProductIdsFromPage(string $url): array
    {

        $html = Browsershot::url($url)
            ->userAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/201.0 Safari/537.36')
            ->bodyHtml();

        // Zapisz HTML do debugowania
        file_put_contents(storage_path('app/scraped.html'), $html);

        $crawler = new Crawler($html);
        $ids = [];

        $crawler->filter('a')->each(function (Crawler $node) use (&$ids) {
            $href = $node->attr('href');
            if ($href && preg_match('#/([A-Z]{2}[0-9]{4})\.html$#', $href, $matches)) {
                $ids[] = $matches[1];
            }
        });

        $ids = array_unique($ids);

        echo "Znaleziono " . count($ids) . " produktów:\n";
        print_r($ids);

        return $ids;
    }

    public function getProductData(string $productId): ?array
    {
        $url = "https://www.adidas.pl/plp-app/api/product/{$productId}";

        $client = HttpClient::create([
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/202.0 Safari/537.36',
                'Accept' => 'application/json'
            ]
        ]);

        try {
            $response = $client->request('GET', $url);
            $json = $response->toArray();

            // Wyciągamy tylko to, co chcesz
            $name  = $json['product']['title'] ?? null;
            $image = $json['product']['image'] ?? null;
            $price = $json['product']['priceData']['price'] ?? null;

            $result = [
                'id'    => $productId,
                'name'  => $name,
                'image' => $image,
                'price' => $price
            ];

            // Możesz zapisać do pliku lub zwrócić
            file_put_contents(
                storage_path("app/product_{$productId}_short.json"),
                json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            );

            print_r($result);

            return $result;
        } catch (\Exception $e) {
            \Log::error("Błąd pobierania produktu {$productId}: " . $e->getMessage());
            return null;
        }
    }
}
