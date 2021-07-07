<?php 

class Secret 
{

    private $conn;

    public $text;
    public $expiresAfterViews;
    public $expiresAfter;
    public $createdAt;
    public $hash;


    public function __construct($db)
    {
        $this->conn = $db;
    }

    
    public function getSecretByHash()
    {
        $stmt = $this->conn->prepare("SELECT * FROM secret WHERE hash=?");
        $stmt->execute([$this->hash]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result == null) {
            return null;
        } 
        else {
            if ($result['expiresAt'] > date('Y-m-d H:i') && $result['remainingViews'] > 0) {
                $this->updateSecret($this->hash);

                $this->hash = $result['hash'];
                $this->text = $result['secretText'];
                $this->expiresAfter = $result['expiresAt'];
                $this->expiresAfterViews = $result['remainingViews'] - 1;
                $this->createdAt = $result['createdAt'];
    
                $this->deleteSecret();
    
                return $result;
            }
            else {
                $this->deleteSecret();

                return null;
            }
            
        }
    }


    public function updateSecret($hash) 
    {
        $stmt = $this->conn->prepare("UPDATE secret SET remainingViews = remainingViews-1 WHERE hash=?; commit;");
        $stmt->execute([$hash]);
    }


    public function deleteSecret() 
    {
        $stmt = $this->conn->prepare("DELETE FROM secret WHERE hash=? AND (remainingViews < 1 OR expiresAt < sysdate())");
        $stmt->execute([$this->hash]);
    }


    public function setSecret() 
    {
        try {
            $stmt = $this->conn->prepare("INSERT INTO secret(hash, secretText, createdAt, expiresAt, remainingViews) 
            VALUES (?, ?, sysdate(), ?, ?)");
            if (!$this->validSecret($this->expiresAfter, $this->expiresAfterViews)) {
                return false;
            }
            $this->hash = uniqid();
            $stmt->execute([$this->hash, $this->text, $this->setExpiresAt($this->expiresAfter), $this->expiresAfterViews]);
            return $stmt;
        }
        catch (Exception $e) {
            $this->setSecret($this->text, $this->expiresAfter, $this->expiresAfterViews);
        }

    }


    public function validSecret($expiresAfter, $expiresAfterViews) 
    {
        if (is_numeric($expiresAfterViews) && is_numeric($expiresAfter) && $expiresAfter >= 0 && $expiresAfterViews > 0) {
            return true;
        }
        else {
            return false;
        }
    }


    public function setExpiresAt($expiresAfter) 
    {
        $today = date('Y-m-d H:i');
        $dateTime = new DateTime($today);
        $dateTime = $dateTime->modify('+'. $expiresAfter .' minutes')->format('Y-m-d H:i');
        return $dateTime;
    }

}
