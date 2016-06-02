<?php

namespace app\models;

use Yii;
use yii\base\Model;

class EmailForm extends Model
{
    public $subject;
    public $recipients;
    public $body;
    private $_emails;

    public function rules()
    {
        return [
            /*[['subject' ,'recipients' ,'body'], 'required'],*/
            [['recipients'], 'validateRecipients'],
        ];
    }


    public function validateRecipients($attribute, $params)
    {
        $emails = explode(',', $this->$attribute);
        if (count($emails) === 1) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->addError($attribute, 'Invalid email format');
            }
        } else if (count($emails) > 1) {
            foreach ($emails as $email) {
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $this->addError($attribute, 'Invalid email format');
                }
            }
            $this->_emails = $emails;
        }
    }

    public function send()
    {
        if ($this->validate()) {
            /*Yii::$app->mailer->compose()
                ->setFrom('tomas.cabagay@gmail.com')
                ->setTo($this->recipients)
                ->setSubject($this->subject)
                ->setHtmlBody($this->body)
                ->send();*/
            return true;
        }
        return false;
    }

}
