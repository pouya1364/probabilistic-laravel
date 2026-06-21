<?php

declare(strict_types=1);

return [
    'bloom_filters' => [
        // 'emails_seen' => ['expected_items' => 100000, 'false_positive_rate' => 0.01],
    ],
    'counting_bloom_filters' => [
        // 'active_sessions' => ['expected_items' => 50000, 'false_positive_rate' => 0.01],
    ],
    'cuckoo_filters' => [
        // 'rate_limited_ips' => ['expected_items' => 100000],
    ],
    'count_min_sketches' => [
        // 'page_view_counts' => ['width' => 2000, 'depth' => 5],
    ],
    'hyperloglogs' => [
        // 'unique_visitors' => ['precision' => 14],
    ],
];
