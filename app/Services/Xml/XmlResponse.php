<?php

namespace App\Services\Xml;

use Illuminate\Http\Response;
use SimpleXMLElement;

class XmlResponse {
    public static function arrayToXml(array $data, ?SimpleXMLElement $xmlData = null): Response {
        if ($xmlData === null) {
            $xmlData = new SimpleXMLElement('<root/>');
        }

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                self::arrayToXml($value, $xmlData->addChild($key));
            } else {
                $xmlData->addChild("$key", htmlspecialchars("$value"));
            }
        }

        return response($xmlData->asXML(), 200)->header('Content-Type', 'application/xml');
    }
}
