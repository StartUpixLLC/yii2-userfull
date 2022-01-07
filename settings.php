<?php
namespace common\models;

use Yii;

class Settings
{
    static function getTime($initialTime = 0, $action = false, $interval = 0, $format = 'UNIX')
    {
        $initialTime = !empty($initialTime) ? $initialTime : Yii::$app->db->createCommand('SELECT UNIX_TIMESTAMP()')->queryScalar(); // Выбираем из БД текущее время UNIX по GMT0

        if (in_array($action, ['ADD', 'SUB'])) {
            $_initialTime = Yii::$app->formatter->asDatetime($initialTime, Yii::$app->params['mysqlDateFormat']); // Приводим timestamp к MySQL формату ('Y-MM-dd')

            $time = Yii::$app->db->createCommand('SELECT DATE_' . $action . '("' . $_initialTime . '", INTERVAL ' . $interval . ')')->queryScalar(); // Получаем время в формате 'Y-MM-dd' с учетом сдвига
            $time = strtotime($time); // Переводим время с учетом сдвига обратно в Unix
        } else {
            $time = $initialTime;
        }

        if ($format != 'UNIX') {
            $time = Yii::$app->formatter->asDatetime($time, $format); // Если передан формат отличный от строки UNIX, приводим время к переданному формату 
        }

        return $time;
    }
}
