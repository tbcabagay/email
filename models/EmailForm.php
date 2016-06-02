<?php

namespace app\models;

use Yii;
use yii\base\Model;

class EmailForm extends Model
{
    public $subject;
    public $body;
    public $to;
    public $cc;

    private $_emails = [];

    public function rules()
    {
        return [
            [['subject', 'to', 'body'], 'required'],
            [['to', 'cc'], 'validateEmails'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'subject' => Yii::t('app', 'Subject'),
            'body' => Yii::t('app', 'Body'),
            'to' => Yii::t('app', 'To'),
            'cc' => Yii::t('app', 'CC'),
        ];
    }

    public function validateEmails($attribute, $params)
    {
        if (strpos($this->$attribute, ',') === false) {
            $this->_filterEmail($this->$attribute, $attribute);
        } else {
            $emails = explode(',', $this->$attribute);
            foreach ($emails as $email) {
                $this->_filterEmail($email, $attribute);
            }
            $this->_emails[$attribute] = $emails;
        }
    }

    private function _filterEmail($email, $attribute)
    {
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $this->addError($attribute, 'Invalid email format');
        }
    }

    public function send()
    {
        if ($this->validate()) {
            /*echo "<pre>"; print_r($this->_emails); exit();*/
            if (empty($this->_emails['to']) === false) {
                foreach ($this->_emails['to'] as $emailTo) {
                    $mail = Yii::$app->mailer->compose()
                        ->setFrom('tomas.cabagay@gmail.com')
                        ->setSubject($this->subject)
                        ->setHtmlBody($this->body)
                        ->setTo($emailTo);
                    if (isset($this->_emails['cc'])) {
                        $mail->setCc($this->_emails['cc']);
                    } else {
                        $mail->setCc($this->cc);
                    }
                    $messages[] = $mail;
                }
                Yii::$app->mailer->sendMultiple($messages);
            } else {
                $mail = Yii::$app->mailer->compose()
                    ->setFrom('tomas.cabagay@gmail.com')
                    ->setSubject($this->subject)
                    ->setHtmlBody($this->body)
                    ->setTo($this->to);
                if (isset($this->_emails['cc'])) {
                    $mail->setCc($this->_emails['cc']);
                } else {
                    $mail->setCc($this->cc);
                }
                $mail->send();
            }
            return true;
        }
        return false;
    }

}
