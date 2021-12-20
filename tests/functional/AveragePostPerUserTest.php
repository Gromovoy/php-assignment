<?php

declare(strict_types=1);

namespace Tests\functional;

use PHPUnit\Framework\TestCase;
use Statistics\Dto\ParamsTo;
use App\Controller\Factory\StatisticsControllerFactory;


/**
 * Class AveragePostPerUser
 *
 * @package Tests\unit
 */
class AveragePostPerUserTest extends TestCase
{
    /**
     * @test
     * @dataProvider checkAccumulateDataProvider
     *
     * @param $AvgPost
     * @param ParamsTo[]
     */
    public function doAccumulateTest(
        array $avgPost
    )
    {
        $factory = new StatisticsControllerFactory;
        $controller = $factory->create();
        /*
         * #todo: the better way to do it is refactor $controller by creation render class and injecting it.
         */
        ob_start();
        @$controller->indexAction(["month" => "August, 2018"]);
        $output = ob_get_clean();
        $decodedStat = json_decode($output,true);

        foreach ($decodedStat["stats"]["children"] as $statItem) {
            if ($statItem['name'] == "average-posts-per-user") {
                $this->assertEquals($avgPost, $statItem);
            }
        }
    }

    /**
     * @return mixed[][]
     */
    public function checkAccumulateDataProvider(): array
    {
        $avgPost = [
            "name" => "average-posts-per-user",
            "label" => "Average number of posts per user in a given month",
            "value" => 1,
            "units" => "posts",
            "splitPeriod" => null,
            "children" => null
        ];

        return
            [
                'success' => [
                    $avgPost
                ],
            ];
    }
}
