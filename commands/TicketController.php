<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\ListOfTicketSearch;
use yii\console\Controller;
use yii\console\ExitCode;
use app\models\Ticket;
use yii\helpers\VarDumper;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class TicketController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @throws \Exception
     * @return bool
     */
    public function actionClosetickets()
    {
        $query = Ticket::find()
            ->leftJoin('users', 'ticket.user_id = users.id')
            ->leftJoin('comment', 'comment.ticket_id = ticket.id')
            ->where('status = true')
            ->andWhere('comment.user_id = (SELECT comment.user_id FROM comment, users WHERE users.is_admin = true ORDER BY comment.id DESC LIMIT 1)')
            ->andWhere("comment.create_time > now() - interval '2 minutes'")
            ->all();

        foreach ($query as $q) {
            var_dump($q['id']);
            $q->status = false;

            try {
                if($q->save()) {
                    throw new \Exception('Failed to save ticket: ' . VarDumper::dumpAsString($q));
                };
            } catch(\Exception $exception) {
                \Yii::error($exception->getMessage());
            }

        }
    }
}
