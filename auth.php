<?php

class Auth
{
private $m_db, 
    $m_timeLeft, 
    $m_currTime, 
    $m_banUser, 
    $m_key, 
    $m_hwid, 
    $m_ip;

    public function __construct(mysqli $db)
    {
        $this->m_db = $db;
    }

    // -- safetly stores the recieved url params
    function getData($keyParam, $hwidParam, $banParam)
    {
        $this->m_key = $this->m_db->real_escape_string($keyParam); 
        $this->m_hwid = $this->m_db->real_escape_string($hwidParam); 
        $this->m_banUser = $banParam;
    }

    function sendData($data)
    {

    }

/*     function fetchHWIDData()
    {
        $stmt = $this->m_db->prepare("SELECT * FROM users WHERE hwid = :$this->m_hwid");
        $stmt->bind_param();
    } */

    function firstTimeSetup()
    {
        // get the keyparam and and the hwid with SQL
        // selects the hwid of a user where key = $keyParam

        $result = $this->m_db->query("SELECT hwid, token FROM users WHERE token=$this->m_key");

        $r    = mysqli_fetch_array($result);
        $hwid = $r['hwid'];
        $key  = $r['token'];

        if ($hwid == 0 && $key == $this->m_key)
        {
            // Update the hwid if its zero and the key is real
            // UPDATE users SET hwid = 1111 WHERE token = 1337
            $this->m_db->query("UPDATE users SET hwid=$this->m_hwid WHERE token=$this->m_key");
            echo "first_setup_true\n";
        }
        else echo "first_setup_false\n";
    }

    // -- 0 : DOES_EXIST, 1 : DOESNT_EXIST, 2 : BANNED, 3 : INVALID_KEY
    function Validate()
    {
        // -- if hwid matches and key return true, if key matches but not the hwid (if the key is found for multiple hwids, ban the hwid)
        $result = $this->m_db->query("SELECT token, hwid FROM users WHERE token=$this->m_key");
        $r    = mysqli_fetch_array($result);
        $hwid = $r['hwid'];
        $key  = $r['token'];

        if ($key == $this->m_key)
        {
            if ($hwid == $this->m_hwid)
                return 1;
            else
            {
                $this->banHWID($this->m_hwid);
                $this->banHWID($hwid);

                $this->banReason($this->m_hwid, "Sharing Key");
                $this->banReason($hwid, "Sharing Key");
                return 2;
            }
            return 0;
        }
        return 3;
    }

    function userBanned()
    {
        // -- if hwid matches with a banned hwid return true (key banned)
        $r = $this->m_db->query("SELECT * FROM banned_users WHERE hwid = $this->m_hwid");
        echo "$r->num_rows";
        return $r->num_rows == 1;
    }

    /*
    // -- a shared key results in a ban
    function banSharedKeys()
    {
        $r = $this->m_db->query("SELECT hwid FROM users WHERE token=$this->m_key");
        // -- if there are more than one hwid affiliated with the key BOTH hwid's will be banned
        if ($r->num_rows > 1)
        {
            while ($row = mysqli_fetch_array($r))
            {
                $this->banHWID($row['hwid']);
                $this->banReason($row['hwid'], "Sharing Key");
            }
            return TRUE;
        }
        else return FALSE;
    }
    */
    function getHWID()
    {
        return $this->m_hwid;
    }

    function removeUser()
    {

    }

    function banHWID($hwid)
    {
        // -- add key and hwid to banned_hwids table
        // -- make sure the strings arent empty
        if (!empty($hwid))  
            $this->m_db->query("INSERT INTO banned_users (token, hwid) VALUES ($this->m_key, $hwid)");
    }

    function banReason($hwid, $reason)
    {
        // -- make sure the strings arent empty
        if (!empty($hwid) && !empty($reason))
            $this->m_db->query("INSERT INTO banned_users (hwid, reason) VALUES ($hwid, $reason)");
    }

    // -- gets the ip address from the connected party
    function getIP()
    {
        $this->m_ip = $_SERVER["REMOTE_ADDR"];
    }

    
    function timeLeft()
    {
        
    }

    function timeExpired()
    {
        return FALSE;
    }

    function addTime($time)
    {

    }
}

?>