# Jane OpenApi 3 Library

Library is simple boilerplate and setup for our generated SDKs.
It will automatically install all required runtime libraries.

[[_TOC_]]

## Quick Setup
1. Add library into sdk dependencies `composer require websupport/jane-openapi`
2. Create `.jane-openapi` config file for SDK generation.

## Pagination Support

Library supports pagination for endpoints which return array of items and receives pagination headers in responses.
Returned collection is then [PaginatedCollection](./src/PaginatedCollection.php), which implements all array like php interfaces.

To check, whether returned collection is paginated just perform simple check:
```php
use WebSupport\JaneOpenApi\PaginatedCollection;

$items = $client->getListOfItems(); //returned items are `iterable`
if ($items instanceof PaginatedCollection) {
    // woohoo, I've received paginated collection
    $items->getPage(); // returns Page object containing all paging data
}

//or there is also ine helper method
if (PaginatedCollection::isPaginated($items)) {
    $page = $items->getPage();
}

// setting pagination for requested collection
$itemsFromNextPage = $client->getListOfItems($page->next()); //returned hostings from next page
```

> This is possible due to our custom [PaginatedPsr7EndpointTrait](./src/PaginatedPsr7EndpointTrait.php), always make sure
> that you have properly configure `endpoint-generator` in your `.jane-openapi` config file.

## Example `.jane-openapi` config file
You should have this file committed in your `sdk` folder right next to project `src`.
```php
<?php

return [
    'openapi-file' => __DIR__.'/../spec/spec.json',
    'namespace' => 'WebSupport\{Application}\Sdk\Generated',
    'directory' => __DIR__.'/src/Generated',
    'endpoint-generator' => 'WebSupport\JaneOpenApi\Generator\PaginatedPsr7EndpointGenerator',
];
```
