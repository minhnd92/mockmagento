<?php
class SM_BestSeller_Model_CalcRange
{
    public function toOptionArray()
    {
        return array(

            array('value'=>1, 'label'=>'7 days ago'),
            array('value'=>2, 'label'=>'Last Month'),
            array('value'=>3, 'label'=>'Begin of the year'),
            array('value'=>4, 'label'=>'Last year'),

        );
    }
}