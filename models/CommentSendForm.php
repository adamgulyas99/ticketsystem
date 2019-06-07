<?php
/**
 * Created by PhpStorm.
 * User: adam
 * Date: 6/6/19
 * Time: 11:09 AM
 */

namespace app\models;


use yii\base\Model;
use Yii;

class CommentSendForm extends Model
{
    public $content;

    public function formName()
    {
        return 'comment-send-form';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'string'],
        ];
    }

    /**
     * Saves an instance of Comment.
     *
     * @return bool|null
     */
    public function sendComment()
    {
        if (!$this->validate()) {
            return null;
        }

        date_default_timezone_set("Europe/Budapest");
        $date = date('Y-m-d H:i:s');
        $userid = Yii::$app->user->identity->getId();

        $comment = new Comment();
        $comment->content = $this->content;
        $comment->create_time = $date;
        $comment->user_id = $userid;
        $comment->ticket_id = Yii::$app->request->get('id');
        if (!$comment->ticket->status && !Yii::$app->user->identity->is_admin) {
            $comment->ticket->status = true;
        }

        return $comment->ticket->save() && $comment->save();
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