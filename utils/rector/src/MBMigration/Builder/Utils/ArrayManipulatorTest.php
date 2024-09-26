<?php

namespace Utils\Rector\MBMigration\Builder\Utils;

use MBMigration\Builder\Utils\ArrayManipulator;
use PHPUnit\Framework\TestCase;

class ArrayManipulatorTest extends TestCase
{
    /**
     * @var ArrayManipulator|null
     */
    private $arrayManipulator = null;

    /**
     * Setup function for every test. It initialises the ArrayManipulator.
     */
    protected function setUp(): void
    {
        $this->arrayManipulator = new ArrayManipulator();
    }

    /**
     * Test function for the groupArrayByParentId method of the ArrayManipulator class.
     */
    public function testGroupArrayByParentId()
    {
        $list = json_decode("[{\"id\":25511639,\"category\":\"photo\",\"item_type\":null,\"order_by\":0,\"group\":0,\"parent_id\":null,\"settings\":{\"slide\":{\"fit\":false,\"mobile_fit\":null,\"slide_height\":null,\"slide_width\":null,\"mobile_height\":null,\"mobile_width\":null,\"extension\":\"jpg\",\"mobile_extension\":\"jpg\"}},\"link\":null,\"new_window\":false,\"content\":\"9faf442e-3ae2-40f3-ad8c-efd4d448aa23.jpg\"},{\"id\":13051042,\"category\":\"list\",\"item_type\":null,\"order_by\":0,\"group\":1,\"parent_id\":null,\"settings\":[],\"link\":null,\"new_window\":false,\"content\":null},{\"id\":13051041,\"category\":\"list\",\"item_type\":null,\"order_by\":0,\"group\":1,\"parent_id\":null,\"settings\":[],\"link\":null,\"new_window\":false,\"content\":null},{\"id\":13051040,\"category\":\"list\",\"item_type\":null,\"order_by\":0,\"group\":1,\"parent_id\":null,\"settings\":[],\"link\":null,\"new_window\":false,\"content\":null},{\"id\":13051051,\"category\":\"photo\",\"item_type\":null,\"order_by\":0,\"group\":0,\"parent_id\":13051042,\"settings\":{\"image\":{\"height\":360,\"width\":640,\"alt\":\"\"}},\"link\":null,\"new_window\":false,\"content\":\"5cd2f398-5fc0-43c7-b399-dd6d13c15bbf.jpg\"},{\"id\":13051052,\"category\":\"text\",\"item_type\":\"title\",\"order_by\":1,\"group\":0,\"parent_id\":13051042,\"settings\":[],\"link\":null,\"new_window\":false,\"content\":\"\\u003cp style\\u003d\\\"color: rgb(255, 255, 255);\\\"\\u003eFirst time at\\u003c/p\\u003e\\u003cp style\\u003d\\\"color: rgb(255, 255, 255);\\\"\\u003eNew Life?\\u0026nbsp;\\u003c/p\\u003e\"},{\"id\":13051053,\"category\":\"text\",\"item_type\":\"body\",\"order_by\":2,\"group\":0,\"parent_id\":13051042,\"settings\":[],\"link\":null,\"new_window\":false,\"content\":\"\\u003cp style\\u003d\\\"color: rgb(255, 255, 255); font-weight: 600;\\\"\\u003e\\u003cspan class\\u003d\\\"clovercustom\\\" style\\u003d\\\"font-weight: 200;\\\"\\u003eIt is an honor and a joy to be able to connect with you and to be able to tell you all the good things God is doing at our locations.\\u003c/span\\u003e\\u003c/p\\u003e\\u003cp\\u003e\\u003cstyle\\u003e\\n\\n\\u003c/style\\u003e\\u003c/p\\u003e\\u003cp\\u003e\\u003cstyle\\u003e\\n\\n\\u003c/style\\u003e\\u003c/p\\u003e\"},{\"id\":13051054,\"category\":\"button\",\"item_type\":null,\"order_by\":3,\"group\":0,\"parent_id\":13051042,\"settings\":[],\"link\":\"what-we-believe\",\"new_window\":false,\"content\":\"\\u003cp\\u003eWHAT WE BELIEVE\\u003c/p\\u003e\"},{\"id\":13051047,\"category\":\"photo\",\"item_type\":null,\"order_by\":0,\"group\":0,\"parent_id\":13051041,\"settings\":{\"image\":{\"height\":360,\"width\":640,\"alt\":\"\"}},\"link\":null,\"new_window\":false,\"content\":\"dd85720f-4be4-4441-9932-6c8213a6b60c.jpg\"},{\"id\":13051048,\"category\":\"text\",\"item_type\":\"title\",\"order_by\":1,\"group\":0,\"parent_id\":13051041,\"settings\":[],\"link\":null,\"new_window\":false,\"content\":\"\\u003cp\\u003e\\u003cspan class\\u003d\\\"clovercustom\\\" style\\u003d\\\"color: rgb(255, 255, 255);\\\"\\u003eOur Services\\u003c/span\\u003e\\u003c/p\\u003e\"},{\"id\":13051049,\"category\":\"text\",\"item_type\":\"body\",\"order_by\":2,\"group\":0,\"parent_id\":13051041,\"settings\":[],\"link\":null,\"new_window\":false,\"content\":\"\\u003cp style\\u003d\\\"color: rgb(255, 255, 255);\\\"\\u003eWe have two campuses. Each campus is unique with it\\u0027s own flavor but with the same heart and vision of helping people discover God\\u0027s love and purpose for their lives.\\u003c/p\\u003e\"},{\"id\":13051050,\"category\":\"button\",\"item_type\":null,\"order_by\":3,\"group\":0,\"parent_id\":13051041,\"settings\":[],\"link\":\"services\",\"new_window\":false,\"content\":\"\\u003cp\\u003eJoin us Sunday\\u003c/p\\u003e\"},{\"id\":13051043,\"category\":\"photo\",\"item_type\":null,\"order_by\":0,\"group\":0,\"parent_id\":13051040,\"settings\":{\"image\":{\"height\":360,\"width\":640}},\"link\":null,\"new_window\":false,\"content\":\"ee1e218c-3fac-4c9f-9b61-0d27c7b2e0eb.jpg\"},{\"id\":13051044,\"category\":\"text\",\"item_type\":\"title\",\"order_by\":1,\"group\":0,\"parent_id\":13051040,\"settings\":[],\"link\":null,\"new_window\":false,\"content\":\"\\u003cp\\u003e\\u003cspan class\\u003d\\\"clovercustom\\\" style\\u003d\\\"color: rgb(255, 255, 255);\\\"\\u003eOutreach \\u003cbr\\u003e\\u003c/span\\u003e\\u003c/p\\u003e\"},{\"id\":13051045,\"category\":\"text\",\"item_type\":\"body\",\"order_by\":2,\"group\":0,\"parent_id\":13051040,\"settings\":[],\"link\":null,\"new_window\":false,\"content\":\"\\u003cp style\\u003d\\\"color: rgb(255, 255, 255);\\\"\\u003eAt New Life we believe in supporting Christian programs through donations and volunteering. We strive to be the hands of feet of God here on earth.\\u003cbr\\u003e\\u003c/p\\u003e\"},{\"id\":13051046,\"category\":\"button\",\"item_type\":null,\"order_by\":3,\"group\":0,\"parent_id\":13051040,\"settings\":[],\"link\":\"outreach\",\"new_window\":false,\"content\":\"\\u003cp\\u003e\\u0026nbsp; \\u0026nbsp; \\u0026nbsp; \\u0026nbsp;Learn\\u0026nbsp; \\u0026nbsp; \\u0026nbsp; \\u0026nbsp;\\u003c/p\\u003e\\u003cp\\u003eMore\\u003c/p\\u003e\"}]", true);
        $list = json_decode("[{\"id\":13195028,\"category\":\"text\",\"item_type\":\"title\",\"order_by\":0,\"group\":1,\"parent_id\":null,\"settings\":[],\"link\":null,\"new_window\":false,\"content\":\"\\u003cp\\u003eSunday Gathering\\u003c/p\\u003e\"},{\"id\":13195027,\"category\":\"photo\",\"item_type\":null,\"order_by\":0,\"group\":0,\"parent_id\":null,\"settings\":{\"image\":{\"height\":718,\"width\":718}},\"link\":\"https://www.google.com/maps/dir//433+Barney+Ave,+Millen,+GA+30442/@32.8088275,-81.9423848,17z/data\\u003d!4m8!4m7!1m0!1m5!1m1!1s0x88fa1e1a65272c67:0x183f58558b2567ce!2m2!1d-81.9401961!2d32.8088275\",\"new_window\":true,\"content\":\"4d68d35d-249a-4b4c-b565-06bd1aa1dabb.jpg\"},{\"id\":13195029,\"category\":\"text\",\"item_type\":\"body\",\"order_by\":1,\"group\":1,\"parent_id\":null,\"settings\":[],\"link\":null,\"new_window\":false,\"content\":\"\\u003cp style\\u003d\\\"font-size: 1.3298em;\\\"\\u003eWe believe that Sunday mornings should be a time that we celebrate what God has been doing in us all week.\\u003c/p\\u003e\\u003cp style\\u003d\\\"font-size: 1.3298em;\\\"\\u003e\\u003cbr\\u003e\\u003c/p\\u003e\\u003cp style\\u003d\\\"font-size: 1.3298em;\\\"\\u003eOur Sunday morning Gatherings are a time to worship, teach, and spend time in prayer together. Our services are not our primary focus of ministry, but complimentary.\\u003c/p\\u003e\\u003cp style\\u003d\\\"font-size: 1.3298em;\\\"\\u003e\\u003cbr\\u003e\\u003c/p\\u003e\\u003cp style\\u003d\\\"font-size: 1.3298em;\\\"\\u003eService starts at 11:00 AM:\\u003c/p\\u003e\\u003cp style\\u003d\\\"font-size: 1.3298em;\\\"\\u003eJenkins County High School\\u003c/p\\u003e\\u003cp style\\u003d\\\"font-size: 1.3298em;\\\"\\u003e433 Barney Avenue\\u003c/p\\u003e\\u003cp style\\u003d\\\"font-size: 1.3298em;\\\"\\u003eMillen, Ga. 30442\\u003c/p\\u003e\\u003cp style\\u003d\\\"font-size: 1.3298em;\\\"\\u003e\\u003cbr\\u003e\\u003c/p\\u003e\\u003cp style\\u003d\\\"font-size: 1.3298em;\\\"\\u003e(Church Entrance behind gymnasium on North Avenue)\\u003c/p\\u003e\\u003cp\\u003e\\u003cbr\\u003e\\u003c/p\\u003e\\u003cp\\u003e\\u003cbr\\u003e\\u003c/p\\u003e\\u003cp\\u003e\\u003c/p\\u003e\\u003ccenter\\u003e\\u003cp style\\u003d\\\"font-size: 1.3298em;\\\"\\u003e\\u0026nbsp;\\u003ca href\\u003d\\\"https://www.google.com/maps/place/32%C2%B048\\u002741.1%22N+81%C2%B056\\u002738.0%22W/@32.8114167,-81.9438889,734m/data\\u003d!3m2!1e3!4b1!4m6!3m5!1s0x0:0x0!7e2!8m2!3d32.8114063!4d-81.9438789\\\" class\\u003d\\\"sites-button cloverlinks\\\" role\\u003d\\\"button\\\" data-location\\u003d\\\"external\\\" data-detail\\u003d\\\"https://www.google.com/maps/place/32%C2%B048\\u002741.1%22N+81%C2%B056\\u002738.0%22W/@32.8114167,-81.9438889,734m/data\\u003d!3m2!1e3!4b1!4m6!3m5!1s0x0:0x0!7e2!8m2!3d32.8114063!4d-81.9438789\\\" data-category\\u003d\\\"button\\\" target\\u003d\\\"_blank\\\"\\u003eGet Directions Here\\u003c/a\\u003e \\u003c/p\\u003e\\u003c/center\\u003e\\u003cp\\u003e\\u003c/p\\u003e\\u003cp\\u003e\\u003cbr\\u003e\\u003c/p\\u003e\\u003cp\\u003e\\u003cbr\\u003e\\u003c/p\\u003e\\u003cp\\u003e\\u003cbr\\u003e\\u003c/p\\u003e\\u003cp\\u003e\\u003cbr\\u003e\\u003c/p\\u003e\\u003cp\\u003e\\u003cbr\\u003e\\u003c/p\\u003e\\u003cp\\u003e\\u003cbr\\u003e\\u003c/p\\u003e\\u003cp\\u003e\\u003cbr\\u003e\\u003c/p\\u003e\"}]", true);

        $result = $this->arrayManipulator->groupItemsListByParentId($list, 'gallery');

        $expected = [
            123 => [
                'parent_id' => null,
                'id' => 123,
                'content' => null,
                'category' => 'list',
                'order_by' => 2,
                'item' => [
                    [
                        'parent_id' => 123,
                        'id' => 456,
                        'content' => 'child',
                        'category' => 'list',
                        'order_by' => 1
                    ]
                ]
            ]
        ];

        $this->assertEquals($expected, $result);
    }

    /**
     * Teardown function for every test. It cleans up the ArrayManipulator.
     */
    protected function tearDown(): void
    {
        $this->arrayManipulator = null;
    }
}
