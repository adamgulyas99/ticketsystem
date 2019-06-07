<?php
/**
 * Created by PhpStorm.
 * User: adam
 * Date: 6/4/19
 * Time: 9:30 AM
 */

namespace app\models;

use Yii;
use yii\base\Model;

class TicketSendForm extends Model
{
    public $heading;
    public $priority;
    public $content;

    /**
     * {@inheritdoc}
     */
    public function formName()
    {
        return 'ticket-send-form';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['heading'], 'string'],
            [['content'], 'string'],
            [['heading', 'content'], 'required', 'message' => 'Need to fulfil all fields!'],
        ];
    }

    /**
     * Send ticket up.
     *
     * @return bool whether the creating new ticket and comment was successful
     */
    public function sendTicket()
    {
        if (!$this->validate()) {
            return null;
        }

        $errors = [];

        date_default_timezone_set("Europe/Budapest");
        $date = date('Y-m-d H:i:s');
        $userid = Yii::$app->user->identity->getId();

        $ticket = new Ticket();
        $ticket->heading = $this->heading;
        $ticket->priority = 'normal';
        $ticket->status = true;
        $ticket->user_id = $userid;
        if(!$ticket->save()) {
            $errors[] = $ticket->getErrors();
        }

        $comment = new Comment();
        $comment->content = $this->content;
        $comment->create_time = $date;
        $comment->user_id = $userid;
        $comment->ticket_id = $ticket->id;

        if(!$comment->save()) {
            $errors[] = $comment->getErrors();
        }

        if(count($errors) > 0) {
            var_dump($errors);
            return false;
        }

        return true;
    }

    /**
     * Getting database connection.
     *
     * @return yii\db\Connection
     */
    public static function getDb() {
        return Yii::$app->db;
    }
}