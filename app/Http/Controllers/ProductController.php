<?php

namespace App\Http\Controllers;

use App\Category;
use App\Field;
use App\Offer;
use App\Price;
use App\PriceHistory;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    public function importProducts() {
        for($i = 1; $i <= 8; $i++){
            $string = "http://api.tradedoubler.com/1.0/products.json;page=". $i .";pageSize=100;fid=23056?token=26A8CEEC9833CED128CAC91910344740632FBC93";
            $json = file_get_contents($string);
            $data = json_decode($json);
            foreach($data->products as $product) {
                $new_product = new Product;
                $new_product['id'] = (int)get_object_vars($product->identifiers)['sku'];
                $new_product['ean'] = (float)get_object_vars($product->identifiers)['ean'];
                $new_product['name'] = $product->name;
                $new_product['description'] = $product->description;
                $new_product['productImage'] = $product->productImage->url;
                $new_product['language'] = $product->language;
                $new_product['shortDescription'] = isset($product->shortDescription) ? $product->shortDescription : null;
                $new_product['model'] = $product->model;
                $new_product['groupingId'] = $product->groupingId;
                $new_product->save();

                foreach($product->fields as $field) {
                    $new_field = Field::where('name', $field->name)->where('value', $field->value)->first();
                    if (!$new_field) {
                        $new_field = new Field;
                        $new_field['name'] = $field->name;
                        $new_field['value'] = $field->value;
                    }
                    $new_field->save();
                    $new_product->fields()->attach($new_field);
                }

                foreach($product->categories as $category) {
                    $new_category = Category::where('name', $category->name)->first();
                    if (!$new_category) {
                        $new_category = new Category;
                        $new_category['name'] = $category->name;
                    }
                    $new_category->save();
                    $new_product->categories()->attach($new_category);
                }

                foreach($product->offers  as $offer) {
                    $new_offer = Offer::where('feedId', $offer->feedId)->where('modified', $offer->modified)->where('sourceProductId', $offer->sourceProductId)->first();
                    if (!$new_offer) {
                        $new_offer = new Offer;
                        $new_offer['id'] = $offer->id;
                        $new_offer['feedId'] = $offer->feedId;
                        $new_offer['productUrl']  = $offer->productUrl;
                        $new_offer['modified'] = $offer->modified;
                        $new_offer['sourceProductId'] = (int)$offer->sourceProductId;
                        $new_offer['programLogo'] = $offer->programLogo;
                        $new_offer['programName'] = $offer->programName;
                        $new_offer['availability'] = $offer->availability == 'True' ? 1 : 0;
                        $new_offer['deliveryTime'] = $offer->deliveryTime;
                        $new_offer['shippingCost'] = $offer->shippingCost;
                        $new_offer->save();
                    }

                    foreach($offer->priceHistory  as $change) {
                        $new_change = PriceHistory::where('date', $change->date)->first();
                        if (!$new_change) {
                            $new_change = new PriceHistory;
                            $new_change['date'] = $change->date;
                            $new_change['offer_id'] = $offer->id;
                            $price = Price::where('value', get_object_vars($change->price)['value'])->where('currency', get_object_vars($change->price)['currency'])->first();
                            if (!$price) {
                                $price = new Price;
                                $price['value'] = get_object_vars($change->price)['value'];
                                $price['currency'] = get_object_vars($change->price)['currency'];
                                $price->save();
                            }
                            $new_change['price_id'] = $price->id;
                            $new_change->save();
                        }
                    }
                }
            }
        }
    }

    public function getProducts(Request $request) {
        return response()->json(Product::with('fields')->with('offers')->with('pricechanges')->with('prices')->with('categories')->paginate($request['amount']));
    }
}
