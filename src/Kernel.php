<?php

namespace Storekeeper\AssesFullstackApi;

class Kernel
{
    public function handleIndexRequest(): void
    {
        echo json_encode(['title' => 'Discount service', 'time' => time()]);
    }
}
