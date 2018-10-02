<?php
namespace app\widgets;

use app\assets\AppAsset;
use app\models\RegionModel;
use Yii;
use yii\helpers\Html;

/**
 * Alert widget renders a message from session flash. All flash messages are displayed
 * in the sequence they were assigned using setFlash. You can set message as following:
 *
 * ```php
 * Yii::$app->session->setFlash('error', 'This is the message');
 * Yii::$app->session->setFlash('success', 'This is the message');
 * Yii::$app->session->setFlash('info', 'This is the message');
 * ```
 *
 * Multiple messages could be set as follows:
 *
 * ```php
 * Yii::$app->session->setFlash('error', ['Error 1', 'Error 2']);
 * ```
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @author Alexander Makarov <sam@rmcreative.ru>
 */
class RegionSelector extends yii\base\Widget
{
    /**
     * @var string the name of the breadcrumb container tag.
     */
    public $tag = 'div';
    /**
     * @var array the HTML attributes for the breadcrumb container tag.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $options = ['class' => 't-region', 'id' => 'dropdownRegionSelect'];
    public $dropdownOption = ['class' => 'dropdown-menu', 'aria-labelledby' => "dropdownRegion"];
    public $selectLinkOption = [
        'class' => 'btn btn-link dropdown-toggle',
        'role' => "button",
        'id' => "dropdownRegion",
        'data-toggle' => "dropdown",
        'aria-haspopup' => "true",
        'aria-expanded' => "false"
    ];
    public $regionId = null;
    /**
     * @var bool whether to HTML-encode the link labels.
     */
    public $encodeLabels = true;

    public $regions = [];

    /**
     * @var string the template used to render each inactive item in the breadcrumbs. The token `{link}`
     * will be replaced with the actual HTML link for each inactive item.
     */
    public $itemClass = "dropdown-item";
    /**
     * @var string the template used to render each active item in the breadcrumbs. The token `{link}`
     * will be replaced with the actual HTML link for each active item.
     */
    public $activeItemClass = "dropdown-item active";


    /**
     * Renders the widget.
     */
    public function run()
    {
        $this->regions = RegionModel::find()->getCollection()->aggregate([      // https://docs.mongodb.com/manual/meta/aggregation-quick-reference/
            [ '$project' => [
                '_id' => [ '$toString' => '$_id' ],
                'name' => '$name',
            ] ]
        ]);

        if (empty($this->regions)) {
            return;
        }

        echo Html::beginTag('div', $this->options);
            echo Html::beginTag('div', ['class' => 'dropdown']);


                $selectedRegionText = " - Выбрать -";
                $links = [];
                foreach ($this->regions as $region) {
                    if($region['_id'] == $this->regionId)
                        $selectedRegionText = " ".$region['name'];

                    $links[] = Html::a($region['name'], '#', [ 'data-id' => $region['_id'], 'class' => $region['_id'] == $this->regionId ? $this->activeItemClass : $this->itemClass ]);
                }

                echo Html::a('Ваш регион '.$selectedRegionText, "#", $this->selectLinkOption);

                echo Html::tag($this->tag, implode('', $links), $this->dropdownOption);
            echo Html::endTag('div');
        echo Html::endTag('div');
    }


}
