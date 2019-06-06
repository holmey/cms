<?php
/**
 * @link https://craftcms.com/
 * @copyright Copyright (c) Pixel & Tonic, Inc.
 * @license https://craftcms.github.io/license/
 */

namespace crafttests\unit;

use Codeception\Test\Unit;
use Craft;
use craft\db\Query;
use crafttests\fixtures\EntryWithFieldsFixture;
use UnitTester;

/**
 * Unit tests for App
 *
 * @author Pixel & Tonic, Inc. <support@pixelandtonic.com>
 * @author Global Network Group | Giel Tettelaar <giel@yellowflash.net>
 * @since 3.2
 */
class FieldLayoutTest extends Unit
{
    // Public Properties
    // =========================================================================

    /**
     * @var UnitTester
     */
    protected $tester;

    // Public Methods
    // =========================================================================

    public function _fixtures()
    {
        return [
            'entry-with-fields' => [
                'class' => EntryWithFieldsFixture::class
            ]
        ];
    }

    // Tests
    // =========================================================================

    /**
     * @throws \yii\base\NotSupportedException
     */
    public function testFieldLayoutMatrix()
    {
        $tableNames = Craft::$app->getDb()->getSchema()->tableNames;
        $matrixTableName = Craft::$app->getDb()->tablePrefix.'matrixcontent_matrixfirst';

        $this->assertContains($matrixTableName, $tableNames);

        $matrixRows = (new Query())
            ->select('*')->from($matrixTableName)->all();

        foreach ($matrixRows as $row) {
            $this->assertSame('Some text',$row['field_aBlock_firstSubfield']);
        }
    }
}
