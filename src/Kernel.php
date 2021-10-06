<?php

namespace Storekeeper\AssesFullstackApi;

class Kernel
{
    public function handleIndexRequest(): void
    {
        echo json_encode(['title' => 'Assessment from api', 'time' => time()]);
    }
}
