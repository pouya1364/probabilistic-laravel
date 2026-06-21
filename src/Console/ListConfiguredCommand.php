<?php

declare(strict_types=1);

namespace ProbabilisticLaravel\Console;

use Illuminate\Console\Command;
use ProbabilisticLaravel\ProbabilisticManager;

final class ListConfiguredCommand extends Command
{
    protected $signature = 'probabilistic:list';
    protected $description = 'List all configured probabilistic data structure instances';

    public function handle(ProbabilisticManager $manager): int
    {
        foreach ($manager->all() as $group => $instances) {
            if (empty($instances)) {
                continue;
            }
            $this->info($group);
            $rows = [];
            foreach ($instances as $name => $params) {
                $rows[] = [$name, implode(', ', array_map(
                    static fn ($k, $v) => "$k=$v",
                    array_keys($params),
                    array_values($params),
                ))];
            }
            $this->table(['Name', 'Parameters'], $rows);
        }

        return self::SUCCESS;
    }
}
