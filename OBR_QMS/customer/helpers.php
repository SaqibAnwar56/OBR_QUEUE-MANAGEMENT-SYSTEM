<?php
/**
 * Returns the best image URL for a dish.
 * Priority: 1) Local file  2) Unsplash by name keyword  3) Unsplash by category  4) Pool fallback
 */
function getDishImage($dish, $index = 0) {
    // 1. Local image first
    if (!empty($dish['image'])) {
        $local = '../assets/images/' . $dish['image'];
        if (file_exists($local)) return $local;
    }

    $name = strtolower($dish['name'] ?? '');
    $cat  = strtolower($dish['category'] ?? '');

    // 2. Real Unsplash photos by dish name keyword
    $byName = [
        'karahi'  => 'https://images.unsplash.com/photo-1631452180519-c014fe946bc7?w=600&q=80',
        'biryani' => 'https://images.unsplash.com/photo-1589302168068-964664d93dc0?w=600&q=80',
        'tikka'   => 'https://images.unsplash.com/photo-1567188040759-fb8a883dc6d8?w=600&q=80',
        'kabab'   => 'https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?w=600&q=80',
        'kebab'   => 'https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?w=600&q=80',
        'chapli'  => 'https://images.unsplash.com/photo-1529193591184-b1d58069ecdd?w=600&q=80',
        'seekh'   => 'https://images.unsplash.com/photo-1544025162-d76694265947?w=600&q=80',
        'fish'    => 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?w=600&q=80',
        'machi'   => 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?w=600&q=80',
        'dal'     => 'https://images.unsplash.com/photo-1546833999-b9f581a1996d?w=600&q=80',
        'daal'    => 'https://images.unsplash.com/photo-1546833999-b9f581a1996d?w=600&q=80',
        'naan'    => 'https://images.unsplash.com/photo-1574894709920-11b28e7367e3?w=600&q=80',
        'roti'    => 'https://images.unsplash.com/photo-1574894709920-11b28e7367e3?w=600&q=80',
        'paratha' => 'https://images.unsplash.com/photo-1574894709920-11b28e7367e3?w=600&q=80',
        'lassi'   => 'https://images.unsplash.com/photo-1605761366416-df0fbf7ab33a?w=600&q=80',
        'mango'   => 'https://images.unsplash.com/photo-1605761366416-df0fbf7ab33a?w=600&q=80',
        'gulab'   => 'https://images.unsplash.com/photo-1558961363-fa8fdf82db35?w=600&q=80',
        'halwa'   => 'https://images.unsplash.com/photo-1558961363-fa8fdf82db35?w=600&q=80',
        'kheer'   => 'https://images.unsplash.com/photo-1559496417-e7f25cb247f3?w=600&q=80',
        'platter' => 'https://images.unsplash.com/photo-1585937421612-70a008356fbe?w=600&q=80',
        'chicken' => 'https://images.unsplash.com/photo-1527477396000-e27163b481c2?w=600&q=80',
        'mutton'  => 'https://images.unsplash.com/photo-1603360946369-dc9bb6258143?w=600&q=80',
        'beef'    => 'https://images.unsplash.com/photo-1544025162-d76694265947?w=600&q=80',
        'rice'    => 'https://images.unsplash.com/photo-1516714435131-44d6b64dc6a2?w=600&q=80',
        'chawal'  => 'https://images.unsplash.com/photo-1516714435131-44d6b64dc6a2?w=600&q=80',
        'rooh'    => 'https://images.unsplash.com/photo-1544145945-f90425340c7e?w=600&q=80',
        'juice'   => 'https://images.unsplash.com/photo-1621506289937-a8e4df240d0b?w=600&q=80',
        'chai'    => 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=600&q=80',
        'tea'     => 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=600&q=80',
        'soup'    => 'https://images.unsplash.com/photo-1547592180-85f173990554?w=600&q=80',
        'salad'   => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=600&q=80',
    ];
    foreach ($byName as $kw => $url) {
        if (strpos($name, $kw) !== false) return $url;
    }

    // 3. Category fallbacks
    $byCat = [
        'biryani'    => 'https://images.unsplash.com/photo-1589302168068-964664d93dc0?w=600&q=80',
        'bbq'        => 'https://images.unsplash.com/photo-1529193591184-b1d58069ecdd?w=600&q=80',
        'grill'      => 'https://images.unsplash.com/photo-1544025162-d76694265947?w=600&q=80',
        'dessert'    => 'https://images.unsplash.com/photo-1551024506-0bccd828d307?w=600&q=80',
        'drink'      => 'https://images.unsplash.com/photo-1544145945-f90425340c7e?w=600&q=80',
        'beverage'   => 'https://images.unsplash.com/photo-1544145945-f90425340c7e?w=600&q=80',
        'bread'      => 'https://images.unsplash.com/photo-1574894709920-11b28e7367e3?w=600&q=80',
        'vegetarian' => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=600&q=80',
        'veg'        => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=600&q=80',
        'seafood'    => 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?w=600&q=80',
        'platter'    => 'https://images.unsplash.com/photo-1585937421612-70a008356fbe?w=600&q=80',
        'main'       => 'https://images.unsplash.com/photo-1631452180519-c014fe946bc7?w=600&q=80',
    ];
    foreach ($byCat as $kw => $url) {
        if (strpos($cat, $kw) !== false) return $url;
    }

    // 4. Rotating pool
    $pool = [
        'https://images.unsplash.com/photo-1631452180519-c014fe946bc7?w=600&q=80',
        'https://images.unsplash.com/photo-1589302168068-964664d93dc0?w=600&q=80',
        'https://images.unsplash.com/photo-1585937421612-70a008356fbe?w=600&q=80',
        'https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?w=600&q=80',
        'https://images.unsplash.com/photo-1567188040759-fb8a883dc6d8?w=600&q=80',
        'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?w=600&q=80',
        'https://images.unsplash.com/photo-1546833999-b9f581a1996d?w=600&q=80',
        'https://images.unsplash.com/photo-1574894709920-11b28e7367e3?w=600&q=80',
    ];
    return $pool[$index % count($pool)];
}
?>
