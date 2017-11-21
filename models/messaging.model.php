<?php

require 'models/basicModel.php';

class MessagingModel extends BasicModel{
    
    public function sendMessage($sender,$messageData){
        $senderInfo = $this->getAllUserInfo($sender);
        
        if($senderInfo !== false){
            $successful = 'Sent to: ';
            $failed = '<br>Failed to send to: ';
            $messageQuery = $this->connector->prepare("INSERT INTO messages(recipient_ids,sender_id,subject,body) VALUES(:recip_id,:sender_id,:subject,:body)");
            
            foreach($messageData['recip'] as $recipientUserName){
                $recipient = $this->getAllUserInfo($recipientUserName);
                
                if($recipient === false){
                    $failed .= $recipientUserName.' ';
                }else{
                    $messageQuery->bindParam(':recip_id', $recipient['userid'], PDO::PARAM_STR);
                    $messageQuery->bindParam(':sender_id', $senderInfo['userid'], PDO::PARAM_STR);
                    $messageQuery->bindParam(':subject', $messageData['subject'], PDO::PARAM_STR);
                    $messageQuery->bindParam(':body', $messageData['message'], PDO::PARAM_STR);
                    $messageQuery->execute();
                    $successful .= $recipientUserName.' ';
                }
            }
            return $successful.$failed;
        }
        return 'User Does Not Exsist';
    }
    
    public function recents($user){
        $recentsQuery = $this->connector->prepare("select * from messages where recipient_ids in (select userid from usrs where username = :user) order by msgid desc limit 10;");
        $recentsQuery->bindParam(':user', $user, PDO::PARAM_STR);
        $recentsQuery->execute();
        $results = $recentsQuery->fetchAll(PDO::FETCH_ASSOC);
        
        if($results){
            return $results;
        }
        return false;
    }
    
    public function is_read($messageID){
        $queryString = $this->connector->prepare('SELECT msg_rid FROM messages_read WHERE msg_rid = :msgid');
        $queryString->bindParam(':msgid', $messageID, PDO::PARAM_STR);
        $queryString->execute();
        
        if($queryString->fetch()){
            return true;
        }
        return false;
    }
    
    public function markMessageRead($msgid){
        $messageQuery = $this->connector->prepare('SELECT * FROM messages WHERE msgid = :ms_id');
        $messageQuery->bindParam(':ms_id', $msgid, PDO::PARAM_STR);
        $messageQuery->execute();
        
        $message = $messageQuery->fetch(PDO::FETCH_ASSOC);
        if($message){
            $readQuery = $this->connector->prepare('INSERT INTO messages_read(msgid,reader_id) VALUES(:msgid, :reader_id)');
            $readQuery->bindParam(':msgid', $msgid, PDO::PARAM_STR);
            $readQuery->bindParam(':reader_id', $message['recipient_ids'], PDO::PARAM_STR);
            
            if($readQuery->execute()){
                return true;
            }
        }
        return false;
    }
}