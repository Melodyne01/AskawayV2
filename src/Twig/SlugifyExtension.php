<?php

namespace App\Twig;

use DateTime;
use Twig\TwigFilter;
use Twig\Extension\AbstractExtension;
use Symfony\Component\String\Slugger\SluggerInterface;

class SlugifyExtension extends AbstractExtension{

    private $slugger;

    public function getFilters()
    {
        return [
            new TwigFilter('slugify', [$this, 'slugifyString']),
        ];
    }
    public static function slugifyString(string $text, string $divider = '-'): string
    {
      // replace non letter or digits by divider
    $text = preg_replace('~[^\pL\d]+~u', $divider, $text);

    // transliterate
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

    // remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);

    // trim
    $text = trim($text, $divider);

    // remove duplicate divider
    $text = preg_replace('~-+~', $divider, $text);

    // lowercase
    $text = strtolower($text);

    if (empty($text)) {
        return 'n-a';
    }

    return $text;
    }
}

?>